<?php

namespace App\Services;
use App\Http\Controllers\Api\UserController;
use App\Models\Transaction;
use App\Models\BookingPaymentDetail;
use App\Models\BookingPaymentSummary;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use App\Services\Env;
use Illuminate\Support\Facades\Storage;
use App\Jobs\PendingPayment;
use PhonePe\payments\v1\PhonePePaymentClient;
use App\Jobs\SendGenericEmail;
use App\Models\User;
use Illuminate\Support\Facades\Auth;



// use PhonePe\payments\v1\PhonePePaymentClient;
// use PhonePe\payments\v1\models\request\builders\PgPayRequestBuilder;
// use PhonePe\payments\v1\models\request\builders\InstrumentBuilder;


class PaymentService
{

  protected $client;
    protected $merchantId;
    protected $secretKey;
    protected $baseUrl;


public function __construct()
  {
      // Initialize API key and salt index from configuration or environment variables
  }

public function initiatePayment( $amount,$booking_payment_summaries_id,$installment_no)
    {  
      $merchantId = env('PHONEPE_MERCHANT_ID'); // sandbox or test merchantId
$apiKey=env("PHONEPE_SECRET_KEY"); // sandbox or test APIKEY
$redirectUrl = 'http://localhost:8000/api/v1/payment/callback';

// Set transaction details
$order_id = uniqid(); 
$amount = $amount*100; // amount in INR


$paymentData = array(
    'merchantId' => $merchantId,
    'merchantTransactionId' => $order_id, // test transactionID
    "merchantUserId"=>$order_id,
    'amount' => $amount,
    'redirectUrl'=>$redirectUrl,
    'redirectMode'=>"POST",
    'callbackUrl'=>$redirectUrl,
    "merchantOrderId"=>$order_id,
   "paymentInstrument"=> array(    
    "type"=> "PAY_PAGE",
  )
);


 $jsonencode = json_encode($paymentData);
 $payloadMain = base64_encode($jsonencode);
 $salt_index = 1; //key index 1
 $payload = $payloadMain . "/pg/v1/pay" . $apiKey;
 $sha256 = hash("sha256", $payload);
 $final_x_header = $sha256 . '###' . $salt_index;
 Log::info($final_x_header."jksdlf");
 $request = json_encode(array('request'=>$payloadMain));
                
$curl = curl_init();
curl_setopt_array($curl, [
  CURLOPT_URL => env('PHONEPE_BASE_URL'),
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "POST",
   CURLOPT_POSTFIELDS => $request,
  CURLOPT_HTTPHEADER => [
    "Content-Type: application/json",
     "X-VERIFY: " . $final_x_header,
     "accept: application/json"
  ],
]);

$response = curl_exec($curl);
$err = curl_error($curl);

curl_close($curl);

if ($err) {
  echo "cURL Error #:" . $err;
} else {
   $res = json_decode($response);
   $data = [
    'amount' => $amount,
    'transaction_id' => $order_id,
    'payment_status' => 'PAYMENT_INITIATED',
    'meta'=>$response,
    'providerReferenceId'=>'',
    'merchantOrderId'=>'',
    'checksum'=>'',
    'booking_payment_summaries_id'=>$booking_payment_summaries_id,
    'installment_no'=>$installment_no,
    'payment_mode'=>'Online'
  ];
  
  Transaction::create($data);
if(isset($res->code) && ($res->code=='PAYMENT_INITIATED')){
  $bookingPaymentDetail = BookingPaymentDetail::where('booking_payment_summaries_id', $booking_payment_summaries_id)
    ->where('installment_no', $installment_no);
  
  if ($bookingPaymentDetail) {
       $bookingPaymentDetail->update([
           'transaction_id' => $order_id,
           'transaction_status' => 'PAYMENT_INITIATED',
           'payment_mode'=>'Online',
           'status'=>'3'
       ]);
   }
   $BookingPaymentDetail=BookingPaymentDetail::where('transaction_id', $order_id);
   $payUrl=$res->data->instrumentResponse->redirectInfo->url;
   return  $payUrl ;
   }else{
   //HANDLE YOUR ERROR MESSAGE HERE
   Transaction::where('transaction_id', $order_id)->update(['payment_status'=>'PAYMENT_FAILED']); 
   $BookingPaymentDetail=BookingPaymentDetail::where('transaction_id', $order_id)->update(['transaction_status'=>'PAYMENT_FAILED','payment_mode'=>'Online','status'=>'2']);
      dd('ERROR : ' . json_encode($res));
   }
}

}



public function handlePaymentCallback($request)
    {
      $transactionId = $request->transactionId;
      $installment_no = $request->installment_no;
      $amount = $request->amount;
      $merchantId=$request->merchantId;
      $providerReferenceId=$request->providerReferenceId;
      $merchantOrderId=$request->merchantOrderId;
      $checksum=$request->checksum;
      Log::info($request->all());
      $meta = json_encode($request->all());
      $apiKey=env('PHONEPE_SECRET_KEY'); // sandbox or test APIKEY
        $merchantId=env('PHONEPE_MERCHANT_ID');
        $transactionId=$transactionId;
 $SHOULDPUBLISHEVENTS=true;
$phonePePaymentsClient = new PhonePePaymentClient($merchantId, $apiKey, 1, Env::UAT,$SHOULDPUBLISHEVENTS);

    $checkStatus = $phonePePaymentsClient->statusCheck($transactionId);
    $bookingsummaryDetails = BookingPaymentDetail::where('transaction_id', $transactionId)->get();
$bookingsummaryID= $bookingsummaryDetails->pluck('booking_payment_summaries_id');
    $Totalamount = BookingPaymentSummary::whereIn('id', $bookingsummaryID)->get(['amount', 'paid','user_id']);
$userID = $Totalamount->pluck('user_id');
$userdetails=User::whereIn('id', $userID)->get(['email']);
$useremail = $userdetails->pluck('email');

      if($checkStatus->getState()=='COMPLETED')
  {
    $installmentno= $bookingsummaryDetails->pluck('installment_no');
    $in = $installmentno[0]+1;
    $BookingPaymentDetail = BookingPaymentDetail::where('booking_payment_summaries_id', $bookingsummaryID)
    ->where('installment_no', $in)
    ->update(['active_installment' => 1]);
    // $details = ['email' => $userEmail,'mailbtnLink' => '', 'mailBtnText' => '',
    // 'mailTitle' => 'Congrats!', 'mailSubTitle' => 'Hooray! Your booking is confirmed.', 'mailBody' => 'We are happy to inform you that your payment has been successful! Get ready to create some unforgettable memories. All you need to do is show us this email on the day you arrive, and you’ll be good to go!'];
    // SendCongratsEmail::dispatch($details);
    $details = ['email' => $useremail,'mailbtnLink' => 'http://www.test.com', 'mailBtnText' => 'Click here',
    'mailTitle' => 'Congrats!', 'mailSubTitle' => 'Hooray! Your booking is confirmed.', 'mailBody' => 'We are happy to inform you that your payment has been successful! Get ready to create some unforgettable memories. All you need to do is show us this email on the day you arrive, and you’ll be good to go!'];
    SendGenericEmail::dispatch($details);
     $data = [
      'providerReferenceId' => $providerReferenceId,
      'checksum' => $checksum,
      'meta'=>$meta,
      'payment_mode'=>'Online',
      'payment_status'=>'PAYMENT_SUCCESS'
     ];
if($merchantOrderId !=''){
   $data['merchantOrderId']=$merchantOrderId;
}
Transaction::where('transaction_id', $transactionId)->update($data); 
$BookingPaymentDetail=BookingPaymentDetail::where('transaction_id', $transactionId)->update(['transaction_status'=>'PAYMENT_SUCCESS','payment_mode'=>'Online','status'=>'1']);
// $bookingsummaryDetails = BookingPaymentDetail::where('transaction_id', $transactionId)->get();
// $bookingsummaryID= $bookingsummaryDetails->pluck('booking_payment_summaries_id');
// Log::info($bookingsummaryID.'booking summart');
// $Totalamount = BookingPaymentSummary::whereIn('id', $bookingsummaryID)->get(['amount', 'paid','user_id']);
// $userID = $Totalamount->pluck('user_id');
$Xamounts = $Totalamount->pluck('amount');
$Xpaid = $Totalamount->pluck('paid');
Log::info($Xamounts[0].'Xamounts');
Log::info($Xpaid[0].'Xpaid');
Log::info($Totalamount.'taolamount');
$totalpaid=(float)$Xpaid[0]+$amount/100;
$due=(float)$Xamounts[0]-(float)$totalpaid;
$BookingPaymentSummaries=BookingPaymentSummary::whereIn('id', $bookingsummaryID)->update(['paid'=>$totalpaid,'due'=>$due,'status'=>'1']);

$redirectUrl = 'http://localhost:3000/user/manage-bookings';    
return $redirectUrl;

  }else if($checkStatus->getState()=='FAILED'){
    $details = ['email' =>$useremail,'mailbtnLink' => 'http://www.test.com', 'mailBtnText' => 'click here',
    'mailTitle' => 'Naah!', 'mailSubTitle' => 'Your Payment is Failed.', 'mailBody' => 'We are sad to inform you that your payment has been failed! Get ready to create some unforgettable memories. All you need to do is show us this email on the day you arrive, and you’ll be good to go!'];
    SendGenericEmail::dispatch($details);
    $transactionId = $request->transactionId;
    $request["code"]="PAYMENT_FAILED";
    $meta = json_encode($request->all());
    Transaction::where('transaction_id', $transactionId)->update(['payment_status'=>'PAYMENT_FAILED','meta'=>$meta]); 
    $BookingPaymentDetail=BookingPaymentDetail::where('transaction_id', $transactionId)->update(['transaction_status'=>'PAYMENT_FAILED','payment_mode'=>'Online','status'=>'2']);
     $transactionId = $request->transactionId;
     $paymnetFailUrl = 'http://localhost:3000/payment/payment-failed?transactionId=' . $transactionId.'&merchantId'.$merchantId; 
      //HANDLE YOUR ERROR MESSAGE HERE
      return  $paymnetFailUrl;
  }else if($checkStatus->getState()=='PENDING'){
    // code for pending payment
    $transactionId = $request->transactionId;
    $request["code"]="pending";
    $meta = json_encode($request->all());
    Transaction::where('transaction_id', $transactionId)->update(['payment_status'=>'pending','meta'=>$meta]); 
    $BookingPaymentDetail=BookingPaymentDetail::where('transaction_id', $transactionId)->update(['transaction_status'=>'pending','payment_mode'=>'Online','status'=>'0']);
    $pendingdetails=['transactionId' => $transactionId,'merchantId' => $merchantId];
    $details = ['email' => $useremail,'mailbtnLink' => '', 'mailBtnText' => '',
'mailTitle' => 'Sorry!', 'mailSubTitle' => 'Wait! Your payment is pending.', 'mailBody' => 'We are sorry to inform you that your payment is in pending right now! You need to wait until your payment becomes successful. We will shorly inform you the status of your payment!'];
SendGenericEmail::dispatch($details);
    PendingPayment::dispatch($pendingdetails,$useremail)->delay(now()->addMinutes(20));
     $paymnetFailUrl = 'http://localhost:3000/payment/payment-pending?transactionId=' . $transactionId;
      return  $paymnetFailUrl;
  }
  }

private function getApiKey()
  {
      return config('services.phonepe.api_key');
  }
private function getSaltIndex()
  {
      return config('services.phonepe.salt_index');
  }
}

