<?php

namespace App\Mail;

use App\Models\User;
use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class WalkinCustomerCredentials extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $password;
    public $event;

    public function __construct(User $user, string $password, Event $event)
    {
        $this->user = $user;
        $this->password = $password;
        $this->event = $event;
    }

    public function build()
    {
        return $this->subject('Welcome to Michael Ho Events - Your Account Details')
            ->view('emails.walkin-customer-credentials');
    }
}
