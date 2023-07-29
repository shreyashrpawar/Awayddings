@extends('layouts.app')
@section('title','Home Page')
@section('contents')



<div class="card">
    <div class="card-body">
        <h4 class="card-title">Light & Sounds Edit</h4>
        <hr>
        <form id="timeSlotForm" action="{{ route('lightandsounds.update',$light_sound->id) }}" method="POST"   enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="light_sound_id" value="{{ $light_sound->id }}">

            <div class="row">

                <div class="col-md-6">
                    <div class="col-md-6">
                        <label for="light_sound_image">Light and Sounds Image:</label>
                        <input type="file" id="light_sound_image" name="light_sound_image">
                        @if ($light_sound->image)
                            <img src="{{  $light_sound->image->url}}" alt="Light and sound Image" width="300" height="200">
                        @else
                            <p>No image available.</p>
                        @endif
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Status <span style="color:red">*</span></label>
                        <select name="light_sound_status" id="light_sound_status" class="form-control" required>
                            <option value="" disabled >Select Status</option>
                            <option value="0" <?php echo ($light_sound->status == 0 ? 'selected' : '') ?>>Inactive</option>
                            <option value="1" <?php echo ($light_sound->status == 1 ? 'selected' : '') ?>>Active</option>
                        </select>
                    </div>
                </div>
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
