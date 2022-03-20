@extends('layouts.app')
@section('title','Home Page')
@section('contents')
    <div class="row">
        <div class="col-12 grid-margin">
            <div class="card">
                <div class="card-body">
                    <h4 class="card-title">Location Edit</h4>
                    <form  action="{{ route('amenities.update',$data->id) }}" method="POST" >
                        @csrf
                        @method('put')
                        <div>

                            <div class="form-group">
                                <label>Name</label>
                                <input type="text" class="form-control"  placeholder="Enter the locations name" name="name"
                                       value="{{$data->name}}" required>
                            </div>

                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" id="status" class="form-control">
                                    <option value="1" @if($data->status == 1) selected @endif>Active</option>
                                    <option value="0" @if($data->status == 0) selected @endif>Inactive</option>
                                </select>
                            </div>
                            <button class="btn btn-sm btn-primary">Update</button>


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
