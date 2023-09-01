<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use PDF;
use Storage;
use App\Models\EventPreBookingSummary;

class generateEventSummaryPdf implements ShouldQueue
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
        $summary = EventPreBookingSummary::with([
                'user',
                'property',
                'event_pre_booking_details',
                'pre_booking_summary_status',
                'event_pre_booking_details.artistPerson',
                'event_pre_booking_addson_details',
                'event_pre_booking_addson_artist_person',
            ])->find($this->details['data']);
        // $summary = $this->details['data'];
        $basicDetails = [
            'title' => 'Welcome to Awayddings',
            'prebooking_id' => $summary->id,
            'user_name' => $summary->user->name,
            'user_phone' => $summary->user->phone,
            'property_name' => $summary->property->name,
            'adult' => $summary->pax,
            'duration' => $summary->check_in->format('d-m-Y') . ' - ' . $summary->check_out->format('d-m-Y'),
            'amount' => $summary->total_amount,
        ];

        $groupedPdfData = [];
        $pdfData = [];
        $facilityData = [];
        $dateWiseEventCounts = [];

        foreach ($summary->event_pre_booking_details as $val) {

            $decor = '';
            $artist = '';
            $artist_image_url = '';
            $decor_image_url = '';
            $artist_amount = 0;
            $decor_amount = 0;

            if ($val->artistPerson) {
                $artist_image_url = ($val->artistPerson->image ?  $val->artistPerson->image->url : null);
                $artist =  $val->artistPerson->name;
                $artist_amount = $val->artist_amount;
            } elseif ($val->decoration) {
                $decor_image_url = ($val->decoration->image ? $val->decoration->image->url : null);
                $decor =  $val->decoration->name;
                $decor_amount = $val->decor_amount;
            }
            // dd($decor_amount);

            $groupedPdfData[$val->date][] = [
                'details_id' => $val->id,
                'event' => $val->events->name,
                'date' => $val->date,
                'time' => $val->start_time . ' - ' . $val->end_time,
                'artist' => $artist,
                'decor' => $decor,
                'artist_amount' => $artist_amount,
                'decor_amount' => $decor_amount,
                'start_time' => $val->start_time,
                'end_time' => $val->end_time,
                'decor_image_url' => $decor_image_url,
                'artist_image_url' => $artist_image_url,
            ];
        }

        foreach($summary->event_pre_booking_addson_details as $key => $val) {
            $particular = '';
            $image_url = '';
            $data_name = '';
            $amount = $val->total_amount;
            $data_name = 'facility';
            $image_url = ($val->addson_facility_details->image ? $val->addson_facility_details->image->url : null);
            // elseif ($val->addson_artist_person) {
            //     $particular = $val->addson_artist_person->name;
            //     $amount = $val->artist_amount;
            // }
            // dd($val->addson_facility_details);
            
            $facilityData[] = [
                'facility_id' => $val->id,
                'facility' => $val->addson_facility->name,
                'facility_description' => $val->addson_facility_details->description,
                'amount' => $val->addson_facility_details->price,
                'facility_image_url' => $image_url,
                // Add other relevant fields here
            ];
            
        }

        foreach($summary->event_pre_booking_addson_artist_person as $key => $val) {
            // dd($val);
            $artist_person = '';
            $artist = '';
            $image_url = '';
            $data_name = '';
            $amount = $val->addson_artist_amount;
            if ($val->addson_artist_person) {
                $artist_person = $val->addson_artist_person->name;
                $artist_person_image_url = ($val->addson_artist_person->image ? $val->addson_artist_person->image->url : null );
                $data_name = 'additionalArtistPerson';
            }
            $artistParticular = '';
    
            if ($val->addson_artist) {
                $artist = $val->addson_artist->name;
                $artist_image_url = ($val->addson_artist->image ? $val->addson_artist->image->url : null );
                $data_name = 'additionalArtist';
            }
            
            $pdfData[] = [
                'additional_id' => $val->id,
                'artist_person' => $artist_person,
                'artist' => $artist,
                'amount' => $amount,
                'artist_person_image_url' => $artist_person_image_url,
                'artist_image_url' => $artist_image_url,
                    ];
            
        }
        $additional_data = array_merge($facilityData,$pdfData);
        // dd($additional_data);

        $pdf = PDF::loadView('PDF.myPDF', [
            'basicDetails' => $basicDetails,
            'additional_data' => $additional_data,
            'groupedPdfData' => $groupedPdfData
        ]);
        //dd($bookings);
        // $pdf = PDF::loadView('pdf.event-customer-invoice', compact('bookings'));
        //Storage::put('public/pdf/customer_invoice_' . $bookings->id . '.pdf', $pdf->output());
        $pdfFileName = 'prebooking_' . $summary->id . '.pdf';
        $pdfFilePath = 'pdf/' . $pdfFileName; // The path in the S3 bucket
        $pdfContent = $pdf->output();

        Storage::disk('s3')->put($pdfFilePath, $pdfContent, 'public');
        $pdfLink=Storage::disk('s3')->url($pdfFilePath);

        //  $pre_booking_summary->pdf_url = Storage::disk('s3')->url($pdfFilePath);
        //  $pre_booking_summary->save();

        $invoice = EventPreBookingSummary::updateOrCreate([
            'id'   => $summary->id
        ],[
            'pdf_url' => $pdfLink            
        ]);
    }
}
