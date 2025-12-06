<?php

namespace App\Notifications;

use App\Models\Event;
use App\Models\EventExpense;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ExpenseAddedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $event;
    protected $expense;

    public function __construct(Event $event, EventExpense $expense)
    {
        $this->event = $event;
        $this->expense = $expense;
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        $categoryLabel = $this->expense->category
            ? EventExpense::getCategories()[$this->expense->category] ?? ucfirst($this->expense->category)
            : 'General';

        return (new MailMessage)
            ->subject('New Expense Added - ' . $this->event->name)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line('A new expense has been added to your event **' . $this->event->name . '**.')
            ->line('---')
            ->line('**Expense Details:**')
            ->line('• **Description:** ' . $this->expense->description)
            ->line('• **Amount:** ₱' . number_format($this->expense->amount, 2))
            ->line('• **Category:** ' . $categoryLabel)
            ->line('• **Date:** ' . ($this->expense->expense_date?->format('F d, Y') ?? 'Not specified'))
            ->when($this->expense->notes, function ($mail) {
                return $mail->line('• **Notes:** ' . $this->expense->notes);
            })
            ->line('---')
            ->line('**What\'s Next?**')
            ->line('This expense has been added to your event balance. You can view the details and pay this expense through your customer portal.')
            ->action('View Event Details', route('customer.events.show', $this->event))
            ->line('---')
            ->line('If you have any questions about this expense, please contact us.')
            ->salutation('Best regards, Michael Ho Events');
    }

    public function toArray($notifiable): array
    {
        return [
            'event_id' => $this->event->id,
            'expense_id' => $this->expense->id,
            'description' => $this->expense->description,
            'amount' => $this->expense->amount,
        ];
    }
}
