<?php

namespace App\Jobs;

use App\Mail\installmentEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Mail;

class SendInstallmentEmail implements ShouldQueue
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
        $email = new installmentEmail($this->details['installment_no'],$this->details['total_paid'],$this->details['total_due'],$this->details['installment_amount'],$this->details['next_installment_date'],$this->details['remarks'],"","");
        Mail::to($this->details['email'])->send($email);
    }
}
