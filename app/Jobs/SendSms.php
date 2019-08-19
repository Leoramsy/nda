<?php

namespace App\Jobs;

use App\Models\Contact;
use Twilio\Rest\Client;
use App\Models\NotificationLog;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendSms implements ShouldQueue {

    use Dispatchable,
        InteractsWithQueue,
        Queueable,
        SerializesModels;

    protected $log;
    protected $contact;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Contact $contact, NotificationLog $log) {
        $this->log = $log;
        $this->contact = $contact;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle() {
        $this->log->status = NotificationLog::PROCESS;
        $this->log->reason = $this->attempts() . " out of 3 attempt(s) to send sms to " . $this->contact->mobile_number;
        $this->log->save();
        $sid = env('TWILIO_SID');
        $token = env('TWILIO_TOKEN');
        $client = new Client($sid, $token);
        $sent = $client->messages->create(
                $this->contact->mobile_number, [
            'from' => env('TWILIO_FROM'),
            'body' => $this->contact->auth_code,]
        );
        if ($sent) {
            $this->log->status = NotificationLog::SUCCESS;
            $this->log->reason = "Sms has been sent to " . $this->contact->email;
            $this->log->save();
        } else {
            if ($this->log->status != NotificationLog::ERROR) {
                $this->log->status = NotificationLog::WARNING;
                $this->log->reason = $this->attempts() . " out of 3 failed attempt(s) to send sms from " . env('TWILIO_FROM');
                $this->log->save();
            }
        }
    }

    /**
     * Handle a job failure.
     *
     * @return void
     */
    public function failed() {
        if ($this->log->status != NotificationLog::ERROR) {
            $this->log->status = NotificationLog::ERROR;
            $this->log->reason = "Queue failed to process the job and send out the sms to " . $this->contact->mobile_number;
            $this->log->save();
        }
    }

}
