<?php

namespace App\Imports;

use App\Models\Dependent;
use App\Models\Subscriber;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class DependentsImport implements ToCollection, WithHeadingRow, WithBatchInserts, WithChunkReading
{
    protected $importedCount = 0;
    protected $updatedCount = 0;
    protected $errorCount = 0;
    protected $errors = [];

    public function collection(Collection $rows)
    {
        foreach ($rows as $index => $row) {
            $rowNumber = $index + 2; // +2 لأن الفهرس يبدأ من 0 والصف الأول هو العناوين
            
            try {
                DB::beginTransaction();
                
                // تنظيف البيانات
                $data = $this->cleanRowData($row->toArray());
                
                // التحقق من صحة البيانات
                $validator = $this->validateRow($data, $rowNumber);
                
                if ($validator->fails()) {
                    $this->errors[] = [
                        'row' => $rowNumber,
                        'errors' => $validator->errors()->all(),
                        'data' => $data
                    ];
                    $this->errorCount++;
                    DB::rollBack();
                    continue;
                }
                
                // البحث عن المشترك
                $subscriber = $this->findSubscriber($data);
                
                if (!$subscriber) {
                    $this->errors[] = [
                        'row' => $rowNumber,
                        'errors' => ['لم يتم العثور على المشترك المحدد'],
                        'data' => $data
                    ];
                    $this->errorCount++;
                    DB::rollBack();
                    continue;
                }
                
                // البحث عن تابع موجود
                $existingDependent = $this->findExistingDependent($subscriber, $data);
                
                if ($existingDependent) {
                    // تحديث التابع الموجود
                    $this->updateDependent($existingDependent, $data);
                    $this->updatedCount++;
                } else {
                    // إنشاء تابع جديد
                    $this->createDependent($subscriber, $data);
                    $this->importedCount++;
                }
                
                // تحديث عدد التابعين للمشترك
                $subscriber->update(['dependents_count' => $subscriber->dependents()->count()]);
                
                DB::commit();
                
            } catch (\Exception $e) {
                DB::rollBack();
                $this->errors[] = [
                    'row' => $rowNumber,
                    'errors' => ['خطأ في المعالجة: ' . $e->getMessage()],
                    'data' => $row->toArray()
                ];
                $this->errorCount++;
            }
        }
    }

    protected function cleanRowData($row)
    {
        return [
            'dependent_name' => trim($row['اسم_التابع'] ?? $row['dependent_name'] ?? ''),
            'dependent_nationality' => trim($row['جنسية_التابع'] ?? $row['dependent_nationality'] ?? ''),
            'dependent_id_number' => trim($row['رقم_هوية_التابع'] ?? $row['dependent_id_number'] ?? ''),
            'dependent_price' => $this->parseDecimal($row['سعر_التابع'] ?? $row['dependent_price'] ?? 0),
            'dependent_notes' => trim($row['ملاحظات_التابع'] ?? $row['dependent_notes'] ?? ''),
            
            // بيانات المشترك للربط
            'subscriber_name' => trim($row['اسم_المشترك_الاساسي'] ?? $row['subscriber_name'] ?? ''),
            'subscriber_phone' => $this->cleanPhone($row['رقم_جوال_المشترك'] ?? $row['subscriber_phone'] ?? ''),
            'subscriber_card_number' => trim($row['رقم_بطاقة_المشترك'] ?? $row['subscriber_card_number'] ?? ''),
            'subscriber_id_number' => trim($row['رقم_هوية_المشترك'] ?? $row['subscriber_id_number'] ?? ''),
        ];
    }

    protected function validateRow($data, $rowNumber)
    {
        $rules = [
            'dependent_name' => 'required|string|max:255',
            'dependent_nationality' => 'required|string|max:255',
            'dependent_id_number' => 'nullable|string|max:20',
            'dependent_price' => 'nullable|numeric|min:0',
            'dependent_notes' => 'nullable|string|max:1000',
        ];

        $messages = [
            'dependent_name.required' => 'اسم التابع مطلوب',
            'dependent_nationality.required' => 'جنسية التابع مطلوبة',
            'dependent_price.numeric' => 'سعر التابع يجب أن يكون رقماً',
            'dependent_price.min' => 'سعر التابع يجب أن يكون أكبر من أو يساوي صفر',
        ];

        return Validator::make($data, $rules, $messages);
    }

    protected function findSubscriber($data)
    {
        $query = Subscriber::query();
        
        // البحث بأولوية: رقم البطاقة، ثم رقم الجوال، ثم رقم الهوية، ثم الاسم
        if (!empty($data['subscriber_card_number'])) {
            $subscriber = $query->where('card_number', $data['subscriber_card_number'])->first();
            if ($subscriber) return $subscriber;
        }
        
        if (!empty($data['subscriber_phone'])) {
            $subscriber = $query->where('phone', $data['subscriber_phone'])->first();
            if ($subscriber) return $subscriber;
        }
        
        if (!empty($data['subscriber_id_number'])) {
            $subscriber = $query->where('id_number', $data['subscriber_id_number'])->first();
            if ($subscriber) return $subscriber;
        }
        
        if (!empty($data['subscriber_name'])) {
            $subscriber = $query->where('name', 'like', '%' . $data['subscriber_name'] . '%')->first();
            if ($subscriber) return $subscriber;
        }
        
        return null;
    }

    protected function findExistingDependent($subscriber, $data)
    {
        $query = $subscriber->dependents();
        
        // البحث بالاسم أو رقم الهوية
        if (!empty($data['dependent_id_number'])) {
            $dependent = $query->where('id_number', $data['dependent_id_number'])->first();
            if ($dependent) return $dependent;
        }
        
        return $query->where('name', $data['dependent_name'])->first();
    }

    protected function createDependent($subscriber, $data)
    {
        return Dependent::create([
            'subscriber_id' => $subscriber->id,
            'name' => $data['dependent_name'],
            'nationality' => $data['dependent_nationality'],
            'id_number' => $data['dependent_id_number'],
            'dependent_price' => $data['dependent_price'],
            'notes' => $data['dependent_notes'],
        ]);
    }

    protected function updateDependent($dependent, $data)
    {
        return $dependent->update([
            'name' => $data['dependent_name'],
            'nationality' => $data['dependent_nationality'],
            'id_number' => $data['dependent_id_number'],
            'dependent_price' => $data['dependent_price'],
            'notes' => $data['dependent_notes'],
        ]);
    }

    // Helper methods
    protected function cleanPhone($phone)
    {
        $phone = preg_replace('/\D/', '', $phone);
        if (strlen($phone) === 9 && substr($phone, 0, 1) === '5') {
            return '0' . $phone;
        }
        return $phone;
    }

    protected function parseDecimal($value)
    {
        if (empty($value)) return 0;
        return (float) str_replace(',', '', $value);
    }

    // Getters for results
    public function getImportedCount()
    {
        return $this->importedCount;
    }

    public function getUpdatedCount()
    {
        return $this->updatedCount;
    }

    public function getErrorCount()
    {
        return $this->errorCount;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function batchSize(): int
    {
        return 100;
    }

    public function chunkSize(): int
    {
        return 100;
    }
}
