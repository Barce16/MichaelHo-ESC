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

    /**
     * Get total paid for PACKAGE (excludes expense payments)
     */
    public function getTotalPaidAttribute()
    {
        return $this->payments()
            ->where('status', Payment::STATUS_APPROVED)
            ->where('payment_type', '!=', Payment::TYPE_EXPENSE)
            ->sum('amount');
    }

    /**
     * Get remaining balance for PACKAGE only (excludes expenses)
     */
    public function getRemainingBalanceAttribute()
    {
        return max(0, $this->total_amount - $this->total_paid);
    }

    /**
     * Check if package is fully paid (excludes expenses)
     */
    public function isFullyPaid()
    {
        return $this->total_paid >= $this->total_amount;
    }

    // ========== EXPENSE-RELATED CALCULATIONS ==========

    /**
     * Get total expenses for this event
     */
    public function getExpensesTotalAttribute(): float
    {
        return (float) $this->event->expenses()->sum('amount');
    }

    /**
     * Get unpaid expenses total
     */
    public function getUnpaidExpensesTotalAttribute(): float
    {
        return (float) $this->event->expenses()->unpaid()->sum('amount');
    }

    /**
     * Get paid expenses total
     */
    public function getPaidExpensesTotalAttribute(): float
    {
        return (float) $this->event->expenses()->paid()->sum('amount');
    }

    /**
     * Get grand total (package + all expenses)
     */
    public function getGrandTotalAttribute(): float
    {
        return (float) $this->total_amount + $this->expenses_total;
    }

    /**
     * Get total amount paid (package + expense payments)
     */
    public function getGrandTotalPaidAttribute(): float
    {
        return $this->payments()
            ->where('status', Payment::STATUS_APPROVED)
            ->sum('amount');
    }

    /**
     * Get overall remaining balance (package balance + unpaid expenses)
     */
    public function getOverallRemainingBalanceAttribute(): float
    {
        return $this->remaining_balance + $this->unpaid_expenses_total;
    }

    /**
     * Check if everything is fully paid (package + all expenses)
     */
    public function isEverythingPaid(): bool
    {
        return $this->isFullyPaid() && $this->unpaid_expenses_total <= 0;
    }

    /**
     * Get count of unpaid expenses
     */
    public function getUnpaidExpensesCountAttribute(): int
    {
        return $this->event->expenses()->unpaid()->count();
    }

    /**
     * Get count of paid expenses
     */
    public function getPaidExpensesCountAttribute(): int
    {
        return $this->event->expenses()->paid()->count();
    }
}
