<?php

namespace App\Jobs;

use App\Models\Contact;
use App\Models\NotificationLog;
use Illuminate\Contracts\Mail\Mailer;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class SendEmail implements ShouldQueue {

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
    public function handle(Mailer $mailer) {       
        $this->log->status = NotificationLog::PROCESS;
        $this->log->reason = $this->attempts() . " out of 3 attempt(s) to send e-mail to " . $this->contact->email;
        $this->log->save();        
        $body = "What ever text we think of sending here";
        $sent = $mailer->send('emails.contact', ['body' => $body, 'contact' => $this->contact], function ($message) {
            // Update from address here? TODO: set from address in .env file
            $message->from('leoramsy@gmail.com', "National Debt Advisors");
            $message->to($this->contact->email);                                
            $message->subject("Opt-in to Marketing Communication");            
        });
        if ($sent) {
            $this->log->status = NotificationLog::SUCCESS;
            $this->log->reason = "E-mail has been sent to " . $this->contact->email;
            $this->log->save();
        } else {
            if ($this->log->status != NotificationLog::ERROR) {
                $this->log->status = NotificationLog::WARNING;
                $this->log->reason = $this->attempts() . " out of 3 failed attempt(s) to send e-mail from " . $this->contact->email;
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
            $this->log->reason = "Queue failed to process the job and send out the e-mail to " . $this->contact->email;
            $this->log->save();
        }
    }

}
