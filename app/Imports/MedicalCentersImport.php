<?php

namespace App\Imports;

use App\Models\MedicalCenter;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsOnError;
use Maatwebsite\Excel\Concerns\SkipsErrors;

class MedicalCentersImport implements ToCollection, WithHeadingRow, WithValidation, SkipsOnError
{
    use SkipsErrors;

    private $importedCount = 0;
    private $errorCount = 0;
    private $errorDetails = [];

    /**
     * @param Collection $collection
     */
    public function collection(Collection $collection)
    {
        foreach ($collection as $row) {
            try {
                $this->processRow($row);
                $this->importedCount++;
            } catch (\Exception $e) {
                $this->errorCount++;
                $this->errorDetails[] = [
                    'row' => $this->importedCount + $this->errorCount + 1,
                    'error' => $e->getMessage(),
                    'data' => $row->toArray()
                ];
            }
        }
    }

    /**
     * Process a single row
     */
    private function processRow($row)
    {
        // تحويل البيانات
        $data = [
            'name' => $row['name'] ?? $row['اسم_المركز'] ?? null,
            'description' => $row['description'] ?? $row['الوصف'] ?? null,
            'region' => $row['region'] ?? $row['المنطقة'] ?? null,
            'city' => $row['city'] ?? $row['المدينة'] ?? null,
            'address' => $row['address'] ?? $row['العنوان'] ?? null,
            'phone' => $row['phone'] ?? $row['الهاتف'] ?? null,
            'email' => $row['email'] ?? $row['البريد_الإلكتروني'] ?? null,
            'website' => $row['website'] ?? $row['الموقع_الإلكتروني'] ?? null,
            'type' => $row['type'] ?? $row['نوع_المركز_1_12'] ?? null,
            'status' => $row['status'] ?? $row['الحالة_active_inactive_pending'] ?? 'pending',
            'contract_status' => $row['contract_status'] ?? $row['حالة_العقد_active_expired_pending'] ?? null,
            'contract_start_date' => $this->parseDate($row['contract_start_date'] ?? $row['تاريخ_بداية_العقد_yyyy_mm_dd'] ?? null),
            'contract_end_date' => $this->parseDate($row['contract_end_date'] ?? $row['تاريخ_نهاية_العقد_yyyy_mm_dd'] ?? null),
        ];

        // إنشاء slug
        if (empty($data['slug'])) {
            $data['slug'] = Str::slug($data['name']);
        }

        // التحقق من صحة البيانات
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'region' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'type' => 'required|integer|min:1|max:12',
            'email' => 'nullable|email|max:255',
            'website' => 'nullable|url|max:255',
            'status' => 'in:active,inactive,pending,suspended',
            'contract_status' => 'nullable|in:active,expired,pending',
        ]);

        if ($validator->fails()) {
            throw new \Exception('خطأ في التحقق من البيانات: ' . implode(', ', $validator->errors()->all()));
        }

        // إضافة معرف المنشئ
        $data['created_by'] = auth()->id();

        // إنشاء أو تحديث المركز الطبي
        MedicalCenter::updateOrCreate(
            ['slug' => $data['slug']],
            $data
        );
    }

    /**
     * Parse date from various formats
     */
    private function parseDate($date)
    {
        if (empty($date)) {
            return null;
        }

        // إذا كان التاريخ رقم (Excel date)
        if (is_numeric($date)) {
            return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($date)->format('Y-m-d');
        }

        // محاولة تحويل التاريخ
        try {
            return date('Y-m-d', strtotime($date));
        } catch (\Exception $e) {
            return null;
        }
    }



    /**
     * Validation rules
     */
    public function rules(): array
    {
        return [
            '*.name' => 'required|string|max:255',
            '*.region' => 'required|string|max:100',
            '*.city' => 'required|string|max:100',
            '*.type' => 'required|integer|min:1|max:12',
        ];
    }

    /**
     * Get imported count
     */
    public function getImportedCount(): int
    {
        return $this->importedCount;
    }

    /**
     * Get error count
     */
    public function getErrors(): int
    {
        return $this->errorCount;
    }

    /**
     * Get error details
     */
    public function getErrorDetails(): array
    {
        return $this->errorDetails;
    }
}
