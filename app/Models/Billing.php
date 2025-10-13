<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'total_amount',
        'downpayment_amount',
        'introductory_payment_amount',
        'introductory_payment_status',
        'introductory_paid_at',
        'status',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'downpayment_amount' => 'decimal:2',
        'introductory_payment_amount' => 'decimal:2',
        'introductory_paid_at' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class)->latest();
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    // Get introductory payment
    public function introPayment()
    {
        return $this->hasOne(Payment::class)->where('payment_type', 'introductory');
    }

    // Get downpayment
    public function downPayment()
    {
        return $this->hasOne(Payment::class)->where('payment_type', 'downpayment');
    }

    // Calculate remaining balance after intro payment
    public function getRemainingAfterIntroAttribute(): float
    {
        $intro = $this->introductory_payment_status === 'paid'
            ? $this->introductory_payment_amount
            : 0;

        return max(0, $this->total_amount - $intro);
    }

    // Check if introductory payment is paid
    public function hasIntroPaid(): bool
    {
        return $this->introductory_payment_status === 'paid';
    }

    // Check if downpayment is set
    public function hasDownpaymentSet(): bool
    {
        return $this->downpayment_amount > 0;
    }

    // Mark intro payment as paid
    public function markIntroPaid(): void
    {
        $this->update([
            'introductory_payment_status' => 'paid',
            'introductory_paid_at' => now(),
        ]);
    }

    public function getTotalPaidAttribute()
    {
        return $this->payments()
            ->where('status', Payment::STATUS_APPROVED)
            ->sum('amount');
    }

    public function getRemainingBalanceAttribute()
    {
        return max(0, $this->total_amount - $this->total_paid);
    }

    public function isFullyPaid()
    {
        return $this->total_paid >= $this->total_amount;
    }
}
