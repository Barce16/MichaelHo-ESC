<?php

namespace App\Notifications;

use App\Models\InclusionChangeRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ChangeRequestApprovedNotification extends Notification
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

        $difference = $this->changeRequest->difference;
        $differenceText = $difference > 0
            ? '+₱' . number_format($difference, 2) . ' increase'
            : ($difference < 0
                ? '-₱' . number_format(abs($difference), 2) . ' decrease'
                : 'No price change');

        return (new MailMessage)
            ->subject('Your Inclusion Change Request Has Been Approved')
            ->view('emails.change-request-approved', [
                'changeRequest' => $this->changeRequest,
                'event' => $event,
                'customer' => $customer,
                'addedInclusions' => $addedInclusions,
                'removedInclusions' => $removedInclusions,
                'differenceText' => $differenceText,
            ]);
    }
}
