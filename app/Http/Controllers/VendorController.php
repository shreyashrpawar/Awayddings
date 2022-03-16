<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\User;
use App\Models\UserVendorAlignment;
use App\Models\Vendor;
use App\Models\VendorPropertyAlignment;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $properties = Property::pluck('name','id')->all();
        $vendors    = Vendor::paginate(50);
        return view('app.property.vendors.vendor_list',compact('properties','vendors'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $properties = Property::pluck('name','id')->all();
        return view('app.property.vendors.vendor_create',compact('properties'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        // create vendor
        $vendor_data =[
            'name' => $request->name,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'pin_code' => $request->pin_code,
            'gst' => $request->gst,
            'first_name'=> $request->first_name,
            'last_name'=> $request->last_name,
            'email'=> $request->email,
            'phone'=> $request->phone,

        ];
        $vendorResp = Vendor::create($vendor_data);

        $login_data = [
            'name'=> $request->login_name,
            'email'=> $request->login_email,
            'phone' => $request->login_phone,
            'password'=> bcrypt($request->login_password)
        ];
        // create login details
        $userResp = User::create($login_data);
        $userResp->assignRole('vendor');

        // vendor and user alignment
        $user_vendorResp = UserVendorAlignment::create([
            'vendor_id' => $vendorResp->id,
            'user_id' => $userResp->id
        ]);


        $request->session()->flash('success','Successfully Saved');

        return redirect(route('property-vendors.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       $data = Vendor::find($id);
       return view('app.property.vendors.vendor_show',compact('data'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Vendor::find($id);
        return view('app.property.vendors.vendor_edit',compact('data'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

        $vendor = Vendor::find($id);
        $vendor_data =[
            'name' => $request->name,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'pin_code' => $request->pin_code,
            'gst' => $request->gst,
            'first_name'=> $request->first_name,
            'last_name'=> $request->last_name,
            'email'=> $request->email,
            'phone'=> $request->phone,

        ];
        $vendor->update($vendor_data);
        $request->session()->flash('success','Successfully Updated');
        return redirect(route('property-vendors.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Vendor  $vendor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vendor $vendor)
    {
        //
    }

    public function showPropertyVendorAssociationPage(Request  $request,$vendor_id){
        $propertyVendorAlignments = VendorPropertyAlignment::where('vendor_id',$vendor_id)->get();
        $vendor = Vendor::where('id',$vendor_id)->firstorFail();
        $properties = Property::pluck('name','id')->all();
        return view('app.property.vendors.vendor_associate',compact('propertyVendorAlignments','vendor','properties'));
    }

    public function submitPropertyVendorAssociationForm(Request  $request,$vendor_id){
        $vendor_id = $request->vendor_id;
        $associate = VendorPropertyAlignment::create([
                'vendor_id' => $vendor_id,
                'property_id' => $request->property_id,
        ]);
        $request->session()->flash('success','Successfully Associated');
        return redirect(url('property-vendor/'.$vendor_id.'/associate'));
    }

}
