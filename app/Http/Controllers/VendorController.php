<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\User;
use App\Models\UserVendorAlignment;
use App\Models\Vendor;
use App\Models\VendorPropertyAlignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

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

        $this->validate($request,[
            'name' => 'required',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'pin_code' => 'required',
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            'phone' => 'required',
            'login_password' => 'required',
//            'cancelled_cheque_file' => 'mimes:png,jpg,jpeg,pdf|max:5000',
//            'pan_card_file' => 'mimes:png,jpg,jpeg,pdf|max:5000',
//            'gst_file' => 'mimes:png,jpg,jpeg,pdf|max:5000'
        ]);

        // create vendor
        $vendor_data =[
            'name' => $request->name,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'pin_code' => $request->pin_code,
            'gst' => $request->gst,
            'pan' => $request->pan,
            'first_name'=> $request->first_name,
            'last_name'=> $request->last_name,
            'email'=> $request->email,
            'phone'=> $request->phone,
        ];

        if($request->hasFile('cancelled_cheque_file')){
            $file = $request->file('cancelled_cheque_file');
            $name = time() . $file->getClientOriginalName();
            $filePath = 'images/'. $name;
            Storage::disk('s3')->put($filePath, file_get_contents($file),'public');
            $vendor_data['cancelled_cheque_file']=  Storage::disk('s3')->url($filePath);
        }

        if($request->hasFile('pan_card_file')){
            $file = $request->file('pan_card_file');
            $name = time() . $file->getClientOriginalName();
            $filePath = 'images/'. $name;
            Storage::disk('s3')->put($filePath, file_get_contents($file),'public');
            $vendor_data['pan_card_file']=  Storage::disk('s3')->url($filePath);
        }

        if($request->hasFile('gst_file')){
            $file = $request->file('gst_file');
            $name = time() . $file->getClientOriginalName();
            $filePath = 'images/'. $name;
            Storage::disk('s3')->put($filePath, file_get_contents($file),'public');
            $vendor_data['gst_file']=  Storage::disk('s3')->url($filePath);
        }


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

        return redirect(route('vendors.index'));
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
//        $this->validate($request,[
//            'cancelled_cheque_file' => 'sometimes|required|mimes:png,jpg,jpeg,pdf|max:5000',
//            'pan_card_file' => 'sometimes|required|mimes:png,jpg,jpeg,pdf|max:5000',
//            'gst_file' => 'sometimes|required|mimes:png,jpg,jpeg,pdf|max:5000'
//        ]);

        $vendor = Vendor::find($id);
        $vendor_data =[
            'name' => $request->name,
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'pin_code' => $request->pin_code,
            'gst' => $request->gst,
            'pan' => $request->pan,
            'first_name'=> $request->first_name,
            'last_name'=> $request->last_name,
            'email'=> $request->email,
            'phone'=> $request->phone,

        ];
        if($request->hasFile('cancelled_cheque_file')){
            $file = $request->file('cancelled_cheque_file');
            $name = time() . $file->getClientOriginalName();
            $filePath = 'images/'. $name;
            Storage::disk('s3')->put($filePath, file_get_contents($file),'public');
            $vendor_data['cancelled_cheque_file']=  Storage::disk('s3')->url($filePath);
        }

        if($request->hasFile('pan_card_file')){
            $file = $request->file('pan_card_file');
            $name = time() . $file->getClientOriginalName();
            $filePath = 'images/'. $name;
            Storage::disk('s3')->put($filePath, file_get_contents($file),'public');
            $vendor_data['pan_card_file']=  Storage::disk('s3')->url($filePath);
        }

        if($request->hasFile('gst_file')){
            $file = $request->file('gst_file');
            $name = time() . $file->getClientOriginalName();
            $filePath = 'images/'. $name;
            Storage::disk('s3')->put($filePath, file_get_contents($file),'public');
            $vendor_data['gst_file']=  Storage::disk('s3')->url($filePath);
        }
        $vendor->update($vendor_data);
        $request->session()->flash('success','Successfully Updated');
        return redirect(route('vendors.index'));
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
        $exist = VendorPropertyAlignment::where('vendor_id',$vendor_id)->where('property_id',$request->property_id)->first();
        if(!$exist){
            $associate = VendorPropertyAlignment::create([
                'vendor_id' => $vendor_id,
                'property_id' => $request->property_id,
            ]);
            $request->session()->flash('success','Successfully Associated');
            return redirect(url('property/vendor/'.$vendor_id.'/associate'));
        }else{
            $request->session()->flash('error',$exist->property->name. ' - Property Already Associated');
            return redirect(url('property/vendor/'.$vendor_id.'/associate'));
        }

    }

}
