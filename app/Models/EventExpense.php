<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventExpense extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'added_by',
        'description',
        'amount',
        'category',
        'expense_date',
        'notes',
        'receipt_image',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'expense_date' => 'date',
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
     * Get category label
     */
    public function getCategoryLabelAttribute(): string
    {
        return self::getCategories()[$this->category] ?? ucfirst($this->category ?? 'Uncategorized');
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
}
