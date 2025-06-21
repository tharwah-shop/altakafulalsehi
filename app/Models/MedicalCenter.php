<?php

namespace App\Models;

use App\Helpers\ImageHelper;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str;

class MedicalCenter extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'region',
        'city',
        'address',
        'latitude',
        'longitude',
        'phone',
        'email',
        'website',
        'type',
        'medical_service_types',
        'medical_discounts',
        'status',
        'contract_status',
        'contract_start_date',
        'contract_end_date',
        'image',
        'location',
        'rating',
        'reviews_count',
        'views_count',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'medical_service_types' => 'array',
            'medical_discounts' => 'array',
            'contract_start_date' => 'date',
            'contract_end_date' => 'date',
            'rating' => 'decimal:2',
            'latitude' => 'decimal:8',
            'longitude' => 'decimal:8',
        ];
    }

    /**
     * العلاقات
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function reviews()
    {
        return $this->hasMany(MedicalCenterReview::class);
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeByRegion($query, $region)
    {
        return $query->where('region', $region);
    }

    public function scopeByCity($query, $city)
    {
        return $query->where('city', $city);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Mutators
     */
    public function setNameAttribute($value)
    {
        $this->attributes['name'] = $value;
        if (empty($this->attributes['slug'])) {
            $this->attributes['slug'] = Str::slug($value);
        }
    }

    /**
     * Accessors
     */
    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    /**
     * Helper Methods
     */
    public function updateRating()
    {
        $avgRating = $this->reviews()->where('status', 'approved')->avg('rating');
        $reviewsCount = $this->reviews()->where('status', 'approved')->count();

        $this->update([
            'rating' => $avgRating ?? 0,
            'reviews_count' => $reviewsCount,
        ]);
    }

    public function incrementViews()
    {
        $this->increment('views_count');
    }

    public function getTypeNameAttribute()
    {
        $types = [
            1 => 'مستشفى عام',
            2 => 'عيادة تخصصية',
            3 => 'مركز طبي',
            4 => 'مختبر طبي',
            5 => 'مركز أشعة',
            6 => 'مجمع أسنان',
            7 => 'مركز عيون',
            8 => 'بصريات',
            9 => 'صيدلية',
            10 => 'مركز حجامة',
            11 => 'مركز تجميل',
            12 => 'مركز ليزر'
        ];

        return $types[$this->type] ?? 'غير محدد';
    }



    public function getContractStatusNameAttribute()
    {
        $statuses = [
            'active' => 'نشط',
            'pending' => 'قيد المراجعة',
            'expired' => 'منتهي',
            'suspended' => 'معلق',
            'terminated' => 'ملغي'
        ];

        return $statuses[$this->contract_status] ?? 'غير محدد';
    }

    public function getStatusNameAttribute()
    {
        $statuses = [
            'active' => 'نشط',
            'inactive' => 'غير نشط',
            'pending' => 'في انتظار المراجعة',
            'suspended' => 'معلق'
        ];

        return $statuses[$this->status] ?? 'غير محدد';
    }

    public function getImageUrlAttribute()
    {
        if (!$this->image) {
            return null;
        }

        // التحقق من وجود الصورة أولاً
        if (!ImageHelper::exists($this->image)) {
            return null;
        }

        // استخدام ImageHelper أولاً
        $url = ImageHelper::getUrl($this->image);

        // إذا لم يعمل ImageHelper، استخدم الطريقة التقليدية
        if (!$url) {
            $url = asset("storage/{$this->image}");
        }

        return $url;
    }

    public function getImagePathAttribute()
    {
        return $this->image ? asset("storage/{$this->image}") : null;
    }

    /**
     * الحصول على رابط الصورة المصغرة
     */
    public function getThumbnailUrlAttribute()
    {
        if (!$this->image) {
            return null;
        }

        return ImageHelper::getThumbnailUrl($this->image);
    }

    /**
     * الحصول على معلومات الصورة
     */
    public function getImageInfoAttribute()
    {
        if (!$this->image) {
            return null;
        }

        return ImageHelper::getImageInfo($this->image);
    }
}
