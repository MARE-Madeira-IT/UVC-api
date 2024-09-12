<?php

namespace App\Mail;

use App\Helper;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\User;

class ConfirmEmail extends Mailable
{
    use Queueable, SerializesModels;

   public $user;
   public $name;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $name)
    {
        $this->user = $user;
        $this->name = $name;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        /* Helper::printToConsole($this->user); */
        return $this->from('support@wave-labs.org', 'Wave Labs Support')->subject('Wave Labs Confirm Email')->view('mail.confirm-email');
    }
}
