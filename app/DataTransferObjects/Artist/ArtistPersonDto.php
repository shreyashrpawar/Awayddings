<?php

namespace App\DataTransferObjects\Artist;

use App\Http\Requests\Web\Artist\CreateArtistPersonRequest;
use App\Http\Requests\Web\Artist\UpdateArtistPersonRequest;

class ArtistPersonDto
{
    public function __construct(
        public readonly string  $name,
        public readonly float   $price,
        public readonly bool    $status,
        public readonly int     $artistId,
        public readonly ?string $artistLink
    )
    {
    }

    public static function fromWebRequest(CreateArtistPersonRequest|UpdateArtistPersonRequest $request): ArtistPersonDto
    {
        return new self(
            name: $request->validated('artist_person_name'),
            price: $request->validated('artist_person_price'),
            status: $request->validated('status') ?? true,
            artistId: $request->validated('artist_id'),
            artistLink: $request->validated('artist_person_link')
        );
    }
}
