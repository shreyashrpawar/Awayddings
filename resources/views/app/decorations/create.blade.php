@extends('layouts.app')
@section('title','Home Page')
@section('contents')



<div class="card">
    <div class="card-body">
        <h4 class="card-title">Decorations Add</h4>
        <hr>
        <form id="timeSlotForm" action="{{ route('decorations.store') }}" method="POST"   enctype="multipart/form-data">
            @csrf
            @method('post')
            <div class="row">

                <div class="col-md-6">
                    <!-- <h6 class="text-uppercase">Basic Details</h6> -->
                    <div class="form-group">
                        <label for="">Name  <span style="color:red">*</span></label>
                        <input type="text"  name="decoration_name" id="decoration_name" class="form-control">
                    </div>
                </div>

                <div class="col-md-6">

                    <div class="form-group">
                        <label for="">Description <span style="color:red">*</span></label>
                        <textarea name="decoration_description" id="decoration_description"  class="form-control"  cols="30" rows="5"></textarea>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Price  <span style="color:red">*</span></label>
                        <input type="number" step="any"  name="decoration_price" id="decoration_price" class="form-control">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="col-md-6">
                    <label for="decoration_image">Decoration Image:</label>
                    <input type="file" id="decoration_image" name="decoration_image">

                    </div>
                </div>
                <div class="col-md-6">

                    <div class="form-group">
                        <label for="">Status <span style="color:red">*</span></label>
                        <select name="decoration_status" id="decoration_status" class="form-control" required>
                            <option value="" disabled selected>Select Status</option>
                            <option value="0">Inactive</option>
                            <option value="1">Active</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group" style="margin: 10px;">
                <button class="btn btn-primary">Save</button>
            </div>
        </form>
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
