<?php

namespace App\Http\Controllers\Api;
use Illuminate\Support\Facades\Log;

use App\DataTransferObjects\Booking\PreBookingSummaryDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Booking\CreatePreBookingRequest;
use App\Models\BookingSummary;
use App\Models\PreBookingSummary;
use App\Models\PreBookingSummaryStatus;
use App\Services\Booking\BookingService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PreBookingController extends Controller
{
    public function __construct(
        protected BookingService $service
    )
    {
    }

    public function submit(CreatePreBookingRequest $request): JsonResponse
    {
        $user = auth()->user();
        $this->service->create(PreBookingSummaryDto::fromApiRequest($user->id, $request));

        return response()->json([
            'success' => true,
            'message' => 'Successfully Saved',
        ]);
    }

    //TODO: Can be further optimized with better query, gather what info is to be shown at frontend and rewrite the func
    public function index(Request $request): JsonResponse
    {
        $user = auth()->user();
        $user_id = $user->id;

        $approved_summary = BookingSummary::with(['user', 'booking_details', 'booking_details.hotel_chargable_type', 'property', 'booking_payment_summary', 'booking_payment_summary.booking_payment_details'])
            ->where('user_id', $user_id)->orderBy('created_at', 'desc')->get();

        $getStatuses = PreBookingSummaryStatus::getIdsByStatus([
            PreBookingSummary::STATUS_CANCELED,
            PreBookingSummary::STATUS_REJECTED,
            PreBookingSummary::STATUS_PENDING
        ]);

        $preBookingSummaries = PreBookingSummary::with(
            [
                'user',
                'pre_booking_summary_status',
                'property',
                'pre_booking_details',
                'pre_booking_details.hotel_chargable_type'
            ]
        )
            ->where('user_id', $user_id)
            ->whereIn('pre_booking_summary_status_id', $getStatuses)
            ->orderBy('created_at', 'desc')
            ->get();

        $groupedSummaries = $preBookingSummaries->groupBy(function ($item) {
            $statusName = optional($item->pre_booking_summary_status)->name;
            if ($statusName === PreBookingSummary::STATUS_CANCELED or $statusName === PreBookingSummary::STATUS_REJECTED) {
                return PreBookingSummary::STATUS_CANCELED."_".PreBookingSummary::STATUS_REJECTED;
            }
            return $statusName;
        });

        return response()->json([
            'success' => true,
            'message' => 'Success',
            'data' => [
                'pending' => $groupedSummaries->get(PreBookingSummary::STATUS_PENDING),
                'cancelled' => $groupedSummaries->get(PreBookingSummary::STATUS_CANCELED."_".PreBookingSummary::STATUS_REJECTED),
                'approved' => $approved_summary,
                'completed' => [],
            ],
        ]);
    }
}