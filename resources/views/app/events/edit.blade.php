@extends('layouts.app')
@section('title','Home Page')
@section('contents')



<div class="card">
    <div class="card-body">
        <h4 class="card-title">Event Add</h4>
        <hr>
        <form id="timeSlotForm" action="{{ route('events.update',$event->id) }}" method="POST"   enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="event_id" value="{{ $event->id }}">

            <div class="row">

                <div class="col-md-6">
                    <!-- <h6 class="text-uppercase">Basic Details</h6> -->
                    <div class="form-group">
                        <label for="">Name  <span style="color:red">*</span></label>
                        <input type="text"  name="event_name" id="event_name" class="form-control" value="{{ $event->name }}">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Description <span style="color:red">*</span></label>
                        <textarea name="event_description" id="event_description"  class="form-control"  cols="30" rows="5">{{ $event->description }}</textarea>
                    </div>
                </div>
                <div class="col-md-6">

                    <div class="form-group">
                        <label for="">Visible for Artist <span style="color:red">*</span></label>
                        <select name="is_artist_visible" id="is_artist_visible" class="form-control" required>
                            <option value="" disabled>Select</option>
                            <option value="0" <?php echo ($event->is_artist_visible == 0 ? 'selected' : '') ?>>No</option>
                            <option value="1" <?php echo ($event->is_artist_visible == 1 ? 'selected' : '') ?>>Yes</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">

                    <div class="form-group">
                        <label for="">Visible for Decoration <span style="color:red">*</span></label>
                        <select name="is_decor_visible" id="is_decor_visible" class="form-control" required>
                            <option value="" disabled>Select</option>
                            <option value="0" <?php echo ($event->is_decor_visible == 0 ? 'selected' : '') ?>>No</option>
                            <option value="1" <?php echo ($event->is_decor_visible == 1 ? 'selected' : '') ?>>Yes</option>
                        </select>
                    </div>
                </div>

                <!-- Artists Checkbox Block -->
                <div class="col-md-6 artist-block" style="display: <?php echo ($event->is_artist_visible == 1) ? 'block' : 'none'?>;">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Artists</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <input type="text" id="artist_search" class="form-control artist_search" placeholder="Type to search artist">
                                </div>
                                <div class="col-12">
                                    <div class="form-group" style="margin-left: 22px;">
                                        @foreach($artists as $artist)
                                        <div class="form-check">
                                            <input type="checkbox" name="artists[]" value="{{ $artist->id }}" class="form-check-input" @if ($event->artists->contains($artist->id)) checked @endif>
                                            <label class="form-check-label" for="artist_{{ $artist->id }}">{{ $artist->name }}</label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Decorations Checkbox Block -->
                <div class="col-md-6 decoration-block" style="display: <?php echo ($event->is_decor_visible == 1) ? 'block' : 'none'?>;">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Decorations</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <input type="text" id="decoration_search" class="form-control decoration_search" placeholder="Type to search decoration">
                                </div>
                                <div class="col-12">
                                    <div class="form-group" style="margin-left: 22px;">
                                        @foreach($decorations as $decoration)
                                        <div class="form-check">
                                            <input type="checkbox" name="decorations[]" value="{{ $decoration->id }}" class="form-check-input" @if ($event->decorations->contains($decoration->id)) checked @endif>
                                            <label class="form-check-label" for="decoration_{{ $decoration->id }}">{{ $decoration->name }}</label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Status <span style="color:red">*</span></label>
                        <select name="event_status" id="event_status" class="form-control" required>
                            <option value="" disabled >Select Status</option>
                            <option value="0" <?php// echo ($event->status == 0 ? 'selected' : '') ?>>Inactive</option>
                            <option value="1" <?php// echo ($event->status == 1 ? 'selected' : '') ?>>Active</option>
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
            $("#is_artist_visible").on("change", function() {
                var isVisible = ($(this).val() == 1);
                $(".artist-block").toggle(isVisible);
            });
            $("#is_decor_visible").on("change", function() {
                var isVisible = ($(this).val() == 1);
                $(".decoration-block").toggle(isVisible);
            });
            $(".search-box").on("keyup", function() {
                var input, filter, ul, li, a, i, txtValue;
                input = $(this).val().toUpperCase();
                filter = $(this).data('type');

                ul = $(".card-body .form-group");
                li = ul.find("." + filter + "-item");

                for (i = 0; i < li.length; i++) {
                    a = li[i].getElementsByTagName("label")[0];
                    txtValue = a.textContent || a.innerText;
                    if (txtValue.toUpperCase().indexOf(input) > -1) {
                        li[i].style.display = "";
                    } else {
                        li[i].style.display = "none";
                    }
                }
            });
        });
    </script>
@endsection
