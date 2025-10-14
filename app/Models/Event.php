<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    // Status Constants
    const STATUS_REQUESTED = 'requested';
    const STATUS_APPROVED = 'approved';
    const STATUS_REQUEST_MEETING = 'request_meeting';
    const STATUS_MEETING = 'meeting';
    const STATUS_SCHEDULED = 'scheduled';
    const STATUS_ONGOING = 'ongoing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_REJECTED = 'rejected';

    protected $fillable = [
        'customer_id',
        'package_id',
        'name',
        'event_date',
        'venue',
        'theme',
        'budget',
        'guests',
        'notes',
        'status',
        'rejection_reason',

    ];

    protected $casts = [
        'event_date' => 'date',
        'budget' => 'decimal:2',
    ];

    // Relationships
    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(Package::class);
    }

    public function billing(): HasOne
    {
        return $this->hasOne(Billing::class);
    }

    public function payments()
    {
        return $this->hasManyThrough(Payment::class, Billing::class);
    }

    public function inclusions(): BelongsToMany
    {
        return $this->belongsToMany(Inclusion::class, 'event_inclusion')
            ->withPivot('price_snapshot')
            ->withTimestamps();
    }

    public function staffs(): BelongsToMany
    {
        return $this->belongsToMany(Staff::class, 'event_staff')
            ->withPivot('assignment_role', 'pay_rate', 'pay_status')
            ->withTimestamps();
    }

    public function meetings(): HasMany
    {
        return $this->hasMany(Meeting::class);
    }

    // Status Check Methods
    public function isRequested(): bool
    {
        return $this->status === self::STATUS_REQUESTED;
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function isAwaitingIntroPayment(): bool
    {
        return $this->status === self::STATUS_REQUEST_MEETING;
    }

    public function isAwaitingMeeting(): bool
    {
        return $this->status === self::STATUS_MEETING;
    }

    public function isScheduled(): bool
    {
        return $this->status === self::STATUS_SCHEDULED;
    }

    public function isOngoing(): bool
    {
        return $this->status === self::STATUS_ONGOING;
    }

    public function isCompleted(): bool
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }

    // Payment Status Methods
    public function needsIntroPayment(): bool
    {
        return $this->status === self::STATUS_REQUEST_MEETING &&
            $this->billing &&
            $this->billing->introductory_payment_status !== 'paid';
    }

    public function hasIntroPaymentPending(): bool
    {
        return $this->billing &&
            $this->billing->payments()
            ->where('payments.payment_type', 'introductory')
            ->where('payments.status', 'pending')
            ->exists();
    }

    public function needsDownpayment(): bool
    {
        return $this->status === self::STATUS_MEETING &&
            $this->billing &&
            $this->billing->downpayment_amount > 0 &&
            !$this->hasDownpaymentPaid();
    }

    public function hasDownpaymentPending(): bool
    {
        return $this->billing &&
            $this->billing->payments()
            ->where('payments.payment_type', 'downpayment')
            ->where('payments.status', 'pending')
            ->exists();
    }

    public function hasDownpaymentPaid(): bool
    {
        return $this->billing &&
            $this->billing->payments()
            ->where('payments.payment_type', 'downpayment')
            ->where('payments.status', 'approved')
            ->exists();
    }

    // Staff Assignment
    public function canAssignStaff(): bool
    {
        return in_array($this->status, [
            self::STATUS_SCHEDULED,
            self::STATUS_ONGOING
        ]);
    }

    // Get Status Label (for display)
    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_REQUESTED => 'Pending Approval',
            self::STATUS_APPROVED => 'Approved',
            self::STATUS_REQUEST_MEETING => 'Awaiting Introductory Payment',
            self::STATUS_MEETING => 'Meeting Confirmed',
            self::STATUS_SCHEDULED => 'Scheduled',
            self::STATUS_ONGOING => 'Ongoing',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_REJECTED => 'Rejected',
            default => ucfirst($this->status),
        };
    }

    // Get Status Color (for badges)
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_REQUESTED => 'yellow',
            self::STATUS_APPROVED => 'green',
            self::STATUS_REQUEST_MEETING => 'orange',
            self::STATUS_MEETING => 'blue',
            self::STATUS_SCHEDULED => 'purple',
            self::STATUS_ONGOING => 'teal',
            self::STATUS_COMPLETED => 'gray',
            self::STATUS_REJECTED => 'red',
            default => 'gray',
        };
    }

    // Scope for filtering
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('event_date', '>=', now())
            ->whereIn('status', [self::STATUS_SCHEDULED, self::STATUS_MEETING]);
    }

    public function scopeToday($query)
    {
        return $query->whereDate('event_date', today())
            ->where('status', '!=', self::STATUS_COMPLETED);
    }

    public function scopePast($query)
    {
        return $query->where('event_date', '<', today())
            ->where('status', '!=', self::STATUS_COMPLETED);
    }

    public function feedback(): HasOne
    {
        return $this->hasOne(Feedback::class);
    }

    public function hasFeedback(): bool
    {
        return $this->feedback()->exists();
    }
}
