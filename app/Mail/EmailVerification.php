<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class EmailVerification extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var $user
     * holds user data
     * @return $user
     */
    public $user;
    public $logo = [
            'path'   => 'http://raix.rentch.ng/assets/images/logo-blue.png',
            'width'  => '',
            'height' => '',
        ];
    /**
     * Create a new message instance.
     *
     * @return void
     */
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
        return $this->from('support@rentch.ng')->subject('Welcome to Rentch.ng')->view('email.user.email_verification');
    }
}
