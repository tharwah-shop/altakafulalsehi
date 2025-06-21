<?php

namespace App\Imports;

use App\Models\Subscriber;
use App\Models\Package;
use App\Helpers\SaudiCitiesHelper;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Carbon\Carbon;

class CustomSubscribersImport implements ToCollection, WithHeadingRow, WithBatchInserts, WithChunkReading
{
    protected $importedCount = 0;
    protected $updatedCount = 0;
    protected $errorCount = 0;
    protected $errors = [];
    protected $skipFirstRow = true;

    public function collection(Collection $rows)
    {
        $rowNumber = 1;
        
        foreach ($rows as $row) {
            $rowNumber++;
            
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
            'name' => trim($row['name'] ?? ''),
            'phone' => $this->cleanPhone($row['phone'] ?? ''),
            'email' => trim($row['email'] ?? ''),
            'nationality' => trim($row['nationality'] ?? ''),
            'id_number' => trim($row['id_number'] ?? ''),
            'start_date' => $this->parseDate($row['start_date'] ?? ''),
            'end_date' => $this->parseDate($row['end_date'] ?? ''),
            'card_price' => $this->parseDecimal($row['card_price'] ?? 0),
            'status' => trim($row['status'] ?? 'فعال'),
            'created_at' => $this->parseDate($row['created_at'] ?? ''),
        ];
    }

    /**
     * تحديد الباقة المناسبة بناءً على سعر البطاقة
     */
    protected function determinePackageByPrice($cardPrice)
    {
        // الباقات مرتبة حسب السعر
        $packagePrices = [
            149 => 'باقة الطلاب',
            199 => 'الباقة الأساسية', 
            349 => 'الباقة المميزة',
            499 => 'الباقة الذهبية',
            799 => 'باقة العائلة'
        ];

        // البحث عن أقرب سعر
        $closestPrice = null;
        $minDifference = PHP_INT_MAX;
        
        foreach ($packagePrices as $price => $packageName) {
            $difference = abs($cardPrice - $price);
            if ($difference < $minDifference) {
                $minDifference = $difference;
                $closestPrice = $price;
            }
        }

        return $closestPrice ? $packagePrices[$closestPrice] : 'الباقة الأساسية';
    }

    /**
     * اختيار مدينة عشوائية من المدن الرئيسية
     */
    protected function getRandomCity()
    {
        $majorCities = [
            'الرياض', 'جدة', 'مكة المكرمة', 'المدينة المنورة', 'الدمام', 
            'الخبر', 'تبوك', 'بريدة', 'خميس مشيط', 'حائل', 'أبها', 
            'الطائف', 'الأحساء', 'ينبع', 'نجران', 'جازان'
        ];
        
        return $majorCities[array_rand($majorCities)];
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
            'status' => 'required|in:فعال,منتهي,ملغي,معلق,بانتظار الدفع,في انتظار التحقق من الدفع,معلق - مشكلة في الدفع',
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
        // اختيار مدينة عشوائية
        $city = $this->getRandomCity();

        // تحديد الباقة بناءً على السعر
        $packageName = $this->determinePackageByPrice($data['card_price']);
        $package = Package::where('name', $packageName)->first();
        
        // إذا لم توجد باقة، استخدم الباقة الأساسية
        if (!$package) {
            $package = Package::where('name', 'الباقة الأساسية')->first();
        }

        // توليد رقم البطاقة
        $cardNumber = Subscriber::generateCardNumber($data['id_number'], $data['phone']);
        while (Subscriber::where('card_number', $cardNumber)->exists()) {
            $cardNumber = Subscriber::generateCardNumber($data['id_number'], $data['phone']);
        }

        // تحديد المبلغ الإجمالي
        $totalAmount = $data['card_price'];

        // إنشاء المشترك
        $subscriber = Subscriber::create([
            'name' => $data['name'],
            'phone' => $data['phone'],
            'email' => $data['email'],
            'city' => $city,
            'nationality' => $data['nationality'],
            'id_number' => $data['id_number'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'card_number' => $cardNumber,
            'package_id' => $package?->id,
            'card_price' => $data['card_price'],
            'total_amount' => $totalAmount,
            'dependents_count' => 0,
            'status' => $data['status'],
            'discount_percentage' => 0,
            'discount_amount' => 0,
            'notes' => null,
            'created_by' => auth()->id(),
        ]);

        return $subscriber;
    }

    protected function updateSubscriber($subscriber, $data)
    {
        // تحديد الباقة بناءً على السعر
        $packageName = $this->determinePackageByPrice($data['card_price']);
        $package = Package::where('name', $packageName)->first();

        // تحديد المبلغ الإجمالي
        $totalAmount = $data['card_price'];

        // تحديث بيانات المشترك
        $subscriber->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'nationality' => $data['nationality'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'],
            'package_id' => $package?->id,
            'card_price' => $data['card_price'],
            'total_amount' => $totalAmount,
            'status' => $data['status'],
        ]);

        return $subscriber;
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
            // التعامل مع التنسيق DD/MM/YYYY
            if (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/', $date, $matches)) {
                $day = str_pad($matches[1], 2, '0', STR_PAD_LEFT);
                $month = str_pad($matches[2], 2, '0', STR_PAD_LEFT);
                $year = $matches[3];
                return Carbon::createFromFormat('d/m/Y', "$day/$month/$year")->format('Y-m-d');
            }
            
            return Carbon::parse($date)->format('Y-m-d');
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
