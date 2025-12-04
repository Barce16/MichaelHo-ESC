@component('mail::message')
# New Schedule Assignment

Hello **{{ $staffName }}**,

You have been assigned to a new task for an upcoming event.

---

## Assignment Details

@component('mail::panel')
**Task:** {{ $inclusionName }}

**Event:** {{ $eventName }}

**Date:** {{ $scheduledDate }}

**Time:** {{ $scheduledTime }}

@if($venue)
**Venue:** {{ $venue }}
@endif

@if($remarks)
**Notes:** {{ $remarks }}
@endif
@endcomponent

---

## What's Next?

<x-mail::table>
    | Step | Action |
    |:-----|:-------|
    | 1 | Review the assignment details above |
    | 2 | Mark your calendar for the scheduled date |
    | 3 | Prepare any materials needed for the task |
    | 4 | **Upload proof** after completing the task |
</x-mail::table>

@component('mail::button', ['url' => $actionUrl, 'color' => 'primary'])
View My Schedules
@endcomponent

---

**Important:** Please upload proof of completion after finishing this task. This helps us track progress and maintain
quality.

If you have any questions or need to discuss this assignment, please contact the admin team.

Thanks,<br>
**{{ config('app.name') }}**

<small style="color: #6b7280;">
    This notification was sent on {{ now()->format('F d, Y \a\t g:i A') }}
</small>
@endcomponent