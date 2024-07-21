<?php

namespace App\DataTransferObjects\Booking;

use App\Http\Requests\Api\Booking\CreatePreBookingRequest;
use Carbon\Carbon;
use DateTime;

class PreBookingSummaryDto
{
    public function __construct(
        public readonly int      $userId,
        public readonly int      $propertyId,
        public readonly DateTime $checkInDate,
        public readonly DateTime $checkOutDate,
        public readonly float    $totalAmount,
        public readonly float    $budget,
        public readonly ?string  $userRemarks,
        public readonly ?string  $adminRemarks,
        public readonly bool     $status,
//        public readonly int      $preBookingSummaryStatusId,
        public readonly int      $pax,
        public readonly ?string  $brideName,
        public readonly ?string  $groomName,
        public readonly array    $details
    )
    {
    }

    public static function fromApiRequest(int $userId, CreatePreBookingRequest $request): PreBookingSummaryDto
    {
        return new self(
            userId: $userId,
            propertyId: $request->validated('property_id'),
            checkInDate: Carbon::parse($request->validated('check_in')),
            checkOutDate: Carbon::parse($request->validated('check_out')),
            totalAmount: $request->validated('total_budget'),
            budget: $request->validated('user_budget'),
            userRemarks: $request->validated('user_remarks') ?? null,
            adminRemarks: null,
            status: true,
            pax: $request->validated('adults'),
            brideName: $request->validated('bride_name') ?? null,
            groomName: $request->validated('groom_name') ?? null,
            details: $request->validated('details'),
        );
    }
}
