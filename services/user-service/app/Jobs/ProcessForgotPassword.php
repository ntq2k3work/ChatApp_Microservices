<?php

namespace App\Jobs;

use App\Mail\ForgotPassword;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class ProcessForgotPassword implements ShouldQueue
{
    use Queueable;

    protected $tries = 3;
    protected $backoff = 60; // seconds

    protected $token;
    protected $user;

    /**
     * Create a new job instance.
     */
    public function __construct( $user, string $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            // Dispatch the email job
            Mail::to($this->user->email)->send(new ForgotPassword($this->user, $this->token));
            Log::info('Forgot password email sent to ' . $this->user->email);
        } catch (\Exception $e) {
            Log::error('Failed to send forgot password email: ' . $e->getMessage());
            throw $e; // Re-throw the exception to allow retrying
        }
    }
}
