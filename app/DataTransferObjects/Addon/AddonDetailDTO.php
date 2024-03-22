<?php

namespace App\DataTransferObjects\Addon;

use App\Http\Requests\Web\EmAddon\DetailCreateRequest;
use App\Http\Requests\Web\EmAddon\DetailUpdateRequest;

class AddonDetailDTO
{
    public function __construct(
        public readonly float  $price,
        public readonly string $description,
        public readonly int    $addonId,
        public readonly bool   $status
    )
    {
    }

    public static function fromWebRequest(DetailCreateRequest|DetailUpdateRequest $request, $addonId = null): AddonDetailDTO
    {
        return new self(
            price: $request->validated('price'),
            description: $request->validated('description'),
            addonId: $addonId ?? $request->validated('facility_id'),
            status: $request->has('status') ? $request->validated('status') : true
        );
    }
}
