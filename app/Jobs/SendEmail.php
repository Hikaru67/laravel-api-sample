<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * mail template
     */
    protected $mailTemplate;

    /**
     * mail data
     */
    protected $mailData;

    /**
     * mail receiver
     */
    protected $mailReceiver;

    /**
     * mail subject
     */
    protected $mailSubject;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($mailTemplate, $mailData, $mailReceiver, $mailSubject)
    {
        $this->mailTemplate = $mailTemplate;
        $this->mailData = $mailData;
        $this->mailReceiver = $mailReceiver;
        $this->mailSubject = $mailSubject;
    }

    /**
     * The number of seconds to wait before retrying the job.
     *
     * @var int
     */
    public $retryAfter = 3;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $rsm2 = Mail::send($this->mailTemplate, ['mailData' => $this->mailData], function ($m) {
            $m->from('admin@ol-as.net', 'OLASオンラインサロン');
            $m->to($this->mailReceiver, 'OLASオンラインサロン')->subject($this->mailSubject);
        });

        if (Mail::failures()) {
            Log::error('[SendEmailJobs:' . __LINE__ . 'object =>' . $this->mailSubject . ', receiver =>' . $this->mailReceiver . '] Sending email has been failed!');
        } else {
            Log::info('[SendEmailJobs: object =>' . $this->mailSubject . ', receiver =>' . $this->mailReceiver . '] Sending email successfully!');
        }
    }
}
