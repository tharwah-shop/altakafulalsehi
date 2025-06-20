<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Package extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'name_en',
        'description',
        'description_en',
        'price',
        'dependent_price',
        'duration_months',
        'max_dependents',
        'features',
        'discount_percentage',
        'status',
        'is_featured',
        'sort_order',
        'color',
        'icon',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'dependent_price' => 'decimal:2',
            'discount_percentage' => 'decimal:2',
            'features' => 'array',
            'is_featured' => 'boolean',
            'duration_months' => 'integer',
            'max_dependents' => 'integer',
            'sort_order' => 'integer',
        ];
    }

    /**
     * العلاقات
     */
    public function subscribers()
    {
        return $this->hasMany(Subscriber::class);
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Accessors
     */
    public function getFormattedPriceAttribute()
    {
        return number_format($this->price, 0) . ' ريال';
    }

    public function getFormattedDependentPriceAttribute()
    {
        return $this->dependent_price ? number_format($this->dependent_price, 0) . ' ريال' : 'غير متاح';
    }

    public function getFormattedPriceWithDecimalsAttribute()
    {
        return number_format($this->price, 2) . ' ريال';
    }

    public function getFormattedDependentPriceWithDecimalsAttribute()
    {
        return $this->dependent_price ? number_format($this->dependent_price, 2) . ' ريال' : 'غير متاح';
    }

    public function getDurationTextAttribute()
    {
        if ($this->duration_months == 1) {
            return 'شهر واحد';
        } elseif ($this->duration_months == 12) {
            return 'سنة واحدة';
        } elseif ($this->duration_months < 12) {
            return $this->duration_months . ' أشهر';
        } else {
            $years = floor($this->duration_months / 12);
            $months = $this->duration_months % 12;
            $text = $years . ' سنة';
            if ($months > 0) {
                $text .= ' و ' . $months . ' أشهر';
            }
            return $text;
        }
    }

    /**
     * حساب السعر الإجمالي مع التابعين
     */
    public function calculateTotalPrice($dependentsCount = 0)
    {
        $totalPrice = $this->price;

        if ($dependentsCount > 0 && $this->dependent_price) {
            // التحقق من الحد الأقصى للتابعين
            if ($this->max_dependents > 0 && $dependentsCount > $this->max_dependents) {
                $dependentsCount = $this->max_dependents;
            }

            $totalPrice += ($dependentsCount * $this->dependent_price);
        }

        return $totalPrice;
    }

    /**
     * تنسيق السعر الإجمالي مع التابعين
     */
    public function getFormattedTotalPrice($dependentsCount = 0)
    {
        return number_format($this->calculateTotalPrice($dependentsCount), 0) . ' ريال';
    }

    /**
     * التحقق من إمكانية إضافة تابعين
     */
    public function supportsDependents()
    {
        return $this->dependent_price > 0;
    }

    /**
     * التحقق من وجود حد أقصى للتابعين
     */
    public function hasMaxDependentsLimit()
    {
        return $this->max_dependents > 0;
    }

    /**
     * الحصول على نص وصفي لحدود التابعين
     */
    public function getDependentsLimitTextAttribute()
    {
        if (!$this->supportsDependents()) {
            return 'لا يدعم تابعين';
        }

        if ($this->max_dependents == 0) {
            return 'تابعين غير محدود';
        }

        return 'حتى ' . $this->max_dependents . ' تابعين';
    }
}
