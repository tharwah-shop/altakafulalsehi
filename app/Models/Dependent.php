<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dependent extends Model
{
    use HasFactory;

    protected $fillable = [
        'subscriber_id',
        'name',
        'nationality',
        'id_number',
        'dependent_price',
        'notes',
    ];

    protected function casts(): array
    {
        return [
            'dependent_price' => 'decimal:2',
        ];
    }

    /**
     * العلاقات
     */
    public function subscriber()
    {
        return $this->belongsTo(Subscriber::class);
    }

    /**
     * Scopes
     */
    public function scopeByNationality($query, $nationality)
    {
        return $query->where('nationality', $nationality);
    }

    public function scopeBySubscriber($query, $subscriberId)
    {
        return $query->where('subscriber_id', $subscriberId);
    }

    /**
     * Accessors
     */
    public function getFormattedPriceAttribute()
    {
        return $this->dependent_price ? number_format($this->dependent_price, 2) . ' ريال' : 'غير محدد';
    }
}
