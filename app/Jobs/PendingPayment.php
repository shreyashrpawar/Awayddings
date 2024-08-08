<?php

namespace App\Jobs;
use Illuminate\Support\Facades\Log;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use App\Services\Env;
use PhonePe\payments\v1\PhonePePaymentClient;
use App\Models\Transaction;
use App\Models\BookingPaymentDetail;
use App\Models\BookingPaymentSummary;
use App\Jobs\SendGenericEmail;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class PendingPayment implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $details;
    public $attempt;
    public $startTime;
    public $email;
    /**
     * @return void
     */
    public function __construct($details,$email,$attempt = 1, $startTime = null)
    {
        $this->details = $details;
        $this->email = $email;
        $this->attempt = $attempt;
        $this->startTime = $startTime ?? now();
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
                    //mail


        $apiKey=env('PHONEPE_SECRET_KEY'); // sandbox or test APIKEY
        $merchantId=$this->details['merchantId'];
        $transactionId=$this->details['transactionId'];
 $SHOULDPUBLISHEVENTS=true;
$phonePePaymentsClient = new PhonePePaymentClient($merchantId, $apiKey, 1, Env::UAT,$SHOULDPUBLISHEVENTS);

    $checkStatus = $phonePePaymentsClient->statusCheck($transactionId);
    $amount=$checkStatus->getAmount();

    if ($checkStatus->getState()=='COMPLETED'){

        //mail
$details = ['email' => $this->email,'mailbtnLink' => 'http://www.test.com', 'mailBtnText' => 'check text',
            'mailTitle' => 'Congrats!', 'mailSubTitle' => 'Hooray! Your booking is confirmed.', 'mailBody' => 'We are happy to inform you that your payment has been successful! Get ready to create some unforgettable memories. All you need to do is show us this email on the day you arrive, and you’ll be good to go!'];
            SendGenericEmail::dispatch($details);

        Transaction::where('transaction_id', $transactionId)->update(['payment_status'=>'PAYMENT_SUCCESS','meta'=>$this->details]); 
        $BookingPaymentDetail=BookingPaymentDetail::where('transaction_id', $transactionId)->update(['transaction_status'=>'PAYMENT_SUCCESS','payment_mode'=>'Online','status'=>'1']);
        $bookingsummaryDetails = BookingPaymentDetail::where('transaction_id', $transactionId)->get();
        $bookingsummaryID= $bookingsummaryDetails->pluck('booking_payment_summaries_id');


        $installmentno= $bookingsummaryDetails->pluck('installment_no');
        $BookingPaymentDetail = BookingPaymentDetail::where('booking_payment_summaries_id', $bookingsummaryID)
        ->where('installment_no', (int)$installmentno + 1)
        ->update(['active_installment' => 1]);
        $Totalamount = BookingPaymentSummary::whereIn('id', $bookingsummaryID)->get(['amount', 'paid']);


        $Xamounts = $Totalamount->pluck('amount');
        $Xpaid = $Totalamount->pluck('paid');
        $totalpaid=(float)$Xpaid[0]+$amount/100;
        $due=(float)$Xamounts[0]-(float)$totalpaid;
        $BookingPaymentSummaries=BookingPaymentSummary::whereIn('id', $bookingsummaryID)->update(['paid'=>$totalpaid,'due'=>$due,'status'=>'1']);        
   return;
    }else{
        //mail
        $details = ['email' => $this->email,'mailbtnLink' => '', 'mailBtnText' => '',
            'mailTitle' => 'Naah!', 'mailSubTitle' => 'Your Payment is Failed.', 'mailBody' => 'We are sad to inform you that your payment has been failed! Get ready to create some unforgettable memories. All you need to do is show us this email on the day you arrive, and you’ll be good to go!'];
            SendGenericEmail::dispatch($details);


        Transaction::where('transaction_id', $transactionId)->update(['payment_status'=>'PAYMENT_FAILED','meta'=>$this->details]); 
        $BookingPaymentDetail=BookingPaymentDetail::where('transaction_id', $transactionId)->update(['transaction_status'=>'PAYMENT_FAILED','payment_mode'=>'Online','status'=>'2']);
    
    }
}
}
