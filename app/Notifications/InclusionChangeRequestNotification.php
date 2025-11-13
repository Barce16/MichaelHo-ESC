<?php

namespace App\Notifications;

use App\Models\InclusionChangeRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class InclusionChangeRequestNotification extends Notification
{
    use Queueable;

    protected $changeRequest;
    protected $isUpdate;

    public function __construct(InclusionChangeRequest $changeRequest, bool $isUpdate = false)
    {
        $this->changeRequest = $changeRequest;
        $this->isUpdate = $isUpdate;
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

        $subject = ($this->isUpdate ? 'Updated: ' : 'New: ') . 'Inclusion Change Request - ' . $event->name;

        return (new MailMessage)
            ->subject($subject)
            ->view('emails.inclusion-change-request', [
                'changeRequest' => $this->changeRequest,
                'event' => $event,
                'customer' => $customer,
                'isUpdate' => $this->isUpdate,
                'addedInclusions' => $addedInclusions,
                'removedInclusions' => $removedInclusions,
                'differenceText' => $differenceText,
            ]);
    }
}
