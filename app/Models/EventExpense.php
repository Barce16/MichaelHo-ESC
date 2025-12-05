<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class EventExpense extends Model
{
    use HasFactory;

    // Payment Status Constants
    const STATUS_UNPAID = 'unpaid';
    const STATUS_PAID = 'paid';

    protected $fillable = [
        'event_id',
        'added_by',
        'description',
        'amount',
        'category',
        'expense_date',
        'notes',
        'receipt_image',
        'payment_status',
        'paid_at',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'expense_date' => 'date',
        'paid_at' => 'datetime',
    ];

    /**
     * Expense categories
     */
    public const CATEGORY_TRANSPORTATION = 'transportation';
    public const CATEGORY_MATERIALS = 'materials';
    public const CATEGORY_LABOR = 'labor';
    public const CATEGORY_FOOD = 'food';
    public const CATEGORY_EQUIPMENT = 'equipment';
    public const CATEGORY_MISCELLANEOUS = 'miscellaneous';

    /**
     * Get all available categories
     */
    public static function getCategories(): array
    {
        return [
            self::CATEGORY_TRANSPORTATION => 'Transportation',
            self::CATEGORY_MATERIALS => 'Materials & Supplies',
            self::CATEGORY_LABOR => 'Labor & Manpower',
            self::CATEGORY_FOOD => 'Food & Catering',
            self::CATEGORY_EQUIPMENT => 'Equipment Rental',
            self::CATEGORY_MISCELLANEOUS => 'Miscellaneous',
        ];
    }

    /**
     * Get the event this expense belongs to
     */
    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Get the user who added this expense
     */
    public function addedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'added_by');
    }

    /**
     * Get the payment for this expense (if paid)
     */
    public function payment(): HasOne
    {
        return $this->hasOne(Payment::class, 'expense_id');
    }

    /**
     * Get category label
     */
    public function getCategoryLabelAttribute(): string
    {
        return self::getCategories()[$this->category] ?? ucfirst($this->category ?? 'Uncategorized');
    }

    /**
     * Check if expense is paid
     */
    public function isPaid(): bool
    {
        return $this->payment_status === self::STATUS_PAID;
    }

    /**
     * Check if expense is unpaid
     */
    public function isUnpaid(): bool
    {
        return $this->payment_status === self::STATUS_UNPAID;
    }

    /**
     * Mark expense as paid
     */
    public function markAsPaid(): void
    {
        $this->update([
            'payment_status' => self::STATUS_PAID,
            'paid_at' => now(),
        ]);
    }

    /**
     * Mark expense as unpaid
     */
    public function markAsUnpaid(): void
    {
        $this->update([
            'payment_status' => self::STATUS_UNPAID,
            'paid_at' => null,
        ]);
    }

    /**
     * Scope to filter by category
     */
    public function scopeCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    /**
     * Scope to filter by date range
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('expense_date', [$startDate, $endDate]);
    }

    /**
     * Scope for unpaid expenses
     */
    public function scopeUnpaid($query)
    {
        return $query->where('payment_status', self::STATUS_UNPAID);
    }

    /**
     * Scope for paid expenses
     */
    public function scopePaid($query)
    {
        return $query->where('payment_status', self::STATUS_PAID);
    }
}
