@extends('layouts.app')
@section('title','Home Page')
@section('contents')
    <div class="card">
        <div class="card-body">
            <div class="row ">
                <div class="col-md-6 mb-4">
                    <h4 class="card-title text-uppercase">Pre Booking Details
                        <span class="badge badge-pill badge-info">{{ $summary->pre_booking_summary_status->name }}</span>
                    </h4>
                </div>
                
                @can('pre-booking update')
                    <div class="col-md-3 mb-4 form-inline">
                        <label for="" class="font-weight-bold text-uppercase">Current status &nbsp;</label>
                        <select name="new_status" id="new_status" class="form-control form-control-sm">
                            @foreach($pre_booking_summary_status as $key => $val)
                            <option value="{{ $key }}" @if($key == $summary->pre_booking_summary_status_id) selected @endif> {{ strtoupper( $val) }}</option>
                            @endforeach
                        </select>
                    </div>
                @endcan
                <div class="col-md-3 mb-4 form-inline text-right">
                    <a href="{{ route('pre-bookings.edit',$summary->id) }}{{-- url('pre-bookings.edit', ['id' => $summary->id]) --}}" class="btn btn-sm btn-primary">Edit</a>
                </div>
                

                <div class="col-md-12">
                    <table class="table table-sm" id="pre_booking_details_table">
                        <tr>
                            <th>Name</th>
                            <td> {{ $summary->user->name }}</td>
                            <th>Phone</th>
                            <td>{{  $summary->user->phone  }}</td>
                            <th>Property Name</th>
                            <td> {{ $summary->property->name }}</td>
                        </tr>
                        <tr>
                            <th>Check In</th>
                            <td> {{ $summary->check_in->format('d-m-Y') }}</td>
                            <th>Check Out</th>
                            <td> {{ $summary->check_out->format('d-m-Y') }}</td>
                        </tr>
                        <tr>
                            <th>Adult</th>
                            <td> {{ $summary->pax }}</td>
                            <th>Budget</th>
                            <td> {{ $summary->budget }}</td>
                        </tr>
                        <tr>
                            <th>Bride Name</th>
                            <td> {{ $summary->bride_name }}</td>
                            <th>Groom Name</th>
                            <td> {{ $summary->groom_name }}</td>
                        </tr>
                    </table>
                    <table class="table table-sm" id="preBookingDetails_table">
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
                                    <a href="" class="update" data-name="qty" data-type="text" data-pk="{{ $val->id }}" data-title="Enter quantity">{{ $val->qty }}</a>
                                </td>
                                <td>
                                    <a href="" class="update" data-name="rate" data-type="text" data-pk="{{ $val->id }}" data-title="Enter rate">{{ $val->rate }}</a>
                                </td>

                                <td id="amount_{{ $val->id }}">{{ $val->qty * $val->rate }}</td>

                            </tr>
                        @endforeach
                        <tr>
                            <th colspan="5" class="text-right" >Total</th>
                            <th id="total_amount_th">{{ $total }}</th>
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
    <div class="modal" id="confirmationModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Pre Booking Confirmation</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <form action="{{ route('pre-bookings.update',$summary->id) }}" id="ConfirmBookingForm" method="POST">
                        @csrf
                        @method('put')
                        <input type="hidden" name="pre_booking_id" value="{{ $summary->id }}">
                        <input type="hidden" name="selected_status" class="selected_status" value="{{ $summary->id }}">
                        <div class="form-group">
                            <label for="">
                                User Budget
                            </label>
                            <input type="text" class="form-control" value="{{ $summary->budget }}" disabled>
                        </div>
                        <div class="form-group">
                            <label for="">
                                Final Amount
                            </label>
                            <input type="text" class="form-control" name="final_amount" value="{{ $total }}" readonly>
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
                    <form action="{{ route('pre-bookings.update',$summary->id) }}" id="RejectPreBookingForm" method="POST">
                        @csrf
                        @method('put')
                        <input type="hidden" name="pre_booking_id" value="{{ $summary->id }}">
                        <input type="hidden" name="selected_status" class="selected_status" value="">
                        <div class="form-group">
                            <label for="">
                                User Budget
                            </label>
                            <input type="text" class="form-control" value="{{ $summary->budget }}" disabled>
                        </div>
                        <div class="form-group">
                            <label for="">
                                Final Amount
                            </label>
                            <input type="text" class="form-control" name="final_amount" value="{{ $total }}" readonly>
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
                    <form action="{{ route('pre-bookings.update',$summary->id) }}" id="RejectPreBookingForm" method="POST">
                        @csrf
                        @method('put')
                        <input type="hidden" name="pre_booking_id" value="{{ $summary->id }}">
                        <input type="hidden" name="selected_status" class="selected_status" value="">
                        <div class="form-group">
                            <label for="">
                                User Budget
                            </label>
                            <input type="text" class="form-control" value="{{ $summary->budget }}" disabled>
                        </div>
                        <div class="form-group">
                            <label for="">
                                Final Amount
                            </label>
                            <input type="text" class="form-control" name="final_amount" value="{{ $total }}" readonly>
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
        $.fn.editable.defaults.mode = 'inline';
  
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': '{{csrf_token()}}'
            }
        }); 

        $('.update').editable({
                url: "{{ route('pre_booking_qty_details.update') }}",
                type: 'text',
                pk: 1,
                name: 'name',
                title: 'Enter name',
                success: function(response, newValue) {
                    // Handle the successful response here
                    console.log('Success:', response);
                    console.log('New value:', newValue);
                    $("#total_amount_th").html(response.total_amount);
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
