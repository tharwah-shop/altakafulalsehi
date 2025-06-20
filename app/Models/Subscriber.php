<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Subscriber extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'email',
        'city',
        'nationality',
        'id_number',
        'start_date',
        'end_date',
        'card_number',
        'package_id',
        'card_price',
        'total_amount',
        'dependents_count',
        'status',
        'discount_percentage',
        'discount_amount',
        'notes',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'card_price' => 'decimal:2',
            'total_amount' => 'decimal:2',
            'dependents_count' => 'integer',
            'discount_percentage' => 'decimal:2',
            'discount_amount' => 'decimal:2',
        ];
    }

    /**
     * العلاقات
     */

    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    public function dependents()
    {
        return $this->hasMany(Dependent::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'فعال');
    }

    public function scopeExpired($query)
    {
        return $query->where('status', 'منتهي')
                    ->orWhere('end_date', '<', now());
    }

    public function scopeByNationality($query, $nationality)
    {
        return $query->where('nationality', $nationality);
    }

    public function scopeByPackage($query, $packageId)
    {
        return $query->where('package_id', $packageId);
    }

    public function scopeByCity($query, $city)
    {
        return $query->where('city', $city);
    }

    /**
     * الحصول على خيارات الحالة
     */
    public static function getStatusOptions()
    {
        return [
            'بانتظار الدفع' => 'بانتظار الدفع',
            'في انتظار التحقق من الدفع' => 'في انتظار التحقق من الدفع',
            'فعال' => 'فعال',
            'معلق' => 'معلق',
            'معلق - مشكلة في الدفع' => 'معلق - مشكلة في الدفع',
            'منتهي' => 'منتهي',
            'ملغي' => 'ملغي'
        ];
    }

    /**
     * الحصول على لون الحالة
     */
    public function getStatusColorAttribute()
    {
        return match($this->status) {
            'بانتظار الدفع' => 'warning',
            'في انتظار التحقق من الدفع' => 'info',
            'فعال' => 'success',
            'معلق' => 'secondary',
            'معلق - مشكلة في الدفع' => 'danger',
            'منتهي' => 'dark',
            'ملغي' => 'danger',
            default => 'secondary'
        };
    }

    /**
     * Accessors
     */
    public function getIsActiveAttribute()
    {
        return $this->status === 'فعال' && $this->end_date >= now();
    }

    public function getIsExpiredAttribute()
    {
        return $this->end_date < now() || $this->status === 'منتهي';
    }

    public function getDaysRemainingAttribute()
    {
        if ($this->end_date < now()) {
            return 0;
        }
        return now()->diffInDays($this->end_date);
    }

    public function getFormattedPhoneAttribute()
    {
        // تنسيق رقم الجوال السعودي
        $phone = preg_replace('/\D/', '', $this->phone);
        if (strlen($phone) === 9 && substr($phone, 0, 1) === '5') {
            return '0' . $phone;
        }
        return $this->phone;
    }



    /**
     * Mutators
     */
    public function setPhoneAttribute($value)
    {
        // تنظيف رقم الجوال
        $phone = preg_replace('/\D/', '', $value);
        if (substr($phone, 0, 3) === '966') {
            $phone = substr($phone, 3);
        }
        if (substr($phone, 0, 1) === '0') {
            $phone = substr($phone, 1);
        }
        if (strlen($phone) === 9 && substr($phone, 0, 1) === '5') {
            $this->attributes['phone'] = '0' . $phone;
        } else {
            $this->attributes['phone'] = $value;
        }
    }

    /**
     * توليد رقم البطاقة
     */
    public static function generateCardNumber($idNumber, $phone)
    {
        // أول 3 أرقام من رقم الهوية
        $idPart = substr(preg_replace('/\D/', '', $idNumber), 0, 3);
        $idPart = str_pad($idPart, 3, '0', STR_PAD_LEFT);

        // آخر 3 أرقام من رقم الجوال
        $phonePart = substr(preg_replace('/\D/', '', $phone), -3);
        $phonePart = str_pad($phonePart, 3, '0', STR_PAD_LEFT);

        // 4 أرقام عشوائية
        $randomPart = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);

        return $idPart . $phonePart . $randomPart;
    }

    /**
     * تحديث حالة الاشتراك تلقائياً
     */
    public function updateStatus()
    {
        if ($this->end_date < now() && $this->status === 'فعال') {
            $this->update(['status' => 'منتهي']);
        }
    }
}
