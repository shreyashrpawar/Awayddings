<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Models\EventPreBookingSummary;
use Storage;

class GenerateEventSummaryPdfJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $basicDetails;
    protected $groupedPdfData;
    protected $facilityData;
    protected $pdfData;

    /**
     * Create a new job instance.
     *
     * @param array $basicDetails
     * @param array $groupedPdfData
     * @param array $facilityData
     * @param array $pdfData
     */
    public function __construct($basicDetails, $groupedPdfData, $facilityData, $pdfData)
    {
        $this->basicDetails = $basicDetails;
        $this->groupedPdfData = $groupedPdfData;
        $this->facilityData = $facilityData;
        $this->pdfData = $pdfData;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $basicDetails = $this->basicDetails;
        $groupedPdfData = $this->groupedPdfData;
        $facilityData = $this->facilityData;
        $pdfData = $this->pdfData;
        $additional_data = array_merge($facilityData,$pdfData);
        
        // Create a Dompdf instance
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);

        // Generate the PDF content using a Blade view
        $pdfView = view('pdf.myPDF', compact('basicDetails', 'groupedPdfData', 'facilityData', 'pdfData', 'additional_data'));
        $pdfHtml = $pdfView->render();

        // Load the HTML content into Dompdf
        $dompdf->loadHtml($pdfHtml);
        $dompdf->setPaper('A4', 'portrait');

        // Render the PDF
        $dompdf->render();

        // Save the PDF to a temporary local path
        // $tempFilePath = storage_path('app/temp/summary.pdf');
        $pdfFileName = 'prebooking_' . $basicDetails['prebooking_id'] . '.pdf';
        $pdfFilePath = 'pdf/' . $pdfFileName;
        $pdfContent = $dompdf->output();

        Storage::disk('s3')->put($pdfFilePath, $pdfContent, 'public');
        $pdfLink=Storage::disk('s3')->url($pdfFilePath);

        $invoice = EventPreBookingSummary::updateOrCreate([
            'id'   => $basicDetails['prebooking_id']
        ],[
            'pdf_url' => $pdfLink            
        ]);

        // Delete the temporary local PDF file
        // unlink($tempFilePath);
        
    }
}
