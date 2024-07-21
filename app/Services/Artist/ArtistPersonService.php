<?php

namespace App\Services\Artist;

use App\DataTransferObjects\Artist\ArtistPersonDto;
use App\Models\ArtistPerson;

trait ArtistPersonService
{
    public function createPerson(ArtistPersonDto $dto): ArtistPerson
    {
        $person = $this->processPersonData((new ArtistPerson()), $dto);
        $person->save();
        return $person;
    }

    public function updatePerson(ArtistPerson $person, ArtistPersonDto $dto): ArtistPerson
    {
        $person = $this->processPersonData($person, $dto);
        $person->save();
        return $person;
    }

    private function processPersonData(ArtistPerson $person, ArtistPersonDto $personDto): ArtistPerson
    {
        $person->name = $personDto->name;
        $person->price = $personDto->price;
        $person->artist_id = $personDto->artistId;
        $person->artist_person_link = $personDto->artistLink ?? $person->artist_person_link;
        $person->status = $personDto->status;
        return $person;
    }
}
