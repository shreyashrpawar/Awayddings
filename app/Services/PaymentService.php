<?php

namespace App\Services;
use App\Models\Transaction;
use App\Models\BookingPaymentDetail;
use Illuminate\Support\Facades\Log;
use GuzzleHttp\Client;
use App\Services\Env;
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
      $merchantId = 'PGTESTPAYUAT'; // sandbox or test merchantId
$apiKey="099eb0cd-02cf-4e2a-8aca-3e6c6aff0399"; // sandbox or test APIKEY
$redirectUrl = 'payment-success.php';
 
// Set transaction details
$order_id = uniqid(); 
$name="Tutorials Website";
$email="info@tutorialswebsite.com";
$mobile=9999999999;
$amount = 10; // amount in INR
$description = 'Payment for Product/Service';
 
 
$paymentData = array(
    'merchantId' => $merchantId,
    'merchantTransactionId' => "MT7850590068188104", // test transactionID
    "merchantUserId"=>"MUID123",
    'amount' => $amount*100,
    'redirectUrl'=>$redirectUrl,
    'redirectMode'=>"POST",
    'callbackUrl'=>$redirectUrl,
    "merchantOrderId"=>$order_id,
   "mobileNumber"=>$mobile,
   "message"=>$description,
   "email"=>$email,
   "shortName"=>$name,
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
 $request = json_encode(array('request'=>$payloadMain));
                
$curl = curl_init();
curl_setopt_array($curl, [
  CURLOPT_URL => "https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/pay",
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
 
if(isset($res->success) && $res->success=='1'){
$paymentCode=$res->code;
$paymentMsg=$res->message;
$payUrl=$res->data->instrumentResponse->redirectInfo->url;
 
header('Location:'.$payUrl) ;
}
}
  // '500' is amount, 'test01' is merchantId

      //  $MERCHANTID="PGTESTPAYUAT";
      //  $SALTKEY="099eb0cd-02cf-4e2a-8aca-3e6c6aff0399";
      //  $SALTINDEX="1";
      //  $env=Env::UAT;
      //  $SHOULDPUBLISHEVENTS=true;
      
      // $phonePePaymentsClient = new PhonePePaymentClient($MERCHANTID, $SALTKEY, $SALTINDEX, $env, $SHOULDPUBLISHEVENTS);
      
      // $merchantTransactionId = 'PHPSDK' . date("ymdHis") . "payPageTest";
      // $request = PgPayRequestBuilder::builder()
      //     ->mobileNumber("9717498133")
      //     ->callbackUrl("https://webhook.in/test/status")
      //     ->merchantId($MERCHANTID)
      //     ->merchantUserId("MT78505900681881400")
      //     ->amount($amount)
      //     ->merchantTransactionId($merchantTransactionId)
      //     ->redirectUrl("https://webhook.in/test/redirect")
      //     ->redirectMode("REDIRECT")
      //     ->paymentInstrument(InstrumentBuilder::buildPayPageInstrument())
      //     ->build();
      
      // $response = $phonePePaymentsClient->pay($request);
      // $url=$response->getInstrumentResponse()->getRedirectInfo()->getUrl();
      

//       $amount=$amount;
//       $merchantId = 'M22IKYR5IOETV';
//       $redirectUrl = route('payment.callback');
//       $order_id = uniqid(); 
//       $mobile_no='9717498133';

//       $transaction_data = array(
//           'merchantId' => $merchantId,
//           'merchantTransactionId' => "MT78505900681881400",
//           'merchantUserId'=>"MUID189923",
//           'amount' =>  $amount,
//           "instrumentType"=> "MOBILE",
//         "instrumentReference"=> "8296412345",
//         # "message":'Hi, this is Deepak',
//         # "shortName": "sairamit",
//         "storeId"> "store1",
//         "terminalId"=> "terminal1",
//         "expiresIn"=> 3600,
//           'mobileNumber' => $mobile_no,
//           'redirectUrl'=>"$redirectUrl",
//           'redirectMode'=>"POST",
//           'callbackUrl'=>"$redirectUrl",
//          "paymentInstrument"=> array(    
//           "type"=> "PAY_PAGE",
//          )
//       );
    

//       $encode = json_encode($transaction_data);
//       $payloadMain = base64_encode($encode);
     
//       $salt_index = $this->getSaltIndex(); //key index 1
      
//       $payload = $payloadMain . "/pg/v1/pay" . $this->getApiKey();
//       $sha256 = hash("sha256", $payload);

//       $final_x_header = $sha256 . '###' . $salt_index ;
//       $request = json_encode(array('request'=>$payloadMain));
      
//       $curl = curl_init();
      
//      curl_setopt_array($curl, [
// CURLOPT_URL => "https://mercury-t2.phonepe.com/v3/payLink/init",
// CURLOPT_RETURNTRANSFER => true,
// CURLOPT_ENCODING => "",
// CURLOPT_MAXREDIRS => 10,
// CURLOPT_TIMEOUT => 90,
// CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
// CURLOPT_CUSTOMREQUEST => "POST",
//  CURLOPT_POSTFIELDS => $request,
// CURLOPT_HTTPHEADER => [
//   "Content-Type: application/json",
//    "X-VERIFY: " . $final_x_header,
//    "accept: application/json"
// ],
// ]);
       
//        $response = curl_exec($curl);
//        Log::info("resopghjffxfycxfxvvf".$response);

//        $err = curl_error($curl);
//        curl_close($curl);
//        if ($err) {
//           echo "cURL Error #:" . $err;
//           return response()->json(['error' => 'Invalid amount or payment details not found.'], 400);
//         } else {
//    $res = json_decode($response);
   
//    $data = [
//     'amount' => $amount,
//     'transaction_id' => $order_id,
//     'payment_status' => 'PAYMENT_INITIATED',
//     'meta'=>$response,
//     'providerReferenceId'=>'',
//     'merchantOrderId'=>'',
//     'checksum'=>'',
//     'booking_payment_summaries_id'=>$booking_payment_summaries_id,
//     'installment_no'=>$installment_no,
//     'payment_mode'=>'Online'
//   ];
  
//   Transaction::create($data);

//   if(isset($res->code) && ($res->code=='PAYMENT_INITIATED')){
//     $bookingPaymentDetail = BookingPaymentDetail::where('booking_payment_summaries_id', $booking_payment_summaries_id)
//     ->where('installment_no', $installment_no);
  
//   if ($bookingPaymentDetail) {
//        $bookingPaymentDetail->update([
//            'transaction_id' => $order_id,
//            'transaction_status' => 'PAYMENT_INITIATED',
//            'payment_mode'=>'Online',
//            'status'=>'3'
//        ]);
//    }
//    $BookingPaymentDetail=BookingPaymentDetail::where('transaction_id', $order_id);
//    $payUrl=$res->data->instrumentResponse->redirectInfo->url;
//    return  $payUrl ;
//    }else{
//    //HANDLE YOUR ERROR MESSAGE HERE
//    Transaction::where('transaction_id', $order_id)->update(['payment_status'=>'PAYMENT_FAILED']); 
//    $BookingPaymentDetail=BookingPaymentDetail::where('transaction_id', $order_id)->update(['transaction_status'=>'PAYMENT_FAILED','payment_mode'=>'Online','status'=>'2']);
//       dd('ERROR : ' . json_encode($res));
//    }
}

public function handlePaymentCallback($request)
    {
    
      if($request->code == 'PAYMENT_SUCCESS')
  {
   
     $transactionId = $request->transactionId;
     $merchantId=$request->merchantId;
     $providerReferenceId=$request->providerReferenceId;
     $merchantOrderId=$request->merchantOrderId;
     $checksum=$request->checksum;
     $meta = json_encode($request->all());
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
$redirectUrl = 'http://localhost:3000/user/manage-bookings';    
return $redirectUrl;

  }else{
    $transactionId = $request->transactionId;
    Transaction::where('transaction_id', $transactionId)->update(['payment_status'=>'PAYMENT_FAILED']); 
    $BookingPaymentDetail=BookingPaymentDetail::where('transaction_id', $transactionId)->update(['transaction_status'=>'PAYMENT_FAILED','payment_mode'=>'Online','status'=>'2']);
     $transactionId = $request->transactionId;
     $paymnetFailUrl = 'http://localhost:3000/payment/payment-failed?transactionId=' . $transactionId; 
      //HANDLE YOUR ERROR MESSAGE HERE
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

