@extends('layouts.app')
@section('title','Home Page')
@section('contents')



<div class="card">
    <div class="card-body">
        <h4 class="card-title">Time Slot Add</h4>
        <hr>
        <form id="timeSlotForm" action="{{ route('timeslots.store') }}" method="POST"   enctype="multipart/form-summary">
            @csrf
            @method('post')
            <div class="row">

                <div class="col-md-6">
                    <!-- <h6 class="text-uppercase">Basic Details</h6> -->
                    <div class="form-group">
                        <label for="">From Time  <span style="color:red">*</span></label>
                        <input type="time"  name="from_time" id="from_time" class="form-control time">
                    </div>

                    <div class="form-group">
                        <label for="">Status <span style="color:red">*</span></label>
                        <select name="status" id="status" class="form-control" required>
                            <option value="" disabled selected>Select Status</option>
                            <option value="0">Inactive</option>
                            <option value="1">Active</option>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    
                    <div class="form-group">
                        <label for="">To Time  <span style="color:red">*</span></label>
                        <input type="time"  name="to_time" id="to_time" class="form-control time">
                    </div>
                </div>

                <div class="form-group">
                    <button class="btn btn-primary">Save</button>
                </div>
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
            $('.time').on('focus', function () {
                $(this).attr('type', 'time');
            });

            // Hide the seconds part of the time picker
            $('.time').on('click', function () {
                setTimeout(function () {
                    $('input[type="time"]').each(function () {
                        if ($(this).attr('type') == 'text') {
                            var timeVal = $(this).val();
                            if (timeVal !== '') {
                                var parts = timeVal.split(':');
                                var newTime = parts[0] + ':' + parts[1];
                                $(this).val(newTime);
                            }
                        }
                    });
                }, 100);
            });
        });
    </script>
@endsection
