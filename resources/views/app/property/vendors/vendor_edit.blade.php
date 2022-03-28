@extends('layouts.app')
@section('title','Vendor Details Edit')
@section('contents')
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Vendor Details Edit</h4>
            <hr>
            <form id="propertyRegistrationFrom" action="{{ route('vendors.update',$data->id) }}" method="POST" >
                @csrf
                @method('put')
                <div class="row">

                    <div class="col-md-6">
                        <h6 class="text-uppercase">Basic Details</h6>
                        <div class="form-group">
                            <label for="">Name  <span style="color:red">*</span></label>
                            <input type="text" class="form-control"  name="name" required  value="{{ $data->name }}">
                        </div>
                        <div class="form-group">
                            <label for="">Address  <span style="color:red">*</span></label>
                            <input type="text" class="form-control"  name="address" required value="{{ $data->address }}" >
                        </div>

                        <div class="form-group">
                            <label for="">City  <span style="color:red">*</span></label>
                            <input type="text" class="form-control"  name="city" required  value="{{ $data->city }}" >
                        </div>

                        <div class="form-group">
                            <label for="">State  <span style="color:red">*</span></label>
                            <input type="text" class="form-control"  name="state" required  value="{{ $data->state }}" >
                        </div>

                        <div class="form-group">
                            <label for="">Pin Code  <span style="color:red">*</span></label>
                            <input type="text" class="form-control phone"  name="pin_code" required  value="{{ $data->pin_code }}" maxlength="6" >
                        </div>
                        <div class="form-group">
                            <label for="">Pan Card </label>
                            <input type="text" class="form-control"  name="pan"  >
                        </div>
                        <div class="form-group">
                            <label for="">GST</label>
                            <input type="text" class="form-control"  name="gst" required  value="{{ $data->gst }}" >
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-uppercase">Contact Person Details</h6>

                        <div class="form-group">
                            <label for="">First Name  <span style="color:red">*</span></label>
                            <input type="text" class="form-control"  name="first_name"  id="first_name" value="{{ $data->first_name }}" required>
                        </div>
                        <div class="form-group">
                            <label for="">Last Name  <span style="color:red">*</span></label>
                            <input type="text" class="form-control"  name="last_name"   id="last_name" value="{{ $data->last_name }}" required>
                        </div>

                        <div class="form-group">
                            <label for="">Email  <span style="color:red">*</span></label>
                            <input type="email" class="form-control"  name="email"  id="email" value="{{ $data->email }}" required>
                        </div>
                        <div class="form-group">
                            <label for="">Phone  <span style="color:red">*</span></label>
                            <input type="text" class="form-control phone"  name="phone"  id="phone" value="{{ $data->phone }}"  maxlength="11" required>
                        </div>

                    </div>

                    <div class="form-group">
                        <button class="btn btn-primary">Update</button>
                    </div>
                </div>
            </form>
        </div>

    </div>
@endsection
@section('js')
    <script src="{{ asset('/assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <script>
        $('#email').change(function(){
                $('#login_email').val($(this).val());
        })
        $('#phone').change(function(){
            $('#login_phone').val($(this).val());
        })
        $('#first_name,#last_name').change(function(){
            let first_name = $('#first_name').val();
            let last_name = $('#last_name').val();
            $('#login_name').val(first_name +' '+ last_name);
        })
        $('.phone').keyup(function(e)
        {
            if (/\D/g.test(this.value))
            {
                // Filter non-digits from input value.
                this.value = this.value.replace(/\D/g, '');
            }
        });
    </script>
@endsection
