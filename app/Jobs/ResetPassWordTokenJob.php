<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Mail\ResetPassWordTokenMail;
use Mail;

class ResetPassWordTokenJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @var user
     * holds new user resource
     */
    public $user;
    /**
     * @var token
     * holds new reset passeord token
     */
    public $token;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($user, $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $email = New ResetPassWordTokenMail($this->user, $this->token);
        Mail::to($this->user->email)->send($email);
    }
}
