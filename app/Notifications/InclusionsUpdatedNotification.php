<?php

namespace App\Notifications;

use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Collection;

class InclusionsUpdatedNotification extends Notification
{
    use Queueable;

    protected $event;
    protected $oldTotal;
    protected $newTotal;
    protected $addedInclusions;
    protected $removedInclusions;

    public function __construct(Event $event, $oldTotal, $newTotal, Collection $addedInclusions, Collection $removedInclusions)
    {
        $this->event = $event;
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
            ->subject('Event Inclusions Updated - ' . $this->event->name)
            ->view('emails.inclusions-updated', [
                'event' => $this->event,
                'customer' => $this->event->customer,
                'oldTotal' => $this->oldTotal,
                'newTotal' => $this->newTotal,
                'addedInclusions' => $this->addedInclusions,
                'removedInclusions' => $this->removedInclusions,
            ]);
    }
}
