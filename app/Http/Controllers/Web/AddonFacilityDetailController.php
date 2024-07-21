<?php

namespace App\Http\Controllers\Web;

use App\DataTransferObjects\Addon\AddonDetailDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\EmAddon\DetailCreateRequest;
use App\Http\Requests\Web\EmAddon\DetailUpdateRequest;
use App\Models\EmAddonFacility;
use App\Models\EmAddonFacilityDetails;
use App\Services\Addon\AddonService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\EmAddonFacilityDetails as AddonDetails;

class AddonFacilityDetailController extends Controller
{
    /**
     * @param AddonService $addonService
     */
    public function __construct(
        protected AddonService $addonService
    )
    {
    }

    /**
     * @param EmAddonFacility $emAddonFacility
     * @return View
     */
    public function index(EmAddonFacility $emAddonFacility): View
    {
        $facilityDetails = EmAddonFacilityDetails::with('image')
            ->where('em_addon_facility_id', $emAddonFacility->id)
            ->get();

        return view(
            'app.addon_facility.facility_details_listing',
            compact('facilityDetails', 'emAddonFacility')
        );
    }

    /**
     * @param DetailCreateRequest $request
     * @return RedirectResponse
     */
    public function store(DetailCreateRequest $request): RedirectResponse
    {
        $facilityDetails = $this->addonService->createDetails(
            AddonDetailDTO::fromWebRequest($request)
        );

        try {
            $this->addonService->saveImage($facilityDetails, $request->file('facility_details_image'));
        } catch (\Exception $exception) {
            $request->session()->flash('error', $exception->getMessage());
            return redirect()->back();
        }
        return redirect()->back();
    }


    /**
     * @param AddonDetails $facilityDetails
     * @param DetailUpdateRequest $request
     * @return RedirectResponse
     */
    public function update(AddonDetails $facilityDetails, DetailUpdateRequest $request): RedirectResponse
    {
        $facilityDetails = $this->addonService->updateDetails(
            $facilityDetails,
            AddonDetailDTO::fromWebRequest($request, $facilityDetails->em_addon_facility_id)
        );

        if ($request->has('facility_details_image')) {
            $facilityDetails->loadMissing('image');
            try {
                $this->addonService->saveImage(
                    model: $facilityDetails,
                    image: $request->file('facility_details_image'),
                    deleteImage: !empty($facilityDetails->image)
                );
            } catch (\Exception $exception) {
                $request->session()->flash('error', $exception->getMessage());
                return redirect()->back();
            }
        }
        return redirect()->back();
    }
}
