<?php

namespace App\DataTransferObjects\Artist;

use App\Http\Requests\Web\Artist\CreateRequest;
use App\Http\Requests\Web\Artist\UpdateRequest;

class ArtistDto
{
    public function __construct(
        public readonly string  $name,
        public readonly ?string $description,
        public readonly bool    $status
    )
    {
    }

    public static function fromWebRequest(CreateRequest|UpdateRequest $request): ArtistDto
    {
        return new self(
            name: $request->validated('artist_name'),
            description: $request->has('description') ? $request->validated('description') : null,
            status: $request->has('status') ? $request->validated('status') : true
        );
    }
}
