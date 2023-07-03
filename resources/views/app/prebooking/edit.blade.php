@extends('layouts.app')
@section('title','Pre-Booking Details Edit')
@section('contents')
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Pre-Booking Details Edit</h4>
            <hr>
            <form id="propertyRegistrationFrom" action="{{ route('pre-bookings.update_details',$summary->id) }}" method="POST"   enctype="multipart/form-summary">
                @csrf
                @method('post')
                    <input type="hidden" name="pre_booking_id" value="{{ $summary->id }}">
                    <input type="hidden" name="selected_status" class="selected_status" value="{{ $summary->id }}">
                <div class="row">

                    <div class="col-md-6">
                        <h6 class="text-uppercase">Basic Details</h6>
                        <div class="form-group">
                            <label for="">Name  <span style="color:red">*</span></label>
                            <input type="text" class="form-control"  name="Name"  value="{{ $summary->user->name }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="">Phone  <span style="color:red">*</span></label>
                            <input type="text" class="form-control phone"  name="Phone" value="{{ $summary->user->phone }}" maxlength="11" readonly>
                        </div>

                        <div class="form-group">
                            <label for="">Property Name  <span style="color:red">*</span></label>
                            <input type="text" class="form-control"  name="property_name"   value="{{ $summary->property->name }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h6 class="text-uppercase">Booking Details</h6>


                        <div class="form-group">
                            <label for="">Check In  <span style="color:red">*</span></label>
                            <input type="text" class="form-control datepick" id="check_in" name="check_in" required value="{{ $summary->check_in->format('d-m-Y') }}" >
                        </div>

                        <div class="form-group">
                            <label for="">Check Out  <span style="color:red">*</span></label>
                            <input type="text" class="form-control datepick" id="check_out" name="check_out" value="{{ $summary->check_out->format('d-m-Y') }}" maxlength="6" >
                        </div>
                        <div class="form-group">
                            <label for="">Adult  <span style="color:red">*</span></label>
                            <input type="text" class="form-control"  name="pax"  id="pax" value="{{ $summary->pax }}"   required>
                        </div>
                        <div class="form-group">
                            <label for="">Budget </label>
                            <input type="text" class="form-control"  name="budget" value="{{ $summary->budget }}" >
                        </div>

                    </div>

                    <div class="form-group">
                        <button class="btn btn-primary">Update</button>
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
</script>
    <script>
    $(document).ready(function() {
        $('#close_modal').on('click', function() {
            $('#budgetModal').hide();
        });

        $('#booking_summary').on('click', function() {
            $('#budgetModal').show();
        });
        $("#check_in").datepicker({
            dateFormat: 'dd-mm-yy',//check change
            onSelect: function(selectedDate) {
                $("#check_out").datepicker("option", "minDate", selectedDate);
            }
        });

        $("#check_out").datepicker({
            dateFormat: 'dd-mm-yy',//check change
            onSelect: function(selectedDate) {
                var selectedDate = $(this).val();
            }
        });
    });

    function check() {
        var date1 = new Date(document.getElementById('check_in').value);
        var date2 = new Date(document.getElementById('check_out').value);
        var diff = Math.abs(date2.getTime() - date1.getTime());
        var noofdays = Math.ceil(diff / (1000 * 3600 * 24));  
        if(date1  > date2){ 
            alert("Check-out date must be after check-in date!")
        }
        else {
            alert(noofdays);
        }
    }	
    </script>
@endsection
