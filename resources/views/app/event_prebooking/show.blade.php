@extends('layouts.app')
@section('title','Home Page')
@section('contents')
    <div class="card">
        <div class="card-body">
            <div class="row ">
                <div class="col-md-6 mb-4">
                    <h4 class="card-title text-uppercase">Event Pre Booking Details
                        <span class="badge badge-pill badge-info">{{ $summary->pre_booking_summary_status->name }}</span>
                    </h4>
                </div>

                @can('Wedding-Planning-Bookings-Pre-Bookings-update')
                    <div class="col-md-3 mb-4 form-inline">
                        <label for="" class="font-weight-bold text-uppercase">Current status &nbsp;</label>
                        <select name="new_status" id="new_status" class="form-control form-control-sm">
                            @foreach($pre_booking_summary_status as $key => $val)
                            <option value="{{ $key }}" @if($key == $summary->pre_booking_summary_status_id) selected @endif> {{ strtoupper( $val) }}</option>
                            @endforeach
                        </select>
                    </div>
                @endcan
                @php
                    $display = '';
                    if($summary->pre_booking_summary_status_id == 3 || $summary->pre_booking_summary_status_id == 4)
                        $display = 'display:none';
                @endphp
                @can('Wedding-Planning-Bookings-Pre-Bookings-update')
                <div class="col-md-3 mb-4 form-inline text-right" style="@php echo $display; @endphp">
                    <a href="{{ route('event-pre-booking.edit',$summary->id) }}" class="btn btn-sm btn-primary">Edit</a>
                </div>
                @endcan

                <div class="col-md-12">
                    <table class="table table-sm" id="pre_booking_details_table">
                        <tr>
                            <th>Name</th>
                            <td> {{ $summary->user->name }}</td>
                            <th>Pre Booking ID</th>
                            <td>{{  $summary->id  }}</td>
                            <th>Property Name</th>
                            <td> {{ $summary->property->name }}</td>
                        </tr>
                        <tr>
                        <th>Phone</th>
                            <td>{{  $summary->user->phone  }}</td>
                            <th>Check In</th>
                            <td> {{ $summary->check_in->format('d-m-Y') }}</td>
                            <th>Check Out</th>
                            <td> {{ $summary->check_out->format('d-m-Y') }}</td>
                        </tr>
                        <tr>
                            <th>Adult</th>
                            <td> {{ $summary->pax }}</td>
                            <th>Total Amount</th>
                            <td id="total_amount_listing_th"> {{ $summary->total_amount }}</td>
                        </tr>
                        <tr>
                            <th>Bride Name</th>
                            <td> {{ $summary->bride_name }}</td>
                            <th>Groom Name</th>
                            <td> {{ $summary->groom_name }}</td>
                             <!-- Empty column to align the button -->
                             <td colspan="1"></td>

                            @can('Venue/Resort-Booking-Bookings-read')
                            <td>
                                @if ($firstMatchingPrebookingId)
                                    <div class="col-md-3 mb-4 form-inline text-right">
                                        <a href="{{ route('pre-bookings.show',$firstMatchingPrebookingId) }}" class="btn btn-sm btn-outline-primary">View Venue/ Resort booking Details</a>
                                    </div>
                                @endif
                            </td>
                            @endcan
                        </tr>
                    </table>
                    <table class="table table-sm" id="preBookingDetails_table">
                        <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Event</th>
                                <th>Date</th>
                                <th>Timing</th>
                                <th>Particular</th>
                                <th>Amount</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        @php
                            $total = 0;
                            $key = 0;   

                        @endphp
                        @foreach($data as $key => $detail)
                            @php
                                //$total = $total  + $detail['amount'];
                            @endphp

                            <tr>
                                <th>{{ ++$key }}</th>
                                <td>{{ $detail['event'] }}</td>
                                <td> 

                                    {{ $detail['date'] }}
                                     
                                </td>
                                <td>{{ $detail['time'] }}</td>
                                <td> {{ $detail['particular'] }}</td>
                                <td>
                                    <a href="" class="update" data-summary={{ $summary->id }} data-name={{ $detail['data-name'] }} data-type="text" data-pk="{{ $detail['id'] }}" data-title="Enter amount">{{ $detail['amount'] }}</a>
                                </td>
                                <td>
                                    
                                    <a href="#" class="btn btn-sm btn-outline-primary view-button" data-image="{{ $detail['image_url'] }}">View</a>
                                </td>
                                 

                            </tr>
                        @endforeach
                        <tr>
                            <th colspan="6" class="text-right" >Total</th>
                            <th id="total_amount_th">{{ $summary->total_amount }}</th>
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
            </div>

        </div>
    </div>

    <div id="imageModal" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-body">
                    <img id="popupImage" src="" alt="Image" width="400" height="300">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirmation</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this item?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger" id="deleteButton">Delete</button>
                </div>
            </div>
        </div>
    </div>


    <div class="modal" id="confirmationModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Event Pre Booking Confirmation</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <form action="{{ route('event-pre-booking.update',$summary->id) }}" id="ConfirmBookingForm" method="POST">
                        @csrf
                        @method('put')
                        <input type="hidden" name="pre_booking_id" value="{{ $summary->id }}">
                        <input type="hidden" name="selected_status" class="selected_status" value="{{ $summary->id }}">
                        <!-- <div class="form-group">
                            <label for="">
                                User Budget
                            </label>
                            <input type="text" class="form-control" value="{{-- $summary->budget --}}" disabled>
                        </div> -->
                        <div class="form-group">
                            <label for="">
                                Final Amount
                            </label>
                            <input type="text" class="form-control final_amount" name="final_amount" value="{{ $summary->total_amount }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="">
                               Additional Discounts
                            </label>
                            <input type="text" class="form-control" name="additional_discount" value="">
                        </div>
                        <div class="form-group">
                            <label for="">
                                No Of Installment
                            </label>
                            <select name="installments" id="installments" class="form-control" required>
                                <option value="">Select Installments</option>
                                <option value="1">1</option>
                                <option value="2">2</option>
                                <option value="3">3</option>
                                <option value="4">4</option>
                                <option value="5">5</option>

                            </select>

                        </div>

                        <div class="form-group">
                            <label for="">
                                Remark
                            </label>
                            <textarea name="admin_remark"  class="form-control"  cols="30" rows="5"></textarea>
                        </div>
                        <button class="btn btn-sm btn-success btn-block">Confirm Booking</button>
                    </form>
                </div>

                <!-- Modal footer -->


            </div>
        </div>
    </div>
    <div class="modal" id="rejectModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"> Reject Pre Booking  </h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('event-pre-booking.update',$summary->id) }}" id="RejectPreBookingForm" method="POST">
                        @csrf
                        @method('put')
                        <input type="hidden" name="pre_booking_id" value="{{ $summary->id }}">
                        <input type="hidden" name="selected_status" class="selected_status" value="">
                        <!-- <div class="form-group">
                            <label for="">
                                User Budget
                            </label>
                            <input type="text" class="form-control" value="{{-- $summary->budget --}}" disabled>
                        </div> -->
                        <div class="form-group">
                            <label for="">
                                Final Amount
                            </label>
                            <input type="text" class="form-control final_amount" name="final_amount" value="{{ $summary->total_amount }}" readonly>
                        </div>

                        <div class="form-group">
                            <label for="">
                                Remarks
                            </label>
                            <textarea name="admin_remark"  class="form-control"  cols="30" rows="5" required></textarea>
                        </div>
                        <button class="btn btn-sm btn-success btn-block">Reject Booking</button>
                    </form>
                </div>

                <!-- Modal footer -->


            </div>
        </div>
    </div>
    <div class="modal" id="cancelModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Cancel Booking  </h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <form action="{{ route('event-pre-booking.update',$summary->id) }}" id="RejectPreBookingForm" method="POST">
                        @csrf
                        @method('put')
                        <input type="hidden" name="pre_booking_id" value="{{ $summary->id }}">
                        <input type="hidden" name="selected_status" class="selected_status" value="">
                        <!-- <div class="form-group">
                            <label for="">
                                User Budget
                            </label>
                            <input type="text" class="form-control" value="{{-- $summary->budget --}}" disabled>
                        </div> -->
                        <div class="form-group">
                            <label for="">
                                Final Amount
                            </label>
                            <input type="text" class="form-control final_amount" name="final_amount" value="{{ $summary->total_amount }}" readonly>
                        </div>

                        <div class="form-group">
                            <label for="">
                                Remarks
                            </label>
                            <textarea name="admin_remark"  class="form-control"  cols="30" rows="5" required></textarea>
                        </div>
                        <button class="btn btn-sm btn-success btn-block">Cancel Booking</button>
                    </form>
                </div>

                <!-- Modal footer -->


            </div>
        </div>
    </div>
