@extends('layouts.app')
@section('title','Home Page')
@section('contents')
<style>
    .selected-decorations-tags {
    display: flex;
    flex-wrap: wrap;
    align-items: center;
    gap: 5px;
    border: 1px solid #ccc;
    padding: 5px;
    min-height: 40px; /* Adjust the height as per your design */
}

.tag {
    background-color: #007bff;
    color: #fff;
    padding: 5px 10px;
    border-radius: 20px;
    display: flex;
    align-items: center;
}

.tag .close {
    margin-left: 5px;
    cursor: pointer;
}
</style>
<div class="card">
    <div class="card-body">
        <h4 class="card-title">Event Add</h4>
        <hr>
        <form id="timeSlotForm" action="{{ route('events.store') }}" method="POST"   enctype="multipart/form-data">
            @csrf
            @method('post')
            <div class="row">

                <div class="col-md-6">
                    <!-- <h6 class="text-uppercase">Basic Details</h6> -->
                    <div class="form-group">
                        <label for="">Name  <span style="color:red">*</span></label>
                        <input type="text"  name="event_name" id="event_name" class="form-control">
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Description <span style="color:red">*</span></label>
                        <textarea name="event_description" id="event_description"  class="form-control"  cols="30" rows="5"></textarea>
                    </div>
                </div>
                <div class="col-md-6">

                    <div class="form-group">
                        <label for="">Visible for artist <span style="color:red">*</span></label>
                        <select name="is_artist_visible" id="is_artist_visible" class="form-control" required>
                            <option value="" disabled selected>Select</option>
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-6">

                    <div class="form-group">
                        <label for="">Visible for Decoration <span style="color:red">*</span></label>
                        <select name="is_decor_visible" id="is_decor_visible" class="form-control" required>
                            <option value="" disabled selected>Select</option>
                            <option value="0">No</option>
                            <option value="1">Yes</option>
                        </select>
                    </div>
                </div>


                <div class="col-md-6 artist-block" style="display: none;">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Artists</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <input type="text" id="artist_search" class="form-control search-box" data-type="artist" placeholder="Type to search artist">
                                </div>
                                <div class="col-12">
                                    <div class="form-group" style="margin-left: 22px;">
                                        @foreach($artists as $artist)
                                        <div class="form-check artist-item">
                                            <input type="checkbox" name="artists[]" value="{{ $artist->id }}" class="form-check-input">
                                            <label class="form-check-label" for="artist_{{ $artist->id }}">{{ $artist->name }}</label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6 decoration-block" style="display: none;">
                    <div class="card">
                        <div class="card-header">
                            <h6 class="mb-0">Decorations</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12 mb-3">
                                    <input type="text" id="decoration_search" class="form-control search-box" data-type="decoration" placeholder="Type to search decoration">
                                </div>
                                <div class="col-12">
                                    <div class="form-group" style="margin-left: 22px;">
                                        @foreach($decorations as $decoration)
                                        <div class="form-check decoration-item">
                                            <input type="checkbox" name="decorations[]" value="{{ $decoration->id }}" class="form-check-input">
                                            <label class="form-check-label" for="decoration_{{ $decoration->id }}">{{ $decoration->name }}</label>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>



                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Status <span style="color:red">*</span></label>
                        <select name="event_status" id="event_status" class="form-control" required>
                            <option value="" disabled selected>Select Status</option>
                            <option value="0">Inactive</option>
                            <option value="1">Active</option>
                        </select>
                    </div>

                </div>

                <!-- <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Decorations</label>
                        <select name="decorations[]" id="decorations" class="form-control" multiple>
                            @foreach($decorations as $decoration)
                                <option value="{{ $decoration->id }}">{{ $decoration->description }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="form-group">
                        <label for="">Selected Decorations</label>
                        <div class="selected-decorations-tags">
                        </div>
                    </div>
                </div> -->
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
            // $(".artist_search").on("keyup", function() {
            //     var input, filter, ul, li, a, i, txtValue;
            //     input = $(this).val();
            //     filter = input.toUpperCase();
            //     ul = $(".card-body .form-group");
            //     li = ul.find(".form-check");

            //     for (i = 0; i < li.length; i++) {
            //         a = li[i].getElementsByTagName("label")[0];
            //         txtValue = a.textContent || a.innerText;
            //         if (txtValue.toUpperCase().indexOf(filter) > -1) {
            //             li[i].style.display = "";
            //         } else {
            //             li[i].style.display = "none";
            //         }
            //     }
            // });
        });
    </script>
@endsection
