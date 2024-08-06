<?php

namespace App\Http\Controllers\Api;
use App\Services\PaymentService;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Models\BookingPaymentDetail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\BookingPaymentSummary;



class PaymentController extends Controller
{
    protected $paymentService;
    public function __construct(PaymentService $paymentService)
    {
        $this->paymentService = $paymentService;
    }
    public function makePayment(Request $request)
    
    {   
        $user = auth()->user();
        $user_id = $user->id;
        Log::info($user_id);
        $amount_response= $request->input('amount');
        
        $amount_formatd = number_format($request->input('amount'),2);
        $amount_trimd = str_replace(',', '', $amount_formatd);
        $amount = intval(floatval( $amount_trimd));
       
        $booking_payment_summaries_id=$request->input('booking_payment_summaries_id');
        $installment_no=$request->input('installment_no');

        $paymentDetails = BookingPaymentDetail::where('booking_payment_summaries_id',$booking_payment_summaries_id)
        ->where('installment_no', $installment_no)
        ->first();
               
        $booking_payment_summaries_id=$request->input('booking_payment_summaries_id');
        $installment_no=$request->input('installment_no');
        $BookingPaymentSummaries=BookingPaymentSummary::where('id', $booking_payment_summaries_id)->update(['user_id'=>$user_id]);
        $bookingdetails = BookingPaymentDetail::where('booking_payment_summaries_id',$booking_payment_summaries_id)
        ->where('installment_no', $installment_no)
        ->first();
//         $merchantId = 'PGTESTPAYUAT86'; // sandbox or test merchantId
// $apiKey="96434309-7796-489d-8924-ab56988a6076"; // sandbox or test APIKEY
// $redirectUrl = 'http://localhost:8000/api/v1/payment/callback';

// // Set transaction details
// $order_id = uniqid(); 
// $name="testing";
// $email="testing@gmail.com";
// $mobile=9999999999;
// $amount = $amount; // amount in INR
// $description = 'Payment for Product/Service';


// $paymentData = array(
//     'merchantId' => $merchantId,
//     'merchantTransactionId' => "MT7850590068188104", // test transactionID
//     "merchantUserId"=>"MUID123",
//     'amount' => $amount*100,
//     'param1'=>$installment_no,
//     'redirectUrl'=>$redirectUrl,
//     'redirectMode'=>"POST",
//     'callbackUrl'=>$redirectUrl,
//     "merchantOrderId"=>$order_id,
//    "mobileNumber"=>$mobile,
//    "message"=>$description,
//    "email"=>$email,
//    "shortName"=>$name,
//    "paymentInstrument"=> array(    
//     "type"=> "PAY_PAGE",
//   )
// );


//  $jsonencode = json_encode($paymentData);
//  $payloadMain = base64_encode($jsonencode);
//  $salt_index = 1; //key index 1
//  $payload = $payloadMain . "/pg/v1/pay" . $apiKey;
//  $sha256 = hash("sha256", $payload);
//  $final_x_header = $sha256 . '###' . $salt_index;
//  $request = json_encode(array('request'=>$payloadMain));
                
// $curl = curl_init();
// curl_setopt_array($curl, [
//   CURLOPT_URL => "https://api-preprod.phonepe.com/apis/pg-sandbox/pg/v1/pay",
//   CURLOPT_RETURNTRANSFER => true,
//   CURLOPT_ENCODING => "",
//   CURLOPT_MAXREDIRS => 10,
//   CURLOPT_TIMEOUT => 30,
//   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//   CURLOPT_CUSTOMREQUEST => "POST",
//    CURLOPT_POSTFIELDS => $request,
//   CURLOPT_HTTPHEADER => [
//     "Content-Type: application/json",
//      "X-VERIFY: " . $final_x_header,
//      "accept: application/json"
//   ],
// ]);

// $response = curl_exec($curl);
// $err = curl_error($curl);
// Log::info($response);

// curl_close($curl);

// if ($err) {
//   echo "cURL Error #:" . $err;
// } else {
//    $res = json_decode($response);
 
// if(isset($res->success) && $res->success=='1'){
// $paymentCode=$res->code;
// $paymentMsg=$res->message;
// $payUrl=$res->data->instrumentResponse->redirectInfo->url;
// return $payUrl;
// }}
       
        if ($paymentDetails && $paymentDetails->amount == $amount_response) {
            $paymentUrl = $this->paymentService->initiatePayment($amount,$booking_payment_summaries_id, $installment_no);
            return $paymentUrl;
        } else {
            // Amount does not match or payment details not found, return error message
            return response()->json(['error' => '.'], 400);
        }
    
       
} 
          
        // Return the response from the payment service

    public function paymentCallback(Request $request)
    
    {        $installment_no=$request->input('installment_no');
        // $param1=$request->input('param1');
        $redirectUrl= $this->paymentService->handlePaymentCallback($request,$installment_no);  
        
        return new RedirectResponse($redirectUrl);
    }

    

}
