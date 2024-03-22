<?php

namespace App\Services\Addon;

use App\DataTransferObjects\Addon\AddonDetailDTO;
use App\Models\EmAddonFacilityDetails as AddonDetails;

trait DetailService
{
    /**
     * @param AddonDetailDTO $dto
     * @return AddonDetails
     */
    public function createDetails(AddonDetailDTO $dto): AddonDetails
    {
        $facilityDetails = $this->processDetailData((new AddonDetails()), $dto);
        $facilityDetails->save();
        return $facilityDetails;
    }

    /**
     * @param AddonDetails $facilityDetails
     * @param AddonDetailDTO $dto
     * @return AddonDetails
     */
    public function updateDetails(AddonDetails $facilityDetails, AddonDetailDTO $dto): AddonDetails
    {
        $facilityDetails = $this->processDetailData($facilityDetails, $dto);
        $facilityDetails->save();
        return $facilityDetails;
    }

    /**
     * @param AddonDetails $facilityDetails
     * @param AddonDetailDTO $detailDTO
     * @return AddonDetails
     */
    private function processDetailData(AddonDetails $facilityDetails, AddonDetailDTO $detailDTO): AddonDetails
    {
        $facilityDetails->em_addon_facility_id = $detailDTO->addonId;
        $facilityDetails->price = $detailDTO->price;
        $facilityDetails->description = $detailDTO->description;
        $facilityDetails->status = $detailDTO->status;
        return $facilityDetails;
    }
}
