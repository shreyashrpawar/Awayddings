@extends('layouts.app')
@section('title','Home Page')
@section('contents')



<div class="card">
    <div class="card-body">
        <h4 class="card-title">Artist Add</h4>
        <hr>
        <form id="timeSlotForm" action="{{ route('artists.update',$artist->id) }}" method="POST"   enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="artist_id" value="{{ $artist->id }}">
            <input type="hidden" name="selected_status" class="selected_status" value="{{ $artist->id }}">

            <div class="row">

                <div class="col-md-6">
                    <!-- <h6 class="text-uppercase">Basic Details</h6> -->
                    <div class="form-group">
                        <label for="">Name  <span style="color:red">*</span></label>
                        <input type="text"  name="artist_name" id="artist_name" class="form-control" value="{{ $artist->name }}">
                    </div>
                    <div class="col-md-6">
                        <label for="artist_image">Artist Image:</label>
                        <input type="file" id="artist_image" name="artist_image">
                        @if ($artist->image)
                            <img src="{{ $artist->image->url }}" alt="Artist Image" width="300" height="200">
                        @else
                            <p>No image available.</p>
                        @endif
                    </div>
                </div>
                <!-- <div class="col-md-6">
                    

                    <div class="form-group">
                        <label for="">Status <span style="color:red">*</span></label>
                        <select name="artist_status" id="artist_status" class="form-control" required>
                            <option value="" disabled >Select Status</option>
                            <option value="0" <?php //echo ($artist->status == 0 ? 'selected' : '') ?>>Inactive</option>
                            <option value="1" <?php //echo ($artist->status == 1 ? 'selected' : '') ?>>Active</option>
                        </select>
                    </div>
                </div> -->
            </div>

            <div class="form-group" style="margin: 10px;">
                <button class="btn btn-primary">Update</button>
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
