@extends('layouts.app')
@section('title','Home Page')
@section('contents')
    <div class="content-wrapper">
        <div class="row">
            <div class="col-12 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Amenities Registration</h4>
                        <form  action="{{ route('amenities.store') }}" method="POST" >
                            @csrf
                            <div>
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" class="form-control"  placeholder="Enter the Amenity name" name="name" required>
                                </div>
                                 <button class="btn btn-sm btn-primary">Save</button>
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
    <script src="{{ asset('/assets/vendors/jquery-steps/jquery.steps.min.js') }}"></script>
    <script src="{{ asset('assets/vendors/jquery-validation/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('assets/js/wizard.js') }}"></script>

@endsection