@endsection
@section('js')
<link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/jquery-editable/css/jquery-editable.css" rel="stylesheet"/>
    <script>$.fn.poshytip={defaults:null}</script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/jquery-editable/js/jquery-editable-poshytip.min.js"></script>
    <script>
        $('.view-button').on('click', function() {
            var imageUrl = $(this).data('image');
            $('#popupImage').attr('src', imageUrl);
            $('#imageModal').modal('show');
        });
        $('.delete-icon').click(function() {

            var id = $(this).data('id');
            $('#deleteConfirmationModal').modal('show');
            
            // Set the ID of the item to be deleted
            $('#deleteButton').data('id', id);
        });
        $('#deleteButton').click(function() {
            var id = $(this).data('id');
            var summary_id = "{{ $summary->id }}";
            var icon = $(this); // Store the icon reference

            // Use the jQuery variable as needed
            console.log(summary_id);

            $.ajax({
                url: '/pre-bookings/delete/' + id,
                type: 'DELETE',
                data: {
                    _token: '{{ csrf_token() }}', // Add the CSRF token for security
                },
                success: function(response) {
                    // Handle success response, e.g., show a success message, update the UI, etc.
                    //$("#total_amount_th").html(response.total_amount);
                    icon.closest('tr').hide();
                    $('#deleteConfirmationModal').modal('hide');
                    setTimeout(function(){// wait for 5 secs(2)
                          location.reload(); // then reload the page.(3)
                     }, 500); 
                   
                },
                error: function(xhr) {
                    // Handle error response, e.g., show an error message, etc.
                    console.log(xhr.responseText);
                }
            });
        });
        $.fn.editable.defaults.mode = 'inline';

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            }
        });

        $('.update').editable({
                url: "{{ route('event_pre_booking_qty_details.update') }}",
                type: 'text',
                pk: 1,
                name: 'name',
                summary: 1,
                title: 'Enter name',
                success: function(response, newValue) {
                    // Handle the successful response here
                    console.log('Success:', response);
                    console.log('New value:', newValue);
                    $("#total_amount_th").html(response.total_amount);
                    $("#total_amount_listing_th").html(response.total_amount);
                    $(".final_amount").val(response.total_amount);
                    $("#amount_"+response.this_id).html(response.amount);

                }
        });

        $('#new_status').change(function(){
            let current_val = parseInt($(this).val());
            let old_val = {{ $summary->pre_booking_summary_status_id  }};
            $('.selected_status').val(current_val);
            if(current_val !== old_val && current_val === 2 ) {
                $('#confirmationModal').modal('show');
            }else if(current_val !== old_val && current_val === 4 ) {
                $('#rejectModal').modal('show');
            }else if(current_val !== old_val && current_val === 4 ) {
                 $('#rejectModal').modal('show');
            }else if(current_val !== old_val && current_val === 3) {
                $('#cancelModal').modal('show');
            }
        })
    </script>
@endsection
