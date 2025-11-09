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

    // Payment Method Constants
    const METHOD_BANK_TRANSFER = 'bank_transfer';
    const METHOD_GCASH = 'gcash';
    const METHOD_BPI = 'bpi';
    const METHOD_CASH = 'cash';

    // Status Constants
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    // Receipt Request Status Constants
    const RECEIPT_NOT_REQUESTED = 0;
    const RECEIPT_REQUESTED = 1;
    const RECEIPT_CREATED = 2;

    protected $fillable = [
        'event_id',
        'billing_id',
        'payment_type',
        'amount',
        'payment_method',
        'reference_number',
        'payment_date',
        'payment_image',
        'status',
        'rejection_reason',
        'notes',
        'receipt_request',
        'receipt_requested_at',
        'receipt_created_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'datetime',
        'receipt_request' => 'integer',
        'receipt_requested_at' => 'datetime',
        'receipt_created_at' => 'datetime',
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

    // Payment Method Check Methods
    public function isBankTransfer(): bool
    {
        return $this->payment_method === self::METHOD_BANK_TRANSFER;
    }

    public function isGcash(): bool
    {
        return $this->payment_method === self::METHOD_GCASH;
    }

    public function isBPI(): bool
    {
        return $this->payment_method === self::METHOD_BPI;
    }

    public function isCash(): bool
    {
        return $this->payment_method === self::METHOD_CASH;
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

    // Get payment method label
    public function getMethodLabel(): string
    {
        return match ($this->payment_method) {
            self::METHOD_BANK_TRANSFER => 'Bank Transfer',
            self::METHOD_GCASH => 'GCash',
            self::METHOD_BPI => 'BPI',
            self::METHOD_CASH => 'Cash',
            default => ucfirst(str_replace('_', ' ', $this->payment_method)),
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

    public function scopeBalance($query)
    {
        return $query->where('payment_type', self::TYPE_BALANCE);
    }

    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }

    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }

    public function scopeByPaymentMethod($query, string $method)
    {
        return $query->where('payment_method', $method);
    }

    // Receipt request method
    public function requestReceipt(): void
    {
        if ($this->isApproved() && $this->receipt_request === self::RECEIPT_NOT_REQUESTED) {
            $this->update([
                'receipt_request' => self::RECEIPT_REQUESTED,
                'receipt_requested_at' => now(),
            ]);
        }
    }

    // Check if receipt is requested
    public function hasReceiptRequested(): bool
    {
        return $this->receipt_request === self::RECEIPT_REQUESTED;
    }

    // Check if receipt is created
    public function hasReceiptCreated(): bool
    {
        return $this->receipt_request === self::RECEIPT_CREATED;
    }

    // Mark receipt as created
    public function markReceiptCreated(): void
    {
        if ($this->receipt_request === self::RECEIPT_REQUESTED) {
            $this->update([
                'receipt_request' => self::RECEIPT_CREATED,
                'receipt_created_at' => now(),
            ]);
        }
    }
}
