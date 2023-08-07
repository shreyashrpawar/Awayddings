<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\EmailforEmiPayments;
use Mail;

class SendEmailEmiPayments implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $details;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($details)
    {
        $this->details = $details;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // $email = new EmailforEmiPayments($this->details['email'],$this->details['name'],$this->details['phone'],$this->details['amount'],"","");
        // Mail::to($this->details['email'])->send($email);

        $email = new EmailforEmiPayments($this->details['email'],$this->details['mailSubTitle'], $this->details['mailbtnLink'],$this->details['mailBtnText'],
        $this->details['mailTitle'],$this->details['mailBody']);
        // dd($this->details['email']);

        Mail::to($this->details['email'])->send($email);
    }
}
