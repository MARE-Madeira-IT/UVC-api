<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\User;

class PasswordReset extends Mailable
{
    use Queueable, SerializesModels;

   public $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    /* public function __construct(User $user, $token)
    {
        $this->user = $user;
        $this->token = $token;
    } */

    public function __construct($user)
    {
        $this->user = $user;
    } 

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('support@wave-labs.org', 'Wave Labs Support')->subject('Wave Labs Password Reset')->view('mail.password-reset');
    }
}
