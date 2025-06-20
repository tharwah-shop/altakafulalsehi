<?php

namespace App\Models;

use App\Helpers\ImageHelper;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Offer extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'medical_center_id',
        'discount_percentage',
        'discount_amount',
        'original_price',
        'discounted_price',
        'start_date',
        'end_date',
        'status',
        'image',
        'terms_conditions',
        'max_uses',
        'current_uses',
        'is_featured',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'is_featured' => 'boolean',
            'original_price' => 'decimal:2',
            'discounted_price' => 'decimal:2',
            'discount_percentage' => 'decimal:2',
            'discount_amount' => 'decimal:2',
        ];
    }

    // العلاقات
    public function medicalCenter(): BelongsTo
    {
        return $this->belongsTo(MedicalCenter::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Accessors
    public function getStatusNameAttribute()
    {
        $statuses = [
            'active' => 'نشط',
            'inactive' => 'غير نشط',
            'expired' => 'منتهي',
            'pending' => 'في انتظار المراجعة',
        ];

        return $statuses[$this->status] ?? 'غير محدد';
    }

    public function getImageUrlAttribute()
    {
        return ImageHelper::getUrl($this->image);
    }

    public function getImagePathAttribute()
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

    public function getIsActiveAttribute()
    {
        return $this->status === 'active' && 
               $this->start_date <= now() && 
               $this->end_date >= now();
    }

    public function getIsExpiredAttribute()
    {
        return $this->end_date < now();
    }

    public function getRemainingDaysAttribute()
    {
        if ($this->is_expired) {
            return 0;
        }

        return now()->diffInDays($this->end_date);
    }

    public function getUsagePercentageAttribute()
    {
        if ($this->max_uses <= 0) {
            return 0;
        }

        return ($this->current_uses / $this->max_uses) * 100;
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
                    ->where('start_date', '<=', now())
                    ->where('end_date', '>=', now());
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeExpired($query)
    {
        return $query->where('end_date', '<', now());
    }

    // Methods
    public function canBeUsed(): bool
    {
        return $this->is_active && 
               ($this->max_uses <= 0 || $this->current_uses < $this->max_uses);
    }

    public function incrementUsage(): bool
    {
        if (!$this->canBeUsed()) {
            return false;
        }

        $this->increment('current_uses');
        return true;
    }
}
