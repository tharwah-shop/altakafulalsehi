<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MedicalCenterReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'medical_center_id',
        'reviewer_name',
        'reviewer_email',
        'rating',
        'comment',
        'status',
        'ip_address',
    ];

    protected function casts(): array
    {
        return [
            'rating' => 'integer',
        ];
    }

    /**
     * العلاقات
     */
    public function medicalCenter()
    {
        return $this->belongsTo(MedicalCenter::class);
    }

    /**
     * Scopes
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        static::created(function ($review) {
            $review->medicalCenter->updateRating();
        });

        static::updated(function ($review) {
            $review->medicalCenter->updateRating();
        });

        static::deleted(function ($review) {
            $review->medicalCenter->updateRating();
        });
    }
}
