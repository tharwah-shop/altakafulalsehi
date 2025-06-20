<?php

namespace App\Imports;

use App\Models\Subscriber;
use App\Models\City;
use App\Models\Package;
use App\Models\Dependent;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;

class SubscribersImport implements ToCollection, WithHeadingRow, WithBatchInserts, WithChunkReading
{
    protected $importedCount = 0;
    protected $updatedCount = 0;
    protected $errorCount = 0;
    protected $errors = [];
    protected $skipFirstRow = true;

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
                
                // البحث عن مشترك موجود
                $existingSubscriber = $this->findExistingSubscriber($data);
                
                if ($existingSubscriber) {
                    // تحديث المشترك الموجود
                    $this->updateSubscriber($existingSubscriber, $data);
                    $this->updatedCount++;
                } else {
                    // إنشاء مشترك جديد
                    $this->createSubscriber($data);
                    $this->importedCount++;
                }
                
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
            'name' => trim($row['الاسم'] ?? $row['name'] ?? ''),
            'phone' => $this->cleanPhone($row['رقم_الجوال'] ?? $row['phone'] ?? ''),
            'email' => trim($row['البريد_الالكتروني'] ?? $row['email'] ?? ''),
            'city_name' => trim($row['المدينة'] ?? $row['city'] ?? ''),
            'nationality' => trim($row['الجنسية'] ?? $row['nationality'] ?? ''),
            'id_number' => trim($row['رقم_الهوية_الاقامة'] ?? $row['id_number'] ?? ''),
            'start_date' => $this->parseDate($row['تاريخ_البداية'] ?? $row['start_date'] ?? ''),
            'end_date' => $this->parseDate($row['تاريخ_النهاية'] ?? $row['end_date'] ?? ''),
            'package_name' => trim($row['الباقة'] ?? $row['package'] ?? ''),
            'card_price' => $this->parseDecimal($row['سعر_البطاقة'] ?? $row['card_price'] ?? 0),
            'total_amount' => $this->parseDecimal($row['المبلغ_الاجمالي'] ?? $row['total_amount'] ?? 0),
            'status' => trim($row['الحالة'] ?? $row['status'] ?? 'فعال'),
            'discount_percentage' => $this->parseDecimal($row['نسبة_الخصم'] ?? $row['discount_percentage'] ?? 0),
            'discount_amount' => $this->parseDecimal($row['مبلغ_الخصم'] ?? $row['discount_amount'] ?? 0),
            'notes' => trim($row['الملاحظات'] ?? $row['notes'] ?? ''),
            
