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

        $bookingdetails = BookingPaymentDetail::where('booking_payment_summaries_id',$booking_payment_summaries_id)
        ->where('installment_no', $installment_no)
        ->first();

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
        $redirectUrl= $this->paymentService->handlePaymentCallback($request,$installment_no);  
        
        return new RedirectResponse($redirectUrl);
    }

    

}
