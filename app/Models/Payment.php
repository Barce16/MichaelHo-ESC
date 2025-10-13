<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    // Payment Type Constants
    const TYPE_INTRODUCTORY = 'introductory';
    const TYPE_DOWNPAYMENT = 'downpayment';
    const TYPE_BALANCE = 'balance';

    // Status Constants
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'event_id',
        'billing_id',
        'payment_type',
        'amount',
        'payment_method',
        'payment_date',
        'payment_image',
        'status',
        'rejection_reason',
        'notes',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'datetime',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function billing()
    {
        return $this->belongsTo(Billing::class);
    }

    // Type Check Methods
    public function isIntroductory(): bool
    {
        return $this->payment_type === self::TYPE_INTRODUCTORY;
    }

    public function isDownpayment(): bool
    {
        return $this->payment_type === self::TYPE_DOWNPAYMENT;
    }

    public function isBalance(): bool
    {
        return $this->payment_type === self::TYPE_BALANCE;
    }

    // Status Check Methods
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    // Get payment type label
    public function getTypeLabel(): string
    {
        return match ($this->payment_type) {
            self::TYPE_INTRODUCTORY => 'Introductory Payment',
            self::TYPE_DOWNPAYMENT => 'Downpayment',
            self::TYPE_BALANCE => 'Balance Payment',
            default => ucfirst($this->payment_type),
        };
    }

    // Get payment image URL
    public function getImageUrlAttribute(): ?string
    {
        return $this->payment_image ? asset('storage/' . $this->payment_image) : null;
    }

    // Scopes
    public function scopeIntroductory($query)
    {
        return $query->where('payment_type', self::TYPE_INTRODUCTORY);
    }

    public function scopeDownpayment($query)
    {
        return $query->where('payment_type', self::TYPE_DOWNPAYMENT);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }
}
