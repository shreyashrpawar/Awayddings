@extends('layouts.app')
@section('title','Home Page')
@section('contents')
        <div class="row">
            <div class="col-12 grid-margin">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Location Registration</h4>
                        <form  action="{{ route('locations.store') }}" method="POST" >
                            @csrf
                            <div>
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" class="form-control"  placeholder="Enter the locations name" name="name" required>
                                </div>
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea name="description" id="" cols="30" rows="5" class="form-control">
                                    </textarea>
                                </div>
                            <button class="btn btn-sm btn-primary">Save</button>
                            </div>
                        </form>
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
