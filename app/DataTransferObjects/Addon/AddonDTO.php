<?php

namespace App\DataTransferObjects\Addon;

use App\Http\Requests\Web\EmAddon\AddonCreateRequest;
use App\Http\Requests\Web\EmAddon\AddonUpdateRequest;

class AddonDTO
{
    public function __construct(
        public readonly string $name,
        public readonly bool   $status
    )
    {
    }

    public static function fromWebRequest(AddonCreateRequest|AddonUpdateRequest $request): AddonDTO
    {
        return new self(
            name: $request->validated('facility_name'),
            status: $request->has('status') ? $request->validated('status') : true
        );
    }
}
