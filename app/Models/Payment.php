<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'subscriber_id',
        'amount',
        'payment_method',
        'status',
        'currency',
        'transfer_amount',
        'sender_name',
        'bank_name',
        'receipt_file',
        'notes',
        'transfer_confirmed_at',
        'verified_at',
        'verified_by'
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'transfer_amount' => 'decimal:2',
            'transfer_confirmed_at' => 'datetime',
            'verified_at' => 'datetime',
        ];
    }

    /**
     * العلاقات
     */
    public function subscriber()
    {
        return $this->belongsTo(Subscriber::class);
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }

    /**
     * Scopes
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopePendingVerification($query)
    {
        return $query->where('status', 'pending_verification');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeBankTransfer($query)
    {
        return $query->where('payment_method', 'bank_transfer');
    }

    /**
     * Accessors
     */
    public function getFormattedAmountAttribute()
    {
        return number_format($this->amount, 2) . ' ' . $this->currency;
    }

    public function getStatusTextAttribute()
    {
        return match($this->status) {
            'pending' => 'في انتظار الدفع',
            'pending_verification' => 'في انتظار التحقق',
            'completed' => 'مكتمل',
            'failed' => 'فشل',
            'cancelled' => 'ملغي',
            default => $this->status
        };
    }

    public function getPaymentMethodTextAttribute()
    {
        return match($this->payment_method) {
            'bank_transfer' => 'تحويل بنكي',
            'myfatoorah' => 'MyFatoorah',
            'tabby' => 'تابي',
            'credit_card' => 'بطاقة ائتمان',
            default => $this->payment_method
        };
    }

    /**
     * التحقق من إمكانية التحقق من الدفع
     */
    public function canBeVerified()
    {
        return $this->status === 'pending_verification' && 
               $this->payment_method === 'bank_transfer' && 
               !empty($this->receipt_file);
    }

    /**
     * تأكيد الدفع
     */
    public function markAsCompleted($verifiedBy = null)
    {
        $this->update([
            'status' => 'completed',
            'verified_at' => now(),
            'verified_by' => $verifiedBy
        ]);

        // تحديث حالة المشترك
        if ($this->subscriber) {
            $this->subscriber->update([
                'status' => 'فعال'
            ]);
        }
    }

    /**
     * رفض الدفع
     */
    public function markAsFailed($reason = null)
    {
        $this->update([
            'status' => 'failed',
            'notes' => $this->notes . "\n" . "سبب الرفض: " . $reason
        ]);

        // تحديث حالة المشترك
        if ($this->subscriber) {
            $this->subscriber->update([
                'status' => 'معلق - مشكلة في الدفع'
            ]);
        }
    }
}