            // بيانات التابعين
            'dependents_names' => trim($row['اسماء_التابعين'] ?? $row['dependents_names'] ?? ''),
            'dependents_nationalities' => trim($row['جنسيات_التابعين'] ?? $row['dependents_nationalities'] ?? ''),
            'dependents_id_numbers' => trim($row['ارقام_هوية_التابعين'] ?? $row['dependents_id_numbers'] ?? ''),
            'dependents_prices' => trim($row['اسعار_التابعين'] ?? $row['dependents_prices'] ?? ''),
        ];
    }

    protected function validateRow($data, $rowNumber)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'nationality' => 'required|string|max:255',
            'id_number' => 'required|string|max:20',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'card_price' => 'nullable|numeric|min:0',
            'total_amount' => 'nullable|numeric|min:0',
            'status' => 'required|in:فعال,منتهي,ملغي,معلق,بانتظار الدفع,في انتظار التحقق من الدفع,معلق - مشكلة في الدفع',
            'discount_percentage' => 'nullable|numeric|min:0|max:100',
            'discount_amount' => 'nullable|numeric|min:0',
        ];

        $messages = [
            'name.required' => 'اسم المشترك مطلوب',
            'phone.required' => 'رقم الجوال مطلوب',
            'email.email' => 'البريد الإلكتروني غير صحيح',
            'nationality.required' => 'الجنسية مطلوبة',
            'id_number.required' => 'رقم الهوية/الإقامة مطلوب',
            'start_date.required' => 'تاريخ البداية مطلوب',
            'start_date.date' => 'تاريخ البداية غير صحيح',
            'end_date.required' => 'تاريخ النهاية مطلوب',
            'end_date.date' => 'تاريخ النهاية غير صحيح',
            'end_date.after' => 'تاريخ النهاية يجب أن يكون بعد تاريخ البداية',
            'status.in' => 'حالة المشترك غير صحيحة',
        ];

        return Validator::make($data, $rules, $messages);
    }

    protected function findExistingSubscriber($data)
    {
        // البحث بالهاتف أو رقم الهوية
        return Subscriber::where('phone', $data['phone'])
                        ->orWhere('id_number', $data['id_number'])
                        ->first();
    }

    protected function createSubscriber($data)
    {
        // البحث عن المدينة
        $city = null;
        if (!empty($data['city_name'])) {
            $city = City::where('name', 'like', '%' . $data['city_name'] . '%')->first();
        }

        // البحث عن الباقة
        $package = null;
        if (!empty($data['package_name'])) {
            $package = Package::where('name', 'like', '%' . $data['package_name'] . '%')->first();
        }

        // توليد رقم البطاقة
        $cardNumber = Subscriber::generateCardNumber($data['id_number'], $data['phone']);
        while (Subscriber::where('card_number', $cardNumber)->exists()) {
            $cardNumber = Subscriber::generateCardNumber($data['id_number'], $data['phone']);
        }

        // إنشاء المشترك
        $subscriber = Subscriber::create([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'city_id' => $city ? $city->id : null,
            'nationality' => $data['nationality'],
            'id_number' => $data['id_number'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'card_number' => $cardNumber,
            'package_id' => $package ? $package->id : null,
            'card_price' => $data['card_price'],
            'total_amount' => $data['total_amount'],
            'status' => $data['status'],
            'discount_percentage' => $data['discount_percentage'],
            'discount_amount' => $data['discount_amount'],
            'notes' => $data['notes'],
            'created_by' => auth()->id(),
        ]);

        // إضافة التابعين
        $this->createDependents($subscriber, $data);

        return $subscriber;
    }

    protected function updateSubscriber($subscriber, $data)
    {
        // البحث عن المدينة
        $city = null;
        if (!empty($data['city_name'])) {
            $city = City::where('name', 'like', '%' . $data['city_name'] . '%')->first();
        }

        // البحث عن الباقة
        $package = null;
        if (!empty($data['package_name'])) {
            $package = Package::where('name', 'like', '%' . $data['package_name'] . '%')->first();
        }

        // تحديث بيانات المشترك
        $subscriber->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'city_id' => $city ? $city->id : $subscriber->city_id,
            'nationality' => $data['nationality'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'package_id' => $package ? $package->id : $subscriber->package_id,
            'card_price' => $data['card_price'],
            'total_amount' => $data['total_amount'],
            'status' => $data['status'],
            'discount_percentage' => $data['discount_percentage'],
            'discount_amount' => $data['discount_amount'],
            'notes' => $data['notes'] ?: $subscriber->notes,
        ]);

        // تحديث التابعين
        $this->updateDependents($subscriber, $data);

        return $subscriber;
    }

    protected function createDependents($subscriber, $data)
    {
        if (empty($data['dependents_names'])) {
            return;
        }

        $names = array_filter(explode(',', $data['dependents_names']));
        $nationalities = array_filter(explode(',', $data['dependents_nationalities']));
        $idNumbers = array_filter(explode(',', $data['dependents_id_numbers']));
        $prices = array_filter(explode(',', $data['dependents_prices']));

        foreach ($names as $index => $name) {
            if (trim($name)) {
                Dependent::create([
                    'subscriber_id' => $subscriber->id,
                    'name' => trim($name),
                    'nationality' => trim($nationalities[$index] ?? ''),
                    'id_number' => trim($idNumbers[$index] ?? ''),
                    'dependent_price' => $this->parseDecimal($prices[$index] ?? 0),
                ]);
            }
        }

        // تحديث عدد التابعين
        $subscriber->update(['dependents_count' => $subscriber->dependents()->count()]);
    }

    protected function updateDependents($subscriber, $data)
    {
        // حذف التابعين الحاليين
        $subscriber->dependents()->delete();
        
        // إضافة التابعين الجدد
        $this->createDependents($subscriber, $data);
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

    protected function parseDate($date)
    {
        if (empty($date)) return null;
        
        try {
            return \Carbon\Carbon::parse($date)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
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
