@extends('layouts.app')
@section('title','Home Page')
@section('css')
    <style>
        .form-group.row {
            margin-bottom: auto!important;
        }
    </style>
@endsection
@section('contents')
    <div class="card" style="background-color: #FFFFFF;">
        <div class="card-header ">
            <h1 class="card-title text-uppercase text-black">Event Booking: {{ $bookings->user->name }} || {{ $bookings->property->name }}

            <span class="badge badge-pill badge-info">{{ $bookings->booking_summaries_status }}</span>

            </h1>
        </div>
        <div class="card-body">
            <div class="row ">

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header bg-primary">
                        <h5 class="card-title text-center text-white">User details</h5>
                        </div>
                        <div class="card-body">
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Name</label>
                                <div class="col-sm-9">
                                  <input type="text" class="form-control" value="{{ $bookings->user->name }}" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Email</label>
                                <div class="col-sm-9">
                                  <input type="text" class="form-control" value="{{ $bookings->user->email }}" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Phone No</label>
                                <div class="col-sm-9">
                                  <input type="text" class="form-control" value="{{ $bookings->user->phone }}" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Property Name</label>
                                <div class="col-sm-9">
                                  <input type="text" class="form-control" value="{{ $bookings->property->name }}" readonly>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Check In</label>
                                <div class="col-sm-9">
                                  <input type="text" class="form-control" value="{{ $bookings->check_in->format('d-m-Y') }}" readonly>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Check Out</label>
                                <div class="col-sm-9">
                                  <input type="text" class="form-control" value="{{ $bookings->check_out->format('d-m-Y') }}" readonly>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Adult</label>
                                <div class="col-sm-9">
                                  <input type="text" class="form-control" value="{{ $bookings->pax ?? '' }}" readonly>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label class="col-sm-3 col-form-label">Remarks</label>
                                <div class="col-sm-9">
                                <input type="text" class="form-control" value="{{ $bookings->booking_summaries_status_remarks ?? '' }}" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @can('booking update')
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header bg-warning">
                            <h5 class="card-title text-center text-white align-items-center">Amount details</h5>
                            </div>
                            <div class="card-body">

                            <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">User remark</label>
                                    <div class="col-sm-9">
                                    <input type="text" class="form-control" value="{{ $bookings->user_remarks ?? '' }}" readonly>
                                    </div>
                                </div>


                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Total Amount</label>
                                    <div class="col-sm-9">
                                    <input type="text" class="form-control" value="{{ number_format($bookings->amount ?? '', 2, '.', ',') }}" readonly>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Discount</label>
                                    <div class="col-sm-9">
                                    <input type="text" class="form-control" value="{{ $bookings->booking_payment_summary->discount }}" readonly>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Amount</label>
                                    <div class="col-sm-9">
                                    <input type="text" class="form-control" value="{{ number_format($bookings->booking_payment_summary->amount, 2, '.', ',') }}" readonly>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Paid</label>
                                    <div class="col-sm-9">
                                    <input type="text" class="form-control" value="{{ number_format($bookings->booking_payment_summary->paid, 1, '.', ',') }}" readonly>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label class="col-sm-3 col-form-label">Due</label>
                                    <div class="col-sm-9">
                                    <input type="text" class="form-control" value="{{ number_format($bookings->booking_payment_summary->due, 1, '.', ',') }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endcan

                <hr style="border: 2px #ffffff solid; width: 100%;">

                
                <div class="col-md-12">
                    <ul class="nav nav-pills" id="pills-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Booking Details</a>
                        </li>
                        @can('booking update')
                            <li class="nav-item">
                                <a class="nav-link" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Payment Details</a>
                            </li>
                        @endcan

                        @php //if ($bookings->booking_invoice)
                            /*<!-- <li>
                                <a class="nav-link btn btn-success bg-success" href="{{ $bookings->booking_invoice->invoice_url ?? '' }}" style="color: #FFFFFF;">Invoice Download</a>
                            </li> -->*/
                        @endphp
                        

                    </ul>
                    <div class="tab-content" id="pills-tabContent">
                        <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                            <table class="table table-sm">
                                <thead class="thead-dark">
                                <tr>
                                    <th>#</th>
                                    <th>Date</th>
                                    <th>Event</th>
                                    <th>Start Time - End Time</th>
                                    <th>Particular</th>
                                    <th>Amount</th>
                                </tr>
                                </thead>
                                <tbody>

                                @php
                                    $total = 0;
                                    $old_date = '';
                                    $total_room = $bookings->property->total_rooms;
                                    $key = 1;

                                @endphp
                                @foreach($bookings->booking_details as $val)
                                    @php
                                        //dd($val);
                                        $show_date = false;
                                        if($old_date != $val->date){
                                            $old_date = $val->date;
                                            $show_date = true;
                                        }

                                        if ($val->artistPerson) {
                                            
                                            $particular = 'Artist Person - '.$val->artistPerson->name;
                                            $amount = $val->artist_amount;
                                            @endphp
                                            <tr>
                                                <th>{{ $key++ }}</th>
                                                <td> 

                                                        {{ $val->date->format('d-m-Y') }}
                                                    
                                                    
                                                </td>
                                                <td>{{ $val->events->name }}</td>
                                                <td> 

                                                    {{ $val->start_time }} - {{ $val->end_time }}
                                                    
                                                </td>
                                                <td>{{ $particular }}</td>
                                                <td> {{ $amount }}</td>

                                            </tr>
                                            @php
                                        } 
                                        if ($val->decoration) {
                                            $particular = 'Decoration - '.$val->decoration->name;
                                            $amount = $val->decor_amount;
                                            //dd($val->decor_amount);
                                            @endphp
                                            <tr>
                                                <th>{{ $key++ }}</th>
                                                <td> 

                                                        {{ $val->date->format('d-m-Y') }}
                                                    
                                                    
                                                </td>
                                                <td>{{ $val->events->name }}</td>
                                                <td> 

                                                    {{ $val->start_time }} - {{ $val->end_time }}
                                                    
                                                </td>
                                                <td>{{ $particular }}</td>
                                                <td> {{ $amount }}</td>

                                            </tr>
                                            @php
                                        }
                                        $total = $total  + $amount;
                                    
                                    @endphp
                                @endforeach
                                @foreach($bookings->bookingAddsonDetails as  $val)
                                    @php
                                    $amount = $val->total_amount;
                                    if ($val->addson_facility) {
                                        $particular = 'Facility - '.$val->addson_facility->name;
                                    } elseif ($val->facility_details) {
                                        $particular = 'Facility Details - '.$val->facility_details->name;
                                    } 
                                    $total = $total  + $amount;
                                    @endphp
                                <tr>
                                        <th>{{ $key++ }}</th>
                                        <td> NA </td>
                                        <td> NA </td>
                                        <td> NA </td>
                                        <td>{{ $particular }}</td>
                                        <td> {{ $amount}}</td>

                                    </tr>
                                @endforeach
                                @foreach($bookings->bookingAddsonArtistPerson as $val)
                                    @php
                                    $additional_particular = '';
                                    if ($val->addson_artist_person) {
                                        $additional_particular = 'Additional Artist Person - '.$val->addson_artist_person->name;
                                    }
                                    $artistParticular = '';
                            
                                    if ($val->addson_artist) {
                                        $artistParticular = 'Additional Artist - '.$val->addson_artist->name;
                                    }

                                    $amount = $val->addson_artist_amount;
                                    $total = $total  + $amount;
                                    @endphp
                                <tr>
                                        <th>{{ $key++ }}</th>
                                        <td> NA </td>
                                        <td> NA </td>
                                        <td> NA </td>
                                        <td>{{ $additional_particular.', '.$artistParticular }}</td>
                                        <td> {{ $amount}}</td>

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
                                    @can('booking update')
                                        <th>Actions</th>
                                    @endcan
                                </tr>
                                </thead>
                                <tbody>

                                @php
                                    $count = 1;
                                @endphp
                                @foreach($bookings->booking_payment_summary->booking_payment_details as $key => $val)
                                
                                    <tr>
                                        <th>{{ 1+ $loop->index }}</th>
                                        <td id="installment_date{{ $val->installment_no }}" data-installment_date="{{ $val->date }}">{{ $val->date }}</td>
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
                                        @can('booking update')
                                            <td>
                                                @if ($count == 1+ $loop->index)
                                                    <button class="btn btn-sm btn-outline-primary installmentStatus" data-id="{{ $val->id }}" data-installment_no="{{ $val->installment_no }}" data-payment_mode="{{ $val->payment_mode ?? '' }}" data-status="{{ $val->status ?? '' }}" data-remarks="{{ $val->remarks ?? '' }}">Edit</button>
                                                    @if ($val->status == 2)
                                                        @php
                                                            $count ++;
                                                        @endphp
                                                    @endif
                                                @endif
                                            </td>
                                        @endcan
                                        
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

    {{-- Status modal --}}
    <div class="modal" id="installmentStatusModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h4 class="modal-title">Installment Status</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <!-- Modal body -->
                <div class="modal-body">
                    <form action="#" id="installmentStatusForm" method="POST">
                        @csrf
                        @method('put')
                        <input type="hidden" name="booking_payment_details_id" id="booking_payment_details_id">
                        <input type="hidden" name="installment_no" id="installment_no">
                        <input type="hidden" name="user_email" id="user_email" value="{{ $bookings->user->email }}">

                        <input type="hidden" name="total_amount" value="{{ $bookings->booking_payment_summary->amount }}">

                        <input type="hidden" id="next_installment_date" name="next_installment_date">

                        <div class="form-group">
                            <label for="Status">Status</label>
                            <select name="status" id="status" class="form-control" required>
                                <option value="" disabled selected>Select Status</option>
                                {{-- <option value="1">Pending</option> --}}
                                <option value="2">Paid</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="payment_mode">Payment Mode</label>
                            <select name="payment_mode" id="payment_mode" class="form-control" required>
                                <option value="">Select Payment Mode</option>
                                <option value="Cash">Cash</option>
                                <option value="Online">Online</option>
                                <option value="Cheque">Cheque</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="remark">Remarks</label>
                            <textarea name="remarks" id="remarks"  class="form-control"  cols="30" rows="5"></textarea>
                        </div>

                        <button type="submit" class="btn btn-sm btn-success btn-block">Save Changes</button>
                    </form>
                </div>
                <!-- Modal footer -->
            </div>
        </div>
    </div>

