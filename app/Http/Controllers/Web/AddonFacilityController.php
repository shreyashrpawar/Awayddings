<?php

namespace App\Http\Controllers\Web;

use App\DataTransferObjects\Addon\AddonDTO;
use App\Http\Controllers\Controller;
use App\Http\Requests\Web\EmAddon\AddonCreateRequest;
use App\Http\Requests\Web\EmAddon\AddonUpdateRequest;
use App\Models\EmAddonFacility;
use App\Services\Addon\AddonService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class AddonFacilityController extends Controller
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
     * @return View
     */
    public function index(): View
    {
        $facilities = EmAddonFacility::all();
        return view('app.addon_facility.list_addon_facilities', compact('facilities'));
    }

    /**
     * @param AddonCreateRequest $request
     * @return RedirectResponse
     */
    public function store(AddonCreateRequest $request): RedirectResponse
    {
        $this->addonService->create(AddonDTO::fromWebRequest($request));
        return redirect()->back();
    }

    /**
     * @param EmAddonFacility $emAddonFacility
     * @param AddonUpdateRequest $request
     * @return RedirectResponse
     */
    public function update(EmAddonFacility $emAddonFacility, AddonUpdateRequest $request): RedirectResponse
    {
        $this->addonService->update($emAddonFacility, AddonDTO::fromWebRequest($request));
        return redirect()->back();
    }
}
