<?php

namespace App\Services\Booking;

use App\DataTransferObjects\Booking\PreBookingDetailDto;
use App\Models\PreBookingDetails;

trait BookingDetailService
{
    public function createDetails(array $details): void
    {
        $preBookingDetailData = [];
        foreach ($details as $dto) {
            $preBookingDetailData[] = $this->processDetailsData($dto);
        }
        PreBookingDetails::insert($preBookingDetailData);
    }

    private function processDetailsData(PreBookingDetailDto $dto): array
    {
        return [
            'hotel_chargable_type_id' => $dto->hotelChargeableId,
            'qty' => $dto->quantity,
            'rate' => $dto->rate,
            'date' => $dto->date->format('Y-m-d'),
            'threshold' => $dto->threshold,
            'pre_booking_summaries_id' => $dto->preBookingSummaryId,
        ];
    }
}
