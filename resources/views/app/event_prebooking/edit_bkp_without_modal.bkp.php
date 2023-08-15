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
                            <input type="text" class="form-control"  name="pan" value="{{ $summary->budget }}" >
                        </div>

                    </div>

                    <div class="form-group">
                        <button type="button" class="btn btn-primary" id="booking_summary">Booking Summary</button>
                        <!-- <button @click="openModal">Open Modal</button> -->
                        <button class="btn btn-primary">Update</button>
                    </div>
                </div>
            </form>
        </div>

    </div>
    <!-- <div id="app">
  <modal></modal>
</div> -->

    <div class="modal" id="budgetModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Pre Booking Confirmation</h4>
                    <button type="button" id="close_modal" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <form action="{{ route('pre-bookings.update',$summary->id) }}" id="ConfirmBookingForm" method="POST">
                        @csrf
                        @method('put')
                        <input type="hidden" name="pre_booking_id" value="{{ $summary->id }}">
                        <input type="hidden" name="property_id" id="property_id" value="{{ $summary->property_id }}">
                        <input type="hidden" name="selected_status" class="selected_status" value="{{ $summary->id }}">
                        <div class="col-md-12">
                            <table class="table table-sm">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>#</th>
                                        <th>Date</th>
                                        <th>Particular</th>
                                        <th>Quantity</th>
                                        <th>Rate</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @php
                                    $total = 0;
                                    $old_date = '';
                                    $total_room = $summary->property->total_rooms;

                                @endphp
                                @foreach($summary->pre_booking_details as $key => $val)
                                    @php
                                            $double_room = 0;
                                            $triple_room = 0;
                                            $current_room = 0;
                                            $show_date = false;
                                            if($old_date != $val->date){
                                                $old_date = $val->date;
                                                $show_date = true;
                                            }
                                        if($val->hotel_chargable_type_id == 1){
                                            $double_room = $val->qty;
                                            $current_room =+ $val->qty;
                                        }elseif($val->hotel_chargable_type_id == 2){
                                            $triple_room = $val->qty;
                                            $current_room =+ $val->qty;
                                        }
                                        if($val->hotel_chargable_type_id != 1 && $val->hotel_chargable_type_id != 2){
                                                $threshold_rooms = ($total_room * $val->threshold)/100;
                                                if($current_room >= $threshold_rooms){
                                                    $total = $total  + ($val->qty * $val->rate);
                                                }
                                            } else{
                                                $total = $total  + ($val->qty * $val->rate);
                                            }
                                    @endphp

                                    <tr>
                                        <th>{{ 1 +$key }}</th>
                                        <td> @if($show_date)

                                                {{ $val->date->format('d-m-Y') }}
                                            @else

                                            @endif
                                        </td>
                                        <td>{{ $val->hotel_chargable_type->name }}</td>
                                        <td>

                                            {{ $val->qty }}
                                        </td>
                                        <td>{{ $val->rate }}</td>

                                        <td>{{ $val->qty * $val->rate }}</td>

                                    </tr>
                                @endforeach
                                <tr>
                                    <th colspan="5" class="text-right" >Total</th>
                                    <th >{{ $total }}</th>
                                </tr>
                                </tbody>


                            </table>
                            <div>
                                <label for="" class="font-weight-bold">User Remarks</label>
                                <p>

                                    {{ $summary->user_remarks }}
                                </p>
                            </div>
                            <div>
                                <label for="" class="font-weight-bold">Internal Remarks</label>
                                <p>

                                    {{ $summary->admin_remarks }}
                                </p>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Modal footer -->


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
//   import Modal from './Modal.vue';
  
//   const app = new Vue({
//     el: '#app',
//     components: {
//       Modal
//     },
//     methods: {
//       openModal() {
//         this.$refs.modal.showModal = true; // Show the modal
//       }
//     }
//   });
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
                // When the first date picker changes, update the minimum selectable date of the second date picker
                $("#check_out").datepicker("option", "minDate", selectedDate);
            }
        });

        $("#check_out").datepicker({
            dateFormat: 'dd-mm-yy',//check change
            onSelect: function(selectedDate) {
                var selectedDate = $(this).val();

                $.ajax({
                    url: '/properties/budget-calculator',
                    method: 'GET',
                    data: { check_out: selectedDate, check_in: $("#check_in").val(), property_id: $("#property_id").val(), adults: $("#pax").val() },
                    success: function(response) {
                        console.log(response);
                    // Handle the response and update the data container
                    $('#dataContainer').html(response);
                    },
                    error: function() {
                    // Handle error cases
                    }
                });
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
