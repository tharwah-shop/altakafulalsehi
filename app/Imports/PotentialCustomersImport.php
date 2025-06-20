<?php

namespace App\Imports;

use App\Models\PotentialCustomer;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Illuminate\Validation\Rule;
use Jenssegers\Agent\Agent;

class PotentialCustomersImport implements ToModel, WithHeadingRow, WithValidation, WithBatchInserts, WithChunkReading
{
    use Importable;

    private $errors = [];
    private $successCount = 0;
    private $skipCount = 0;

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        try {
            // تنظيف البيانات
            $name = trim($row['name'] ?? $row['الاسم'] ?? '');
            $email = trim($row['email'] ?? $row['البريد_الالكتروني'] ?? '');
            $phone = trim($row['phone'] ?? $row['رقم_الجوال'] ?? '');
            $city = trim($row['city'] ?? $row['المدينة'] ?? '');
            $status = trim($row['status'] ?? $row['الحالة'] ?? 'لم يتم التواصل');
            $source = trim($row['source'] ?? $row['المصدر'] ?? '');
            $deviceType = trim($row['device_type'] ?? $row['نوع_الجهاز'] ?? '');
            $ipAddress = trim($row['ip_address'] ?? $row['عنوان_ip'] ?? '');
            $userAgent = trim($row['user_agent'] ?? $row['user_agent'] ?? '');
            $referrerUrl = trim($row['referrer_url'] ?? $row['رابط_الاحالة'] ?? '');
            $callSummary = trim($row['call_summary'] ?? $row['ملخص_المكالمة'] ?? '');
            $requestDate = trim($row['request_date'] ?? $row['تاريخ_الطلب'] ?? $row['created_at'] ?? '');

            // التحقق من البيانات الأساسية
            if (empty($name) || empty($phone)) {
                $this->skipCount++;
                return null;
            }

            // التحقق من عدم تكرار رقم الجوال
            if (PotentialCustomer::where('phone', $phone)->exists()) {
                $this->skipCount++;
                return null;
            }

            // تحديد نوع الجهاز تلقائياً إذا لم يكن محدداً
            if (empty($deviceType) && !empty($userAgent)) {
                $deviceType = $this->detectDeviceType($userAgent);
            }

            // تطبيع حالة العميل
            $status = $this->normalizeStatus($status);

            // تطبيع مصدر العميل
            $source = $this->normalizeSource($source);

            // معالجة تاريخ الطلب
            $createdAt = null;
            if (!empty($requestDate)) {
                try {
                    $createdAt = \Carbon\Carbon::parse($requestDate);
                } catch (\Exception $e) {
                    // إذا فشل تحليل التاريخ، استخدم التاريخ الحالي
                    $createdAt = now();
                }
            }

            $this->successCount++;

            $customer = new PotentialCustomer([
                'name' => $name,
                'email' => !empty($email) ? $email : null,
                'phone' => $phone,
                'city' => $city,
                'status' => $status,
                'source' => $source,
                'device_type' => $deviceType,
                'ip_address' => !empty($ipAddress) ? $ipAddress : null,
                'user_agent' => !empty($userAgent) ? $userAgent : null,
                'referrer_url' => !empty($referrerUrl) ? $referrerUrl : null,
                'call_summary' => !empty($callSummary) ? $callSummary : null,
            ]);

            // تعيين تاريخ الإنشاء إذا كان محدداً
            if ($createdAt) {
                $customer->created_at = $createdAt;
                $customer->updated_at = $createdAt;
            }

            return $customer;

        } catch (\Exception $e) {
            $this->errors[] = "خطأ في الصف: " . $e->getMessage();
            $this->skipCount++;
            return null;
        }
    }

    /**
     * @return array
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'الاسم' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'رقم_الجوال' => 'required|string|max:20',
            'email' => 'nullable|email|max:255',
            'البريد_الالكتروني' => 'nullable|email|max:255',
            'city' => 'required|string|max:255',
            'المدينة' => 'required|string|max:255',
            'request_date' => 'nullable|date',
            'تاريخ_الطلب' => 'nullable|date',
            'created_at' => 'nullable|date',
        ];
    }

    /**
     * @return int
     */
    public function batchSize(): int
    {
        return 100;
    }

    /**
     * @return int
     */
    public function chunkSize(): int
    {
        return 100;
    }

    /**
     * تحديد نوع الجهاز من user agent
     */
    private function detectDeviceType($userAgent)
    {
        if (empty($userAgent)) {
            return 'desktop';
        }

        $agent = new Agent();
        $agent->setUserAgent($userAgent);

        if ($agent->isMobile()) {
            return 'mobile';
        } elseif ($agent->isTablet()) {
            return 'tablet';
        }

        return 'desktop';
    }

    /**
     * تطبيع حالة العميل
     */
    private function normalizeStatus($status)
    {
        $statusMap = [
            'لم يتم التواصل' => 'لم يتم التواصل',
            'لم يرد' => 'لم يرد',
            'رفض' => 'رفض',
            'تأجيل' => 'تأجيل',
            'تم الاصدار' => 'تم الاصدار',
            'تم التواصل' => 'تم التواصل',
            'pending' => 'لم يتم التواصل',
            'contacted' => 'تم التواصل',
            'rejected' => 'رفض',
            'issued' => 'تم الاصدار',
            'postponed' => 'تأجيل',
            'no_answer' => 'لم يرد',
        ];

        return $statusMap[strtolower($status)] ?? 'لم يتم التواصل';
    }

    /**
     * تطبيع مصدر العميل
     */
    private function normalizeSource($source)
    {
        $sourceMap = [
            'google_ads' => 'google_ads',
            'facebook_ads' => 'facebook_ads',
            'direct' => 'direct',
            'organic' => 'organic',
            'referral' => 'referral',
            'social' => 'social',
            'card_request' => 'card_request',
            'إعلانات جوجل' => 'google_ads',
            'إعلانات فيسبوك' => 'facebook_ads',
            'دخول مباشر' => 'direct',
            'بحث طبيعي' => 'organic',
            'إحالة' => 'referral',
            'وسائل التواصل' => 'social',
            'طلب بطاقة' => 'card_request',
        ];

        return $sourceMap[strtolower($source)] ?? null;
    }

    /**
     * الحصول على الأخطاء
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * الحصول على عدد السجلات المستوردة بنجاح
     */
    public function getSuccessCount()
    {
        return $this->successCount;
    }

    /**
     * الحصول على عدد السجلات المتجاهلة
     */
    public function getSkipCount()
    {
        return $this->skipCount;
    }

    /**
     * إعادة تعيين العدادات
     */
    public function resetCounters()
    {
        $this->errors = [];
        $this->successCount = 0;
        $this->skipCount = 0;
    }
}
