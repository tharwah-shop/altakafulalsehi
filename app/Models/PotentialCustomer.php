<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PotentialCustomer extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'city',
        'status',
        'source',
        'device_type',
        'ip_address',
        'user_agent',
        'call_summary',
        'user_id',
        'converted_to_subscriber',
        'referrer_url',
        'landing_page',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_term',
        'utm_content'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the city name for display.
     */
    public function getCityNameAttribute()
    {
        return $this->city ?? '-';
    }

    /**
     * Get the user (customer service representative) that owns the potential customer.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the display name for the source.
     */
    public function getSourceDisplayAttribute()
    {
        $sources = [
            'google_ads' => 'إعلانات جوجل',
            'facebook_ads' => 'إعلانات فيسبوك',
            'direct' => 'دخول مباشر',
            'organic' => 'بحث طبيعي',
            'referral' => 'إحالة',
            'social' => 'وسائل التواصل'
        ];

        return $sources[$this->source] ?? $this->source;
    }

    /**
     * Get the display name for the device type.
     */
    public function getDeviceTypeDisplayAttribute()
    {
        $deviceTypes = [
            'mobile' => 'جوال',
            'desktop' => 'كمبيوتر',
            'tablet' => 'تابلت'
        ];

        return $deviceTypes[$this->device_type] ?? $this->device_type;
    }

    /**
     * Get the display name for the status.
     */
    public function getStatusDisplayAttribute()
    {
        $statuses = [
            'لم يتم التواصل' => 'لم يتم التواصل',
            'لم يرد' => 'لم يرد',
            'رفض' => 'رفض',
            'تأجيل' => 'تأجيل',
            'تم الاصدار' => 'تم الاصدار'
        ];

        return $statuses[$this->status] ?? $this->status;
    }

    /**
     * Scope a query to only include customers from today.
     */
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    /**
     * Scope a query to only include customers from this week.
     */
    public function scopeThisWeek($query)
    {
        return $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]);
    }

    /**
     * Scope a query to only include customers from this month.
     */
    public function scopeThisMonth($query)
    {
        return $query->whereMonth('created_at', now()->month)
                    ->whereYear('created_at', now()->year);
    }

    /**
     * Scope a query to only include pending customers.
     */
    public function scopePending($query)
    {
        return $query->where('status', 'لم يتم التواصل');
    }

    /**
     * Scope a query to only include contacted customers.
     */
    public function scopeContacted($query)
    {
        return $query->whereIn('status', ['لم يرد', 'تأجيل']);
    }

    /**
     * Scope a query to only include issued customers.
     */
    public function scopeIssued($query)
    {
        return $query->where('status', 'تم الاصدار');
    }

    /**
     * Scope a query to only include rejected customers.
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'رفض');
    }

    /**
     * Scope a query to only include converted customers.
     */
    public function scopeConverted($query)
    {
        return $query->whereNotNull('converted_to_subscriber');
    }

    /**
     * Scope a query to search by name, email, or phone.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('phone', 'like', "%{$search}%");
        });
    }
}
