<!DOCTYPE html>
<html>
<head>
    <title>Customer Invoice</title>
</head>
<style type="text/css">
    body{
        font-family: 'Roboto Condensed', sans-serif;
    }
    .m-0{
        margin: 0px;
    }
    .p-0{
        padding: 0px;
    }
    .pt-5{
        padding-top:5px;
    }
    .mt-10{
        margin-top:10px;
    }
    .text-center{
        text-align:center !important;
    }
    .w-100{
        width: 100%;
    }
    .w-50{
        width:50%;   
    }
    .w-85{
        width:85%;   
    }
    .w-15{
        width:15%;   
    }
    .logo img{
        width:45px;
        height:45px;
        padding-top:30px;
    }
    .logo span{
        margin-left:8px;
        top:19px;
        position: absolute;
        font-weight: bold;
        font-size:25px;
    }
    .gray-color{
        color:#5D5D5D;
    }
    .text-bold{
        font-weight: bold;
    }
    .border{
        border:1px solid black;
    }
    table tr,th,td{
        border: 1px solid #d2d2d2;
        border-collapse:collapse;
        padding:7px 8px;
    }
    table tr th{
        background: #F4F4F4;
        font-size:15px;
    }
    table tr td{
        font-size:13px;
    }
    table{
        border-collapse:collapse;
    }
    .box-text p{
        line-height:10px;
    }
    .float-left{
        float:left;
    }
    .total-part{
        font-size:16px;
        line-height:12px;
    }
    .total-right p{
        padding-right:20px;
    }
</style>
<body>
<div class="head-title">
    <h1 class="text-center m-0 p-0">Customer Invoice</h1>
</div>
<div class="add-detail mt-10">
    <div class="w-50 float-left mt-10">
        <br><br>
        <p class="m-0 pt-5 text-bold w-100">Invoice Id - <span class="gray-color">#{{ $bookings->id }}</span></p>
    </div>
    <!-- <div class="w-50 float-left logo mt-10">
        <img src="{{ asset('assets/images/logo.png') }}" style="width: 100%">
    </div> -->
    <div style="clear: both;"></div>
</div>
<div class="table-section bill-tbl w-100 mt-10">
    <table class="table w-100 mt-10">
        <tr>
            <th class="w-50">User details</th>
            <th class="w-50">Amount details</th>
        </tr>
        <tr>
            <td>
                <div class="box-text">
                    <p>Name : {{ $bookings->user->name }}</p>
                    <p>Email : {{ $bookings->user->email }}</p>
                    <p>Phone No : {{ $bookings->user->phone }}</p>
                    <p>Property Name : {{ $bookings->property->name }}</p>
                    <p>Check In : {{ $bookings->check_in->format('d-m-Y') }}</p>
                    <p>Check Out : {{ $bookings->check_out->format('d-m-Y') }}</p>
                    <p>Adult : {{ $bookings->pax ?? '' }}</p>
                    <p>User remark : {{ $bookings->user_remarks ?? '' }}</p>
                </div>
            </td>
            <td>
                <div class="box-text">
                    
                    <p>Total Amount : {{ number_format($bookings->amount ?? '', 2, '.', ',') }}</p>
                    <p>Discount : {{ $bookings->booking_payment_summary->discount }}</p>
                    <p>Amount : {{ number_format($bookings->booking_payment_summary->amount, 2, '.', ',') }}</p>
                    <p>Paid : {{ number_format($bookings->booking_payment_summary->paid, 1, '.', ',') }}</p>
                    <p>Due : {{ number_format($bookings->booking_payment_summary->due, 1, '.', ',') }}</p>
                </div>
            </td>
        </tr>
    </table>
</div>
<div class="table-section bill-tbl w-100 mt-10">
    <table class="table w-100 mt-10">
        <tr>
            <th class="w-100">Booking Details</th>
        </tr>
    </table>
</div>
<div class="table-section bill-tbl w-100 mt-10">
    <table class="table w-100 mt-10">
        <tr>
            <th class="w-50">#</th>
            <th class="w-50">Event</th>
            <th class="w-50">Date</th>
            <th class="w-50">Start Time - End Time</th>
            <th class="w-50">Particular</th>
            <th class="w-50">Amount</th>
        </tr>
        @php
            $total = 0;
            $old_date = '';
            $total_room = $bookings->property->total_rooms;

        @endphp

        @foreach($bookings->booking_details as $key => $val)
            @php
                //dd($val->artistPerson->name);
                $show_date = false;
                if($old_date != $val->date){
                    $old_date = $val->date;
                    $show_date = true;
                }

                if ($val->artistPerson) {
                    
                    $particular = 'Artist Person - '.$val->artistPerson->name;
                    $amount = $val->artist_amount;
                } elseif ($val->decoration) {
                    $particular = 'Decoration - '.$val->decoration->name;
                    $amount = $val->decor_amount;
                }
                $total = $total  + $amount;
            
            @endphp
            <tr align="center">
                <th>{{ 1 +$key }}</th>
                <td> @if($show_date)

                        {{ $val->date->format('d-m-Y') }}
                    @else

                    @endif
                </td>
                <td>{{ $val->events->name }}</td>
                <td> 

                    {{ $val->start_time }} - {{ $val->end_time }}
                    
                </td>
                <td>{{ $particular }}</td>
                <td> {{ $amount }}</td>
            </tr>
        @endforeach
        
        <tr>
            <td colspan="7">
                <div class="total-part">
                    <div class="total-left w-85 float-left" align="right">
                        <p>Total = </p>
                        {{-- <p>Tax (18%)</p>
                        <p>Total Payable</p> --}}
                    </div>
                    <div class="total-right w-15 float-left text-bold" align="right">
                        <p>{{ number_format($total ?? '', 2, '.', ',') }}</p>
                        {{-- <p>$20</p>
                        <p>$330.00</p> --}}
                    </div>
                    <div style="clear: both;"></div>
                </div> 
            </td>
        </tr>
    </table>
</div>

<div class="table-section bill-tbl w-100 mt-10">
    <table class="table w-100 mt-10">
        <tr>
            <th class="w-100">Payment Details</th>
        </tr>
    </table>
</div>
<div class="table-section bill-tbl w-100 mt-10">
    <table class="table w-100 mt-10">
        <tr>
            <th class="w-50">#</th>
            <th class="w-50">Date</th>
            <th class="w-50">Installment No</th>
            <th class="w-50">Amount</th>
            <th class="w-50">Status</th>
            <th class="w-50">Payment Mode</th>
            <th class="w-50">Remarks</th>
        </tr>
        @foreach($bookings->booking_payment_summary->booking_payment_details as $key => $val)
            <tr align="center">
                <td>{{ 1+ $loop->index }}</td>
                <td>{{ $val->date }}</td>
                <td>{{ $val->installment_no }}</td>
                <td>{{ number_format($val->amount, 2, '.', ',') }}</td>
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
        
        <tr>
            <td colspan="7">
                <div class="total-part">
                    <div class="total-left w-85 float-left" align="right">
                        <p>Total Amount = </p>
                        {{-- <p>Tax (18%)</p>
                        <p>Total Payable</p> --}}
                    </div>
                    <div class="total-right w-15 float-left text-bold" align="right">
                        <p>{{ number_format($bookings->amount ?? '', 2, '.', ',') }}</p>
                        {{-- <p>$20</p>
                        <p>$330.00</p> --}}
                    </div>
                    <div style="clear: both;"></div>
                </div> 
            </td>
        </tr>
    </table>
</div>

</html>