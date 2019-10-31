<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ResetPassWordTokenMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * @var $user
     * holds user data
     * @return $user
     */
    public $user;
    public $token;
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
    public function __construct($user, $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('support@rentch.ng')->subject('Rentch.ng Password Reset Token')->markdown('email.user.reset_password_token');
    }
}
