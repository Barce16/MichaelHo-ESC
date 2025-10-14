<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Models\Event;
use App\Models\Payment;

class NotificationService
{
    /**
     * Notify admin of new event request
     */
    public function notifyAdminNewEventRequest(Event $event): void
    {
        $admins = User::where('user_type', 'admin')->get();

        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type' => 'event_request',
                'title' => 'New Event Request',
                'message' => "{$event->customer->customer_name} requested a new event: {$event->name}",
                'link' => route('admin.events.show', $event),
            ]);
        }
    }

    /**
     * Notify admin of new payment submission
     */
    public function notifyAdminPaymentSubmitted(Payment $payment): void
    {
        $admins = User::where('user_type', 'admin')->get();
        $event = $payment->billing->event;

        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type' => 'payment_submitted',
                'title' => 'New Payment Submitted',
                'message' => "{$event->customer->customer_name} submitted ₱" . number_format($payment->amount, 2) . " payment for {$event->name}",
                'link' => route('admin.events.show', $event),
            ]);
        }
    }

    /**
     * Notify customer of event status change
     */
    public function notifyCustomerEventStatus(Event $event, string $oldStatus, string $newStatus): void
    {
        $user = $event->customer->user;

        if (!$user) return;

        $statusLabels = [
            'requested' => 'Requested',
            'request_meeting' => 'Approved - Payment Required',
            'meeting' => 'Meeting Scheduled',
            'scheduled' => 'Event Scheduled',
            'ongoing' => 'Event Ongoing',
            'completed' => 'Event Completed',
            'rejected' => 'Request Rejected',
        ];

        Notification::create([
            'user_id' => $user->id,
            'type' => 'event_status',
            'title' => 'Event Status Updated',
            'message' => "Your event '{$event->name}' status changed to: {$statusLabels[$newStatus]}",
            'link' => route('customer.events.show', $event),
        ]);
    }

    /**
     * Notify customer of payment approval
     */
    public function notifyCustomerPaymentApproved(Payment $payment): void
    {
        $event = $payment->billing->event;
        $user = $event->customer->user;

        if (!$user) return;

        $paymentType = match ($payment->payment_type) {
            'introductory' => 'Introductory',
            'downpayment' => 'Downpayment',
            'balance' => 'Balance',
            default => 'Payment'
        };

        Notification::create([
            'user_id' => $user->id,
            'type' => 'payment_approved',
            'title' => 'Payment Approved',
            'message' => "Your {$paymentType} payment of ₱" . number_format($payment->amount, 2) . " for '{$event->name}' has been approved!",
            'link' => route('customer.events.show', $event),
        ]);
    }

    /**
     * Notify customer of payment rejection
     */
    public function notifyCustomerPaymentRejected(Payment $payment, string $reason): void
    {
        $event = $payment->billing->event;
        $user = $event->customer->user;

        if (!$user) return;

        Notification::create([
            'user_id' => $user->id,
            'type' => 'payment_rejected',
            'title' => 'Payment Rejected',
            'message' => "Your payment of ₱" . number_format($payment->amount, 2) . " was rejected. Reason: {$reason}",
            'link' => route('customer.events.show', $event),
        ]);
    }

    /**
     * Notify staff of new schedule assignment
     */
    public function notifyStaffNewSchedule($staffAssignment): void
    {
        $user = $staffAssignment->staff->user;

        if (!$user) return;

        $event = $staffAssignment->event;

        $message = "You've been assigned to '{$event->name}' on " . $event->event_date->format('M d, Y');

        // Add role info if available
        if (isset($staffAssignment->assignment_role)) {
            $message .= " as {$staffAssignment->assignment_role}";
        }

        Notification::create([
            'user_id' => $user->id,
            'type' => 'schedule_assigned',
            'title' => 'New Schedule Assigned',
            'message' => $message,
            'link' => route('staff.schedules.index'),
        ]);
    }

    /**
     * Notify staff of schedule removal
     */
    public function notifyStaffScheduleRemoved($staff, $event): void
    {
        $user = $staff->user;

        if (!$user) return;

        Notification::create([
            'user_id' => $user->id,
            'type' => 'schedule_removed',
            'title' => 'Schedule Removed',
            'message' => "You've been removed from '{$event->name}' on " . $event->event_date->format('M d, Y'),
            'link' => route('staff.schedules.index'),
        ]);
    }

    /**
     * Notify staff of payroll payment
     */
    public function notifyStaffPayrollPaid($payrollRecord): void
    {
        $user = $payrollRecord->staff->user;

        if (!$user) return;

        Notification::create([
            'user_id' => $user->id,
            'type' => 'payroll_paid',
            'title' => 'Payroll Paid',
            'message' => "Your payroll of ₱" . number_format($payrollRecord->total_amount, 2) . " has been paid!",
            'link' => route('staff.earnings'),
        ]);
    }

    /**
     * Get unread count for user
     */
    public function getUnreadCount(User $user): int
    {
        return Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->count();
    }

    /**
     * Get recent notifications for user
     */
    public function getRecentNotifications(User $user, int $limit = 10)
    {
        return Notification::where('user_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Mark all as read for user
     */
    public function markAllAsRead(User $user): void
    {
        Notification::where('user_id', $user->id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
    }
}
