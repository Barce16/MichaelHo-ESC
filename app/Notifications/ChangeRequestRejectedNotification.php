<?php

namespace App\Notifications;

use App\Models\InclusionChangeRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ChangeRequestRejectedNotification extends Notification
{
    use Queueable;

    protected $changeRequest;

    public function __construct(InclusionChangeRequest $changeRequest)
    {
        $this->changeRequest = $changeRequest;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $event = $this->changeRequest->event;
        $customer = $this->changeRequest->customer;

        $addedInclusions = $this->changeRequest->getAddedInclusions();
        $removedInclusions = $this->changeRequest->getRemovedInclusions();

        return (new MailMessage)
            ->subject('Update on Your Inclusion Change Request')
            ->view('emails.change-request-rejected', [
                'changeRequest' => $this->changeRequest,
                'event' => $event,
                'customer' => $customer,
                'addedInclusions' => $addedInclusions,
                'removedInclusions' => $removedInclusions,
                'rejectionReason' => $this->changeRequest->admin_notes ?? 'No reason provided',
            ]);
    }
}
