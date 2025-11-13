<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InclusionChangeRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'customer_id',
        'old_inclusions',
        'new_inclusions',
        'inclusion_notes',
        'old_total',
        'new_total',
        'difference',
        'status',
        'customer_notes',
        'reviewed_by',
        'admin_notes',
        'reviewed_at',
    ];

    protected $casts = [
        'old_inclusions' => 'array',
        'new_inclusions' => 'array',
        'inclusion_notes' => 'array',
        'old_total' => 'decimal:2',
        'new_total' => 'decimal:2',
        'difference' => 'decimal:2',
        'reviewed_at' => 'datetime',
    ];

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';

    // Relationships
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function reviewedBy()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    // Scopes
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

    // Helper methods
    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isApproved()
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isRejected()
    {
        return $this->status === self::STATUS_REJECTED;
    }

    public function getAddedInclusions()
    {
        $oldIds = collect($this->old_inclusions)->pluck('id')->toArray();
        return collect($this->new_inclusions)->filter(function ($item) use ($oldIds) {
            return !in_array($item['id'], $oldIds);
        })->values();
    }

    public function getRemovedInclusions()
    {
        $newIds = collect($this->new_inclusions)->pluck('id')->toArray();
        return collect($this->old_inclusions)->filter(function ($item) use ($newIds) {
            return !in_array($item['id'], $newIds);
        })->values();
    }

    /**
     * Get human-readable status label
     */
    public function getStatusLabel()
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'Pending Review',
            self::STATUS_APPROVED => 'Approved',
            self::STATUS_REJECTED => 'Rejected',
            default => ucfirst($this->status),
        };
    }

    /**
     * Get Tailwind CSS classes for status badge
     */
    public function getStatusBadgeClass()
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'bg-yellow-100 text-yellow-800',
            self::STATUS_APPROVED => 'bg-green-100 text-green-800',
            self::STATUS_REJECTED => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}
