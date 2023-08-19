<?php

namespace App\Http\Controllers;

use App\Jobs\SendInstallmentEmail;
use App\Jobs\SendCongratsEmail;
use App\Jobs\SendEmailToHotel;
use App\Models\EventBookingPaymentDetail;
use App\Models\EventBookingPaymentSummary;
use App\Models\EventBookingSummary;
use App\Models\EventPreBookingSummary;
use Illuminate\Http\Request;
use PDF;
use Storage;
use App\Jobs\EventGeneratePDF;
use App\Models\UserVendorAlignment;
use App\Models\VendorPropertyAlignment;
use App\Models\User;
use App\Models\Property;
use App\Models\Vendor;

class EventBookingSummaryController extends Controller
{
    public function index(Request  $request)
    {
        $user = $request->user();
        $roles = $user->getRoleNames();
        
        $q = EventBookingSummary::orderBy('id', 'DESC');
        if (in_array("vendor", $roles->toArray())){
            $userVendor = UserVendorAlignment::where('user_id',$user->id)->first();
            $vendor_id =  $userVendor->vendor_id;
            $property_id =  VendorPropertyAlignment::where('vendor_id',$vendor_id)->pluck('property_id')->all();
            $q->whereIn('property_id',$property_id);
        }

        $bookings = $q->get();
        return view('app.event_bookings.index', compact('bookings'));
    }

    public function show($id)
    {
        $bookings = EventBookingSummary::find($id);
        // dd($bookings);

        return view('app.event_bookings.show', compact('bookings'));
    }

    public function update(Request $request, EventBookingSummary $bookingSummary)
    {
        $bookingPaymentDetail = EventBookingPaymentDetail::where('id', $request->booking_payment_details_id)->where('installment_no', $request->installment_no)->first();
        if ($bookingPaymentDetail) {
            if ($bookingPaymentDetail->status == '1') {
                $bookingPaymentSummary = EventBookingPaymentSummary::where('id', $bookingPaymentDetail->em_booking_payment_summaries_id)->first();
        
                $total_paid = $bookingPaymentSummary->paid + round($bookingPaymentDetail->amount, 2);
                $total_due = round($bookingPaymentSummary->amount, 2) - $total_paid;

                $bookingPaymentSummary->paid = $total_paid;
                $bookingPaymentSummary->due = round($total_due, 2);
                $bookingPaymentSummary->save();

                $bookingPaymentDetail->status = 2;

                $remarks = $request->remarks;
                $next_installment_date = $request->next_installment_date;
                $installment_no = $request->installment_no;

                $installment_amount = $bookingPaymentDetail->amount;
                $mailDetails = ['email' => $request->user_email, 'installment_no' => $installment_no, 'total_paid' => $total_paid, 'total_due' => $total_due, 'installment_amount' => $installment_amount, 'next_installment_date' => $next_installment_date, 'remarks' => $remarks];

                if($installment_no == 1) {
                    // SendCongratsEmail::dispatch($details);
                    $details = ['email' => $request->user_email,'mailbtnLink' => '', 'mailBtnText' => '',
                    'mailTitle' => 'Congrats!', 'mailSubTitle' => 'Hooray! Your booking is confirmed.', 'mailBody' => 'We are happy to inform you that your booking is confirmed! Get ready to create some unforgettable memories. All you need to do is show us this email on the day you arrive, and you’ll be good to go!'];
                    SendCongratsEmail::dispatch($details);

                    $bookings = EventBookingSummary::find($bookingPaymentSummary->em_booking_summaries_id);
                    $user = User::find($bookings->user_id);
                    $property_id =  VendorPropertyAlignment::where('property_id',$bookings->property_id)->pluck('vendor_id')->first();
                    if ($property_id) { 
                        $vendor_email = Vendor::where('id', $property_id)->pluck('email')->first();
                        $emailDetails = ['vendor_email' => $vendor_email,'email' => $request->user_email, 'name' => $user->name, 'phone' => $user->phone, 'check_in' => $bookings->check_in, 'check_out' => $bookings->check_out, 'adult' => $bookings->pax,'booking_id' => $bookings->id];
                        SendEmailToHotel::dispatch($emailDetails);
                    }
                }

                if ($next_installment_date == null) {
                    $bookings = EventBookingSummary::find($bookingPaymentSummary->em_booking_summaries_id);
                    $details = ['data' => $bookings,'mailData' => $mailDetails];
                    EventGeneratePDF::dispatch($details);
                    //$pdf = $this->generateInvoicePDF($bookingPaymentSummary->booking_summaries_id);
                }else{
                    $this->installmentMail($mailDetails);
                }
            }
            $bookingPaymentDetail->payment_mode = $request->payment_mode;
            $bookingPaymentDetail->remarks = $request->remarks;
            $bookingPaymentDetail->save();

            return back()->with('success', 'Status updated successfully.');
        } else {
            return back()->with('error', 'Installment not found.');
        }
    }

