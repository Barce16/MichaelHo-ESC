<?php

namespace App\Notifications;

use App\Models\Event;
use App\Models\Customer;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Collection;

class CustomerInclusionsUpdatedNotification extends Notification
{
    use Queueable;

    protected $event;
    protected $customer;
    protected $oldTotal;
    protected $newTotal;
    protected $addedInclusions;
    protected $removedInclusions;

    public function __construct(
        Event $event,
        Customer $customer,
        $oldTotal,
        $newTotal,
        Collection $addedInclusions,
        Collection $removedInclusions
    ) {
        $this->event = $event;
        $this->customer = $customer;
        $this->oldTotal = $oldTotal;
        $this->newTotal = $newTotal;
        $this->addedInclusions = $addedInclusions;
        $this->removedInclusions = $removedInclusions;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Customer Updated Inclusions - ' . $this->event->name)
            ->view('emails.customer-inclusions-updated', [
                'event' => $this->event,
                'customer' => $this->customer,
                'oldTotal' => $this->oldTotal,
                'newTotal' => $this->newTotal,
                'addedInclusions' => $this->addedInclusions,
                'removedInclusions' => $this->removedInclusions,
            ]);
    }
}
