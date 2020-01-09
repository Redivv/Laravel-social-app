<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Illuminate\Support\Facades\Notification;
use App\Notifications\AdminMailInfo;

class SendAdminNewsletter implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $subject;
    protected $contents;
    protected $mailingList;

    public $tries = 1;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(string $subject, string $contents, object $mailingList)
    {
        $this->subject      = $subject;
        $this->contents     = $contents;
        
        $this->mailingList  = $mailingList;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        Notification::send($this->mailingList, new AdminMailInfo($this->subject, $this->contents));
    }
}
