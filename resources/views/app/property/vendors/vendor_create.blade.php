@extends('layouts.app')
@section('title','User Registration')
@section('contents')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Vendor Registration</h4>
                        <hr>
                        <form id="propertyRegistrationFrom" action="{{ route('property-vendors.store') }}" method="POST" >
                            @csrf
                            <div class="row">

                                <div class="col-md-6">
                                    <h6 class="text-uppercase">Basic Details</h6>
                                    <div class="form-group">
                                        <label for="">Name</label>
                                        <input type="text" class="form-control"  name="name" required >
                                    </div>
                                    <div class="form-group">
                                        <label for="">Address</label>
                                        <input type="text" class="form-control"  name="address" required >
                                    </div>

                                    <div class="form-group">
                                        <label for="">City</label>
                                        <input type="text" class="form-control"  name="city" required >
                                    </div>

                                    <div class="form-group">
                                        <label for="">State</label>
                                        <input type="text" class="form-control"  name="state" required >
                                    </div>

                                    <div class="form-group">
                                        <label for="">Pin Code</label>
                                        <input type="text" class="form-control"  name="pin_code" required >
                                    </div>
                                    <div class="form-group">
                                        <label for="">GST</label>
                                        <input type="text" class="form-control"  name="gst" required >
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-uppercase">Contact Person Details</h6>

                                    <div class="form-group">
                                        <label for="">First Name</label>
                                        <input type="text" class="form-control"  name="first_name"  id="first_name" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Last Name</label>
                                        <input type="text" class="form-control"  name="last_name"   id="last_name" required>
                                    </div>

                                    <div class="form-group">
                                        <label for="">Email</label>
                                        <input type="email" class="form-control"  name="email"  id="email" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Phone</label>
                                        <input type="text" class="form-control"  name="phone"  id="phone" required>
                                    </div>

                                </div>
                                <div class="col-md-6">
                                    <h6 class="text-uppercase">Login Details</h6>
                                    <div class="form-group">
                                        <label for="">Name</label>
                                        <input type="text" class="form-control"  name="login_name" id="login_name"  required>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Email</label>
                                        <input type="text" class="form-control"  name="login_email"  id="login_email" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Phone</label>
                                        <input type="text" class="form-control"  name="login_phone"  id="login_phone" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="">Password</label>
                                        <input type="password" class="form-control"  name="login_password" id="login_password"  required>
                                    </div>
                                    <div class="form-group">
                                        <button class="btn btn-primary">Save</button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
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
    </script>
@endsection
