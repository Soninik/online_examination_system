<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use App\Models\User;


class ForgetPasswordJob implements ShouldQueue
{
    use Queueable;

    protected $user;
    protected $token;
    /**
     * Create a new job instance.
     */
    public function __construct(User $user, $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    /**
     * Execute the job.
     */

    public function handle(): void
    {
        $this->user->notify(new \App\Notifications\ForgetPasswordNotification($this->token));
    }
}
