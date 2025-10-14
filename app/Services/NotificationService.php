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

        // Customized messages for each status
        $statusMessages = [
            'requested' => [
                'title' => 'Event Request Received',
                'message' => "Thank you! Your event '{$event->name}' has been received and is under review."
            ],
            'request_meeting' => [
                'title' => 'Event Approved!',
                'message' => "Great news! Your event '{$event->name}' has been approved. Please pay the ₱15,000 introductory payment to proceed."
            ],
            'meeting' => [
                'title' => 'Meeting Scheduled',
                'message' => "Your introductory payment has been confirmed! A meeting for '{$event->name}' has been scheduled to finalize the details."
            ],
            'scheduled' => [
                'title' => 'Event Scheduled',
                'message' => "Exciting! Your event '{$event->name}' has been officially scheduled for " . $event->event_date->format('F d, Y') . ". We're ready to make it amazing!"
            ],
            'ongoing' => [
                'title' => 'Event Started',
                'message' => "Your event '{$event->name}' is now happening! We hope you're having a wonderful time. Enjoy every moment!"
            ],
            'completed' => [
                'title' => 'Event Completed',
                'message' => "Your event '{$event->name}' has been completed successfully! Thank you for choosing us. We'd love to hear your feedback!"
            ],
            'rejected' => [
                'title' => 'Event Request Declined',
                'message' => "We regret to inform you that your event '{$event->name}' request could not be approved" . ($event->rejection_reason ? ". Reason: {$event->rejection_reason}" : ".") . " Please contact us for more information."
            ],
        ];

        $notification = $statusMessages[$newStatus] ?? [
            'title' => 'Event Status Updated',
            'message' => "Your event '{$event->name}' status has been updated."
        ];

        Notification::create([
            'user_id' => $user->id,
            'type' => 'event_status',
            'title' => $notification['title'],
            'message' => $notification['message'],
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


    /**
     * Notify admin of new customer feedback
     */
    public function notifyAdminCustomerFeedback($feedback): void
    {
        $admins = User::where('user_type', 'admin')->get();

        $event = $feedback->event;
        $customer = $feedback->customer;

        $stars = str_repeat('⭐', $feedback->rating);

        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type' => 'customer_feedback',
                'title' => 'New Customer Feedback',
                'message' => "{$customer->customer_name} rated '{$event->name}' {$stars} ({$feedback->rating}/5)",
                'link' => route('admin.events.show', $event),
            ]);
        }
    }
}
