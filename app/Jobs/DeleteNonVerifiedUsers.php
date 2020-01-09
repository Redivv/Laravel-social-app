<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use App\User;
use Carbon\Carbon;

class DeleteNonVerifiedUsers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        
        $notVerifiedTimer = Carbon::now()->subDays(3)->toDateTimeString();
        $unverifiedUsers = User::whereNull('email_verified_at')->where('created_at','<',$notVerifiedTimer)->get();

        foreach ($unverifiedUsers as $user) {
            $user->deleteAll();
        }
    }
}
