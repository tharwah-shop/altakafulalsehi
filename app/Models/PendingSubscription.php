<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PendingSubscription extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'name',
        'phone',
        'email',
        'city',
        'nationality',
        'id_number',
        'package_id',
        'payment_method',
        'dependents',
        'total_amount',
        'dependents_count',
        'status',
        'expires_at'
    ];

    protected function casts(): array
    {
        return [
            'dependents' => 'array',
            'total_amount' => 'decimal:2',
            'expires_at' => 'datetime',
        ];
    }

    /**
     * العلاقات
     */
    // تم إزالة علاقة المدينة لأن النظام يستخدم الآن نظام المدن القائم على الملفات
    // public function city()
    // {
    //     return $this->belongsTo(City::class);
    // }

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'pending')
                    ->where('expires_at', '>', now());
    }

    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<=', now());
    }

    /**
     * تحويل البيانات المؤقتة إلى مشترك فعلي
     */
    public function convertToSubscriber()
    {
        try {
            // التحقق من صحة البيانات المؤقتة
            if ($this->status !== 'pending') {
                throw new \Exception('البيانات المؤقتة ليست في حالة انتظار');
            }

            if ($this->expires_at < now()) {
                throw new \Exception('انتهت صلاحية البيانات المؤقتة');
            }

            $package = $this->package;
            if (!$package) {
                throw new \Exception('لم يتم العثور على الباقة المرتبطة');
            }

            // إنشاء رقم البطاقة مع التحقق من عدم التكرار
            $cardNumber = $this->generateUniqueCardNumber();

        // إنشاء المشترك
        $subscriber = Subscriber::create([
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'city' => $this->city,
            'nationality' => $this->nationality,
            'id_number' => $this->id_number,
            'package_id' => $this->package_id,
            'card_number' => $cardNumber,
            'start_date' => now(),
            'end_date' => now()->addMonths($package->duration_months),
            'status' => 'بانتظار الدفع',
            'total_amount' => $this->total_amount,
            'dependents_count' => $this->dependents_count,
        ]);

        // إضافة التابعين
        if ($this->dependents && is_array($this->dependents)) {
            foreach ($this->dependents as $dependentData) {
                if (!empty($dependentData['name'])) {
                    $subscriber->dependents()->create([
                        'name' => $dependentData['name'],
                        'nationality' => $dependentData['nationality'],
                        'id_number' => $dependentData['id_number'] ?? null,
                        'dependent_price' => $package->dependent_price,
                        'notes' => 'العلاقة: ' . ($dependentData['relationship'] ?? ''),
                    ]);
                }
            }
        }

            // تحديث حالة البيانات المؤقتة
            $this->update(['status' => 'completed']);

            return $subscriber;

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error converting pending subscription to subscriber', [
                'pending_subscription_id' => $this->id,
                'error_message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            throw new \Exception('فشل في تحويل البيانات المؤقتة إلى مشترك: ' . $e->getMessage());
        }
    }

    /**
     * إنشاء رقم البطاقة
     */
    private function generateCardNumber()
    {
        // التحقق من وجود رقم الهوية والجوال
        if (empty($this->id_number) || empty($this->phone)) {
            throw new \Exception('رقم الهوية أو الجوال مفقود لإنشاء رقم البطاقة');
        }

        // استخراج أجزاء رقم البطاقة
        $idPrefix = substr(preg_replace('/\D/', '', $this->id_number), 0, 3);
        $phoneSuffix = substr(preg_replace('/\D/', '', $this->phone), -3);
        $randomNumber = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);

        // التأكد من أن الأجزاء صحيحة
        if (strlen($idPrefix) < 3) {
            $idPrefix = str_pad($idPrefix, 3, '0', STR_PAD_LEFT);
        }
        if (strlen($phoneSuffix) < 3) {
            $phoneSuffix = str_pad($phoneSuffix, 3, '0', STR_PAD_LEFT);
        }

        return "{$idPrefix}{$phoneSuffix}{$randomNumber}";
    }

    /**
     * إنشاء رقم بطاقة فريد
     */
    private function generateUniqueCardNumber()
    {
        $maxAttempts = 10;
        $attempts = 0;

        do {
            $cardNumber = $this->generateCardNumber();
            $exists = \App\Models\Subscriber::where('card_number', $cardNumber)->exists();
            $attempts++;

            if ($attempts >= $maxAttempts) {
                throw new \Exception("فشل في إنشاء رقم بطاقة فريد بعد {$maxAttempts} محاولات");
            }
        } while ($exists);

        return $cardNumber;
    }

    /**
     * تنظيف البيانات المنتهية الصلاحية
     */
    public static function cleanupExpired()
    {
        return static::expired()->delete();
    }
}