    private function installmentMail($details)
    {
        SendInstallmentEmail::dispatch($details);
        return true;
    }

    public function generatePDF()
    {
        $summary = EventPreBookingSummary::with([
            'user',
            'property',
            'event_pre_booking_details',
            'pre_booking_summary_status',
            'event_pre_booking_details.artistPerson',
            'event_pre_booking_addson_details',
            'event_pre_booking_addson_artist_person',
        ])->find(14);

        // foreach ($summary->event_pre_booking_details as $val) {
        //     $particular = '';
        //     $image_url = '';
        //     $data_name = '';
        //     $amount = 0;
        //     if ($val->artistPerson) {
        //         $image_url = ($val->artistPerson->image ? asset('storage/' . $val->artistPerson->image->url) : null );
                
        //         $particular = 'Artist Person - '.$val->artistPerson->name;
        //         $amount = $val->artist_amount;
        //         $image_url = $val->artistPerson;
        //         $data_name = 'artistPerson';
        //     } elseif ($val->decoration) {
        //         $image_url = ($val->decoration->image ? asset('storage/' . $val->decoration->image->url) : null );
        //         $particular = 'Decoration - '.$val->decoration->name;
        //         $amount = $val->decor_amount;
        //         $data_name = 'decor';
        //     }
        //     $event = $val->events->name;
            
        //     $pdfData[] = [
        //         'title' => 'Welcome to Awayddings',
        //         'date' => date('m/d/Y'),
        //         'id' => $val->id,
        //         'event' => $event,
        //         'date' => $val->date->format('d-m-Y'),
        //         'time' => $val->start_time . ' - ' . $val->end_time,
        //         'particular' => $particular,
        //         'data-name' => $data_name,
        //         'amount' => $amount,
        //         'image_url' => $image_url,
        //         // Add other relevant fields here
        //     ];
        // }
        foreach($summary->event_pre_booking_addson_artist_person as $key => $val) {
            // dd($val);
            $particular = '';
            $image_url = '';
            $data_name = '';
            $amount = $val->addson_artist_amount;
            if ($val->addson_artist_person) {
                $particular = 'Additional Artist Person - '.$val->addson_artist_person->name;
                $image_url = ($val->addson_artist_person->image ? asset('storage/' . $val->addson_artist_person->image->url) : null );
                $data_name = 'additionalArtistPerson';
            }
            $artistParticular = '';
    
            if ($val->addson_artist) {
                $artistParticular = 'Additional Artist - '.$val->addson_artist->name;
                $image_url = ($val->addson_artist->image ? $val->addson_artist->image->url : null );
                $data_name = 'additionalArtist';
            }
            
            $pdfData[] = [
                'title' => 'Welcome to Awayddings',
                'id' => $val->id,
                'image_url' => $image_url,
                // Add other relevant fields here
            ];
            
        }

        $pdf = PDF::loadView('PDF.myPDF', ['pdfData' => $pdfData]);
        return view('pdf.pdf_viewer', ['pdfContent' => $pdf->output()]);
    
        // return $pdf->download('itsolutionstuff.pdf');
    }
}
