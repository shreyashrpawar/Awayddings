@extends('layouts.app')
@section('title','Home Page')
@section('contents')



<div class="card">
    <div class="card-body">
        <h4 class="card-title">Artist Person Edit</h4>
        <hr>
        <form id="timeSlotForm" action="{{ route('artist_person_update',$artist_person->id) }}" method="POST"   enctype="multipart/form-data">
            @csrf
            @method('POST')
            <input type="hidden" name="artist_person_id" value="{{ $artist_person->id }}">
            <input type="hidden" name="selected_status" class="selected_status" value="{{ $artist_person->id }}">

            <div class="row">

                <div class="col-md-6">

                    <div class="form-group">
                        <label for="">Artist <span style="color:red">*</span></label>
                        <select name="artist_id" id="artist_id" class="form-control" required>
                            <option value="" disabled>Select Artist</option>
                            @if($artists->count() > 0)
                                @foreach($artists as $key => $val)
                                <option value="{{ $val->id }}" <?php echo ($artist_person->id == $val->id ? 'selected' : '') ?>>{{ $val->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <!-- <h6 class="text-uppercase">Basic Details</h6> -->
                    <div class="form-group">
                        <label for="">Name  <span style="color:red">*</span></label>
                        <input type="text"  name="artist_person_name" id="artist_person_name" class="form-control" value="{{ $artist_person->name }}">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Price  <span style="color:red">*</span></label>
                        <input type="number" step="any"  name="artist_person_price" id="artist_person_price" class="form-control" value="{{ $artist_person->price }}">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Link  <span style="color:red">*</span></label>
                        <input type="url" id="artist_person_link" name="artist_person_link" placeholder="https://www.instagram.com/username" class="form-control" value="{{ $artist_person->artist_person_link }}">
                    </div>
                </div>


                <div class="col-md-6">
                    <div class="form-group">
                        <label for="artist_image">Artist Image:</label>
                        <input type="file" id="artist_person_image" name="artist_person_image">
                        @if ($artist_person->image)
                            <img src="{{ asset('storage/' . $artist_person->image->url) }}{{-- $artist_person->image->url --}}" alt="Artist Image" width="300" height="200">
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
                            <option value="0" <?php// echo ($artist_person->status == 0 ? 'selected' : '') ?>>Inactive</option>
                            <option value="1" <?php// echo ($artist_person->status == 1 ? 'selected' : '') ?>>Active</option>
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
