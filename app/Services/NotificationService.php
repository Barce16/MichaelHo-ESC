<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\User;
use App\Models\Event;
use App\Models\InclusionChangeRequest;
use App\Models\Payment;
use App\Models\EventProgress;

class NotificationService
{

    protected function createNotification(
        int $userId,
        string $type,
        string $title,
        string $message,
        array $data = [],
        ?string $actionUrl = null
    ): void {
        Notification::create([
            'user_id' => $userId,
            'type' => $type,
            'title' => $title,
            'message' => $message,
            'data' => $data,
            'action_url' => $actionUrl,
            'read_at' => null,
        ]);
    }


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
                'link' => route('admin.payments.show', $payment),
            ]);
        }
    }

    /**
     * Notify admin of receipt request
     */
    public function notifyAdminReceiptRequested(Payment $payment): void
    {
        $admins = User::where('user_type', 'admin')->get();
        $event = $payment->billing->event;

        $paymentType = match ($payment->payment_type) {
            'introductory' => 'Introductory',
            'downpayment' => 'Downpayment',
            'balance' => 'Balance',
            default => 'Payment'
        };

        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type' => 'receipt_request',
                'title' => 'Receipt Requested',
                'message' => "{$event->customer->customer_name} requested an official receipt for {$paymentType} payment of ₱" . number_format($payment->amount, 2) . " ({$event->name})",
                'link' => route('admin.payments.show', $payment),
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
                'message' => "Great news! Your event '{$event->name}' has been approved. Please pay the ₱5,000 introductory payment to proceed."
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


    /**
     * Notify customer when event inclusions are updated
     * 
     * @param Event $event The event that was updated
     * @param float $oldTotal The previous total amount
     * @param float $newTotal The new total amount
     * @return void
     */
    public function notifyCustomerInclusionsUpdated(Event $event, float $oldTotal, float $newTotal): void
    {
        $user = $event->customer->user;

        if (!$user) return;

        // Calculate the difference
        $difference = $newTotal - $oldTotal;

        // Format the change text with appropriate symbol
        if ($difference > 0) {
            $changeText = '+₱' . number_format($difference, 2);
        } elseif ($difference < 0) {
            $changeText = '-₱' . number_format(abs($difference), 2);
        } else {
            $changeText = 'No change in total';
        }

        Notification::create([
            'user_id' => $user->id,
            'type' => 'inclusions_updated',
            'title' => 'Event Inclusions Updated',
            'message' => "Your event inclusions have been updated. New total: ₱" . number_format($newTotal, 2) . " ({$changeText})",
            'link' => route('customer.events.show', $event),
        ]);
    }

    /**
     * Notify customer that receipt is ready for download
     */
    public function notifyCustomerReceiptReady(Payment $payment): void
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
            'type' => 'receipt_ready',
            'title' => 'Receipt Ready for Download',
            'message' => "Your official receipt for {$paymentType} payment of ₱" . number_format($payment->amount, 2) . " ({$event->name}) is now ready to download!",
            'link' => route('customer.payments.index'),
        ]);
    }

    /**
     * Notify admin when customer updates their event
     */
    public function notifyAdminEventUpdated(Event $event, string $changeMessage): void
    {
        $admins = User::where('user_type', 'admin')->get();

        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type' => 'event_updated',
                'title' => 'Event Updated by Customer',
                'message' => "{$event->customer->customer_name} updated their event '{$event->name}'.\n\nChanges:\n{$changeMessage}",
                'link' => route('admin.events.show', $event),
                'is_read' => false,
            ]);
        }
    }

    /**
     * Notify admin when staff marks work as finished
     */
    public function notifyAdminStaffWorkFinished($staff, $event)
    {
        $admins = User::where('user_type', 'admin')
            ->where('status', 'active')
            ->get();

        $title = "Staff Work Completed";
        $message = "{$staff->name} has marked their work as finished for the event '{$event->name}' on " .
            \Carbon\Carbon::parse($event->event_date)->format('M d, Y');

        $actionUrl = route('admin.payroll.viewStaffs', $event);

        foreach ($admins as $admin) {
            // Create in-app notification
            Notification::create([
                'user_id' => $admin->id,
                'type' => 'staff_work_finished',
                'title' => $title,
                'message' => $message,
                'link' => $actionUrl,
                'is_read' => false,
            ]);
        }

        return true;
    }

    /**
     * Notify admins when customer updates their event inclusions
     */
    public function notifyAdminCustomerInclusionsUpdated($event, $customer, $oldTotal, $newTotal, $addedInclusions, $removedInclusions)
    {
        $admins = User::where('user_type', 'admin')
            ->where('status', 'active')
            ->get();

        // Build change summary
        $changeSummary = [];
        if ($addedInclusions->count() > 0) {
            $changeSummary[] = $addedInclusions->count() . " added";
        }
        if ($removedInclusions->count() > 0) {
            $changeSummary[] = $removedInclusions->count() . " removed";
        }

        $changeText = !empty($changeSummary) ? implode(", ", $changeSummary) : "modified";

        $totalChange = $newTotal - $oldTotal;
        $changeAmount = $totalChange >= 0
            ? "+₱" . number_format($totalChange, 2)
            : "-₱" . number_format(abs($totalChange), 2);

        $title = "Customer Updated Event Inclusions";
        $message = "{$customer->customer_name} updated inclusions for '{$event->name}' ({$changeText}). " .
            "Total changed from ₱" . number_format($oldTotal, 2) . " to ₱" . number_format($newTotal, 2) . " ({$changeAmount}).";

        $actionUrl = route('admin.events.show', $event);

        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type' => 'customer_inclusions_updated',
                'title' => $title,
                'message' => $message,
                'link' => $actionUrl,
                'is_read' => false,
            ]);
        }

        return true;
    }

    /**
     * Notify admins when customer updates their phone number
     * (In-app notification only, no email)
     */
    public function notifyAdminCustomerPhoneUpdated($event, $customer, $oldPhone, $newPhone)
    {
        $admins = User::where('user_type', 'admin')
            ->where('status', 'active')
            ->get();

        $title = "Customer Contact Number Updated";
        $message = "{$customer->customer_name} updated their contact number for event '{$event->name}'. " .
            "Changed from '{$oldPhone}' to '{$newPhone}'.";

        $actionUrl = route('admin.events.show', $event);

        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type' => 'customer_phone_updated',
                'title' => $title,
                'message' => $message,
                'link' => $actionUrl,
                'is_read' => false,
            ]);
        }

        return true;
    }
    /**
     * Notify admins about new or updated inclusion change request (IN-APP ONLY)
     */
    public function notifyAdminsInclusionChangeRequest(InclusionChangeRequest $changeRequest, bool $isUpdate = false): void
    {
        // Get all active admin users
        $admins = User::where('user_type', 'admin')
            ->where('status', 'active')
            ->get();

        $event = $changeRequest->event;
        $customer = $changeRequest->customer;

        // Count added and removed inclusions
        $addedCount = count($changeRequest->getAddedInclusions());
        $removedCount = count($changeRequest->getRemovedInclusions());

        // Calculate price difference
        $difference = $changeRequest->difference;
        $differenceText = $difference > 0
            ? '+₱' . number_format($difference, 2)
            : ($difference < 0
                ? '-₱' . number_format(abs($difference), 2)
                : '₱0.00');

        // Build notification title and message
        $title = $isUpdate ? 'Inclusion Change Request Updated' : 'New Inclusion Change Request';

        $message = "{$customer->customer_name} " . ($isUpdate ? 'updated' : 'submitted') .
            " inclusion changes for '{$event->name}'. " .
            "Changes: +{$addedCount} / -{$removedCount} items ({$differenceText})";

        $actionUrl = route('admin.change-requests.show', $changeRequest);

        // Create in-app notification for each admin
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type' => 'inclusion_change_request',
                'title' => $title,
                'message' => $message,
                'link' => $actionUrl,
                'is_read' => false,
            ]);
        }
    }

    /**
     * Notify customer that their change request was approved (IN-APP ONLY)
     */
    public function notifyCustomerChangeRequestApproved(InclusionChangeRequest $changeRequest): void
    {
        $event = $changeRequest->event;
        $customer = $changeRequest->customer;
        $user = $customer->user;

        if (!$user) return;

        $difference = $changeRequest->difference;
        $differenceText = $difference > 0
            ? '+₱' . number_format($difference, 2)
            : ($difference < 0
                ? '-₱' . number_format(abs($difference), 2)
                : 'No change');

        Notification::create([
            'user_id' => $user->id,
            'type' => 'change_request_approved',
            'title' => 'Inclusion Change Request Approved',
            'message' => "Your inclusion changes for '{$event->name}' have been approved. New total: ₱" . number_format($changeRequest->new_total, 2) . " ({$differenceText})",
            'link' => route('customer.events.show', $event),
            'is_read' => false,
        ]);
    }

    /**
     * Notify customer that their change request was rejected (IN-APP ONLY)
     */
    public function notifyCustomerChangeRequestRejected(InclusionChangeRequest $changeRequest): void
    {
        $event = $changeRequest->event;
        $customer = $changeRequest->customer;
        $user = $customer->user;

        if (!$user) return;

        $reason = $changeRequest->admin_notes ?? 'No reason provided';

        Notification::create([
            'user_id' => $user->id,
            'type' => 'change_request_rejected',
            'title' => 'Inclusion Change Request Rejected',
            'message' => "Your inclusion changes for '{$event->name}' were not approved. Reason: {$reason}",
            'link' => route('customer.events.show', $event),
            'is_read' => false,
        ]);
    }

    /**
     * Notify customer of event progress update
     */
    public function notifyCustomerEventProgress(Event $event, $progress): void
    {
        $user = $event->customer->user;

        if (!$user) return;

        $message = "Progress update for '{$event->name}': {$progress->status}";
        if ($progress->details) {
            $message .= " - {$progress->details}";
        }

        Notification::create([
            'user_id' => $user->id,
            'type' => 'event_progress',
            'title' => 'Event Progress Update',
            'message' => $message,
            'link' => route('customer.events.show', $event),
            'is_read' => false,
        ]);
    }

    public function notifyCustomerEventProgressUpdate(Event $event, EventProgress $progress): void
    {
        $customer = $event->customer;

        if (!$customer || !$customer->user) {
            return;
        }

        $this->createNotification(
            $customer->user->id,
            'event_progress_updated',
            'Progress Update Modified',
            "A progress update for '{$event->name}' has been modified: {$progress->status}",
            [
                'event_id' => $event->id,
                'progress_id' => $progress->id,
                'status' => $progress->status,
            ],
            route('customer.events.show', $event)
        );
    }

    /**
     * Notify customer when event schedules are updated
     */
    public function notifyCustomerScheduleUpdate(Event $event, array $schedules, string $action = 'updated'): void
    {
        $customer = $event->customer;

        if (!$customer || !$customer->user) {
            return;
        }

        $scheduleCount = count($schedules);
        $actionText = $action === 'created' ? 'added' : 'updated';

        if ($scheduleCount === 1) {
            $schedule = $schedules[0];
            $schedule->load('inclusion');
            $message = "Schedule {$actionText} for '{$event->name}': {$schedule->inclusion->name} on " .
                \Carbon\Carbon::parse($schedule->scheduled_date)->format('M d, Y');
        } else {
            $message = "{$scheduleCount} schedules have been {$actionText} for '{$event->name}'";
        }

        $this->createNotification(
            $customer->user->id,
            'event_schedule_updated',
            'Schedule ' . ucfirst($actionText),
            $message,
            [
                'event_id' => $event->id,
                'schedule_count' => $scheduleCount,
                'action' => $action,
            ],
            route('customer.events.show', $event)
        );
    }
}
