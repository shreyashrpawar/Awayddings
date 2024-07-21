<?php

namespace App\Services\Booking;

use App\DataTransferObjects\Booking\PreBookingDetailDto;
use App\DataTransferObjects\Booking\PreBookingSummaryDto;
use App\Models\PreBookingSummary;
use App\Models\PreBookingSummaryStatus as SummaryStatus;
use Illuminate\Support\Facades\DB;

class BookingService
{
    use BookingDetailService;

    public function create(PreBookingSummaryDto $dto): PreBookingSummary
    {
        return DB::transaction(function () use ($dto) {
            $preBookingSummary = $this->processPreBookingData(
                (new PreBookingSummary()),
                $dto,
                SummaryStatus::getIdByStatus(PreBookingSummary::STATUS_PENDING)
            );
            $preBookingSummary->save();
            $preBookingDetailsData = collect([]);
            foreach ($dto->details as $preBookingDetails) {
                foreach ($preBookingDetails['data'] as $detail) {
                    if ($detail['selectedQty'] > 0) {
                        $preBookingDetailsData->push(PreBookingDetailDto::fromArray($preBookingSummary->id, $detail));
                    }
                }
            }
            $this->createDetails($preBookingDetailsData->toArray());
            return $preBookingSummary;
        });
    }

    private function processPreBookingData(PreBookingSummary $preBookingSummary, PreBookingSummaryDto $dto, int $statusId): PreBookingSummary
    {
        $preBookingSummary->user_id = $dto->userId;
        $preBookingSummary->budget = $dto->budget;
        $preBookingSummary->check_in = $dto->checkInDate->format('Y-m-d');
        $preBookingSummary->check_out = $dto->checkOutDate->format('Y-m-d');
        $preBookingSummary->total_amount = $dto->totalAmount;
        $preBookingSummary->pax = $dto->pax;
        $preBookingSummary->property_id = $dto->propertyId;
        $preBookingSummary->user_remarks = $dto->userRemarks;
        $preBookingSummary->status = $dto->status;
        $preBookingSummary->pre_booking_summary_status_id = $statusId;
        $preBookingSummary->bride_name = $dto->brideName;
        $preBookingSummary->groom_name = $dto->groomName;
        return $preBookingSummary;
    }
}