@endsection
@section('js')
    <script>
        let formModal = $('#installmentStatusModal');
        let $form = $('#installmentStatusForm');
        $('.installmentStatus').click(function(){
            let id = $(this).data('id');
            let installment_no = $(this).data('installment_no');
            let next_installment_no = installment_no + 1;
            
            let next_installment_date = $('#installment_date'+next_installment_no).data('installment_date');
            let payment_mode = $(this).data('payment_mode');
            let remarks = $(this).data('remarks');
            let status = $(this).data('status');
            let updateUrl = `{{ url('event-booking') }}`+'/'+id;
            
            if(status == 2){       
                formModal.find('.modal-body #status').val(status);         
                formModal.find('.modal-body #status').attr("disabled", "disabled");
            }else{
                formModal.find('.modal-body #status').val('');
                formModal.find('.modal-body #status').attr("disabled", false);
            }
           
            formModal.find('.modal-body #booking_payment_details_id').val(id);
            formModal.find('.modal-body #installment_no').val(installment_no);
            formModal.find('.modal-body #payment_mode').val(payment_mode);
            formModal.find('.modal-body #remarks').val(remarks);
            formModal.find('.modal-body #next_installment_date').val(next_installment_date);
            $form.attr('action', updateUrl);
            formModal.modal('show');
        });
    </script>
@endsection
