@extends('layouts.app')
@section('title','Home Page')
@section('contents')
    <div class="card">
        <div class="card-body">
            <div class="row ">
                <div class="col-md-6 mb-4">
                    <h4 class="card-title text-uppercase">Booking Details

                    </h4>
                </div>

                <div class="col-md-12">
                    <table class="table table-sm">
                        <tr>
                            <th>Name</th>
                            <td> {{ $bookings->user->name }}</td>
                            <th>Property Name</th>
                            <td> {{ $bookings->property->name }}</td>
                        </tr>
                        <tr>
                            <th>Check In</th>
                            <td> {{ $bookings->check_in->format('d-m-Y') }}</td>
                            <th>Check Out</th>
                            <td> {{ $bookings->check_out->format('d-m-Y') }}</td>
                        </tr>
                        <tr>
                            <th>Adult</th>
                            <td> {{ $bookings->pax }}</td>

                        </tr>
                        <tr>
                            <th>Total Amount</th>
                            <td> {{ $bookings->amount }}</td>
                            <th>Discount</th>
                            <td> {{ $bookings->booking_payment_summary->discount }}</td>
                            <th>Amount</th>
                            <td> {{ $bookings->booking_payment_summary->amount }}</td>
                            <th>Paid</th>
                            <td> {{ $bookings->booking_payment_summary->paid }}</td>
                            <th>Due</th>
                            <td> {{ $bookings->booking_payment_summary->due }}</td>
                        </tr>
                    </table>

                </div>
                <div class="col-md-12 mt-4">
                    <ul class="nav nav-pills mb-3" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Booking Details</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Payment Details</a>
                        </li>

                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
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
                                    $total_room = $bookings->property->total_rooms;

                                @endphp
                                @foreach($bookings->booking_details as $key => $val)
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
                        </div>
                        <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                            <table class="table table-sm">
                                <thead class="thead-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Installment No</th>
                                    <th>Amount</th>
                                    <th>Status</th>
                                    <th>Payment Mode</th>
                                    <th>Remarks</th>
                                </tr>
                                </thead>
                                <tbody>


                                @foreach($bookings->booking_payment_summary->booking_payment_details as $key => $val)
                                    <tr>
                                        <th>{{ 1+ $loop->index }}</th>
                                        <td>{{ $val->date }}</td>
                                        <td>{{ $val->installment_no }}</td>
                                        <td>{{ $val->amount }}</td>
                                        <td>
                                            @if($val->status == 1)
                                                PENDING
                                            @elseif($val->status == 2)
                                                PAID
                                            @endif

                                           </td>
                                        <td>{{ $val->payment_mode }}</td>
                                        <td>{{ $val->remarks }}</td>
                                    </tr>
                                @endforeach

                                </tbody>


                            </table>

                        </div>

                    </div>
                </div>







            </div>

        </div>
    </div>

@endsection
@section('js')
    <script>

    </script>
@endsection
