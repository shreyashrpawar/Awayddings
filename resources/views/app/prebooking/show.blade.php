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
                <div class="offset-3 col-md-3 mb-4 form-inline">
                    <label for="" class="font-weight-bold text-uppercase">Current status &nbsp;</label>
                    <select name="new_status" id="new_status" class="form-control form-control-sm">
                        @foreach($pre_booking_summary_status as $key => $val)
                        <option value="{{ $key }}" @if($key == $summary->pre_booking_summary_status_id) selected @endif> {{ strtoupper( $val) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-12">
                    <table class="table table-sm">
                        <tr>
                            <th>Name</th>
                            <td> {{ $summary->user->name }}</td>
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
                    </table>
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
                        <label for="" class="font-weight-bold">Admin Remarks</label>
                        <p>

                            {{ $summary->admin_remarks }}
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>
    <div class="modal" id="myModal">
        <div class="modal-dialog">
            <div class="modal-content">

                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title"> Pre Booking status change</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>

                <!-- Modal body -->
                <div class="modal-body">
                    <form action="">

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
                            <input type="text" class="form-control" value="{{ $total }}">
                        </div>
                        <div class="form-group">
                            <label for="">
                                Admin Remark
                            </label>
                            <textarea name="admin_remark"  class="form-control"  cols="30" rows="5"></textarea>
                        </div>
                        <button class="btn btn-sm btn-success btn-block">Confirm</button>
                    </form>
                </div>

                <!-- Modal footer -->


            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>
        $('#new_status').change(function(){
            let current_val = $(this).val();
            let old_val = {{ $summary->pre_booking_summary_status_id  }};
            if(current_val != old_val){
                $('#myModal').modal('show')
            }

        })
    </script>
@endsection
