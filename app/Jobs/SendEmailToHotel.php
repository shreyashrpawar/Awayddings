<?php

namespace App\Jobs;

use App\Mail\EmailToHotel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;

class SendEmailToHotel implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $details;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($emailDetails)
    {
        $this->details = $emailDetails;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $email = new EmailToHotel($this->details['email'],$this->details['name'],$this->details['phone'],$this->details['adult'],$this->details['check_in'],$this->details['check_out'],"","");
        Mail::to($this->details['email'])->send($email);
    }
}
