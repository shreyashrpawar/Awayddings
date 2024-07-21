<?php

namespace App\Services\Addon;

use App\DataTransferObjects\Addon\AddonDTO;
use App\Models\EmAddonFacility;
use App\Services\ImageService;

class AddonService
{

    use DetailService, ImageService;

    /**
     * @param AddonDTO $dto
     * @return EmAddonFacility
     */
    public function create(AddonDTO $dto): EmAddonFacility
    {
        $addonFacility = $this->processData((new EmAddonFacility()), $dto);
        $addonFacility->save();
        return $addonFacility;
    }

    /**
     * @param EmAddonFacility $addonFacility
     * @param AddonDTO $dto
     * @return EmAddonFacility
     */
    public function update(EmAddonFacility $addonFacility, AddonDTO $dto): EmAddonFacility
    {
        $addonFacility = $this->processData($addonFacility, $dto);
        $addonFacility->save();
        return $addonFacility;
    }

    /**
     * @param EmAddonFacility $addonFacility
     * @param AddonDTO $addonDTO
     * @return EmAddonFacility
     */
    private function processData(EmAddonFacility $addonFacility, AddonDTO $addonDTO): EmAddonFacility
    {
        $addonFacility->name = $addonDTO->name;
        $addonFacility->status = $addonDTO->status;
        return $addonFacility;
    }
}
