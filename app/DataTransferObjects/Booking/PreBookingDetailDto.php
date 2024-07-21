<?php

namespace App\DataTransferObjects\Booking;

use Carbon\Carbon;
use DateTime;

class PreBookingDetailDto
{
    public function __construct(
        public readonly int      $preBookingSummaryId,
        public readonly DateTime $date,
        public readonly int      $hotelChargeableId,
        public readonly float    $rate,
        public readonly int      $quantity,
        public readonly float    $threshold
    )
    {
    }

    public static function fromArray(int $summaryId, array $details): PreBookingDetailDto
    {
        return new self(
            preBookingSummaryId: $summaryId,
            date: Carbon::parse($details['date']),
            hotelChargeableId: $details['chargable_type_id'],
            rate: $details['rate'],
            quantity: $details['selectedQty'],
            threshold: $details['percentage_occupancy']
        );
    }
}
