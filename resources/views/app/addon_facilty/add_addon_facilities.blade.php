@extends('layouts.app')
@section('title','Home Page')
@section('contents')
<div class="card">
    <div class="card-body">
        <h1>Addon Facilities</h1>
        <div class="row">

            <div class="col-md-12">
                <!-- Form to add addon facility -->
                <form action="{{ route('addon_facilities.store') }}" method="post">
                    @csrf
                    <div class="col-md-6">

                        <div class="form-group">
                            <label for="facility_name">Facility Name:</label>
                            <input type="text" class="form-control" id="facility_name" name="facility_name">
                        </div>
                    </div>
                    <div class="form-group" style="margin: 10px;">
                        <button class="btn btn-primary" type="submit">Add Facility</button>
                    </div>
                </form>
                <hr>
                <!-- Form to add addon facility details -->
                <form action="{{ route('addon_facility_details.store') }}" method="post">
                    @csrf
                    <div class="col-md-6">

                        <div class="form-group">
                        <label for="facility_id">Select Addon Facility:</label>
                        <select name="facility_id" id="facility_id" class="form-control" required>
                            @foreach($facilities as $facility)
                                <option value="{{ $facility->id }}">{{ $facility->name }}</option>
                            @endforeach
                        </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="price">Price:</label>
                            <input type="number" id="price" name="price" required class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="description">Description:</label>
                            <textarea id="description" name="description" class="form-control"></textarea>
                        </div>
                    </div>
                    <div class="form-group" style="margin: 10px;">
                        <button class="btn btn-primary" type="submit">Add Facility Details</button>
                    </div>
                </form>
            
            </div>
        </div>
    </div>

</div>
@endsection
@section('js')
    <script src="{{ asset('/assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="//code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="//code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    <script src="{{ mix('js/app.js') }}"></script>
    <script>
        $(document).ready(function() {
            
        });
    </script>
@endsection