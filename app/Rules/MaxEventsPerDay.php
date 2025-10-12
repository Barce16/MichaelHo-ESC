<?php

namespace App\Rules;

use App\Models\Event;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Carbon;

class MaxEventsPerDay implements ValidationRule
{
    protected $eventId;
    protected $maxEvents;
    protected $existingCount;

    public function __construct(?int $eventId = null, int $maxEvents = 2)
    {
        $this->eventId = $eventId;
        $this->maxEvents = $maxEvents;
    }

    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Parse the date
        try {
            $date = Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            // If date is invalid, let other validation rules handle it
            return;
        }

        // Count events on this date
        $query = Event::whereDate('event_date', $date)
            ->whereNotIn('status', ['cancelled', 'canceled']); // Don't count cancelled events

        // If editing an existing event, exclude it from the count
        if ($this->eventId) {
            $query->where('id', '!=', $this->eventId);
        }

        $this->existingCount = $query->count();

        // If we've reached the maximum, fail validation
        if ($this->existingCount >= $this->maxEvents) {
            $fail("This date is fully booked. Maximum {$this->maxEvents} events per day allowed. There are already {$this->existingCount} event(s) scheduled.");
        }
    }

    /**
     * Get the existing count (useful for messages)
     */
    public function getExistingCount(): int
    {
        return $this->existingCount ?? 0;
    }
}
