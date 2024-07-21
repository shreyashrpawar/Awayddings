<?php

namespace App\Services\Artist;

use App\DataTransferObjects\Artist\ArtistDto;
use App\Models\Artist;
use App\Services\ImageService;

class ArtistService
{
    use ImageService, ArtistPersonService;
    /**
     * @param ArtistDto $dto
     * @return Artist
     */
    public function create(ArtistDto $dto): Artist
    {
        $artist = $this->processData((new Artist()), $dto);
        $artist->save();
        return $artist;
    }

    /**
     * @param Artist $artist
     * @param ArtistDto $dto
     * @return Artist
     */
    public function update(Artist $artist, ArtistDto $dto): Artist
    {
        $artist = $this->processData($artist, $dto);
        $artist->save();
        return $artist;
    }

    /**
     * @param Artist $artist
     * @param ArtistDto $artistDto
     * @return Artist
     */
    private function processData(Artist $artist, ArtistDto $artistDto): Artist
    {
        $artist->name = $artistDto->name;
        $artist->description = $artistDto->description ?? $artist->description;
        $artist->status = $artistDto->status;
        return $artist;
    }
}
