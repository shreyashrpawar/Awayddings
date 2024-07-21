@extends('layouts.app')
@section('title','Home Page')
@section('contents')
    <div class="card">
        <div class="card-body">
            <div class="form-group row">
                <div class="col-md-6">
                    <h4 class="card-title">Property Rate</h4>
                </div>
                <div class="col-md-6 text-right">

                </div>
            </div>

            <div class="container-fluid">
                <form action="">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">Start Date</label>
                                <input type="date" class="form-control" name="start_date" value="{{ Carbon\Carbon::parse($start_date)->format('Y-m-d') }}" >
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="">End Date</label>
                                <input type="date" class="form-control" name="end_date" value="{{  Carbon\Carbon::parse($end_date)->format('Y-m-d')  }}"  >
                            </div>
                        </div>
                        <div class="col-md-1">
                            <button class="btn  btn-primary mt-4">Search</button>
                        </div>
                    </div>
                </form>

            </div>


            <div class="table-responsive">
                <table class="table">
                    <thead class="thead-dark">
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Type</th>
                        <th width="10%">Amount</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if(count($property_rates) > 0)
                    @foreach($property_rates as $key => $val)
                        <tr>
                            <th>{{ $loop->index + 1 }}</th>
                            <td>   {{ $val['hotel_chargable_type_id']->name }}</td>
                            <td>
                                {{ $val['date']->format('d-m-Y') }}
                            </td>
                            <td>
                                <input type="text" value="{{$val['amount']}}" class="form-control amountInput"  data="{{ $val }}">
                            </td>
                        </tr>
                    @endforeach
                    @else
                        <tr>
                            <th colspan="4" class="text-center text-uppercase">Please set up the default price of the rooms</th>
                        </tr>
                    @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('js')
    <script>
        $('.amountInput').change(function () {
            let amount = $(this).val();
            let data = JSON.parse($(this).attr('data'));
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/property/rate',
                type: 'POST',
                data: {
                    id: (data.id)? data.id: '',
                    property_id:data.property_id,
                    hotel_chargable_type_id:data.hotel_chargable_type_id.id,
                    amount:amount,
                    date:data.date,
                    available:data.available,
                    occupancy:data.occupancy,
                    type:'amount'
                },
                success:function(resp){
                    console.log(resp);
                    if(resp.success){
                        alert(resp.message);
                        location.reload();
                    }
                }
            })
        })

        $('.availableInput').change(function () {
            let available = $(this).val();
            let data = JSON.parse($(this).attr('data'));
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/property/rate',
                type: 'POST',
                data: {
                    id: (data.id)? data.id: '',
                    property_id:data.property_id,
                    hotel_chargable_type_id:data.hotel_chargable_type_id.id,
                    amount:data.amount,
                    date:data.date,
                    available:available,
                    occupancy:data.occupancy,
                    type:'available'
                },
                success:function(resp){
                    console.log(resp);
                    if(resp.success){
                        alert(resp.message);
                        location.reload();
                    }
                }
            })
        })
        $('.occupancyInput').change(function () {
            let occupancy = $(this).val();
            let data = JSON.parse($(this).attr('data'));
            $.ajax({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                url: '/property/rate',
                type: 'POST',
                data: {
                    id: (data.id)? data.id: '',
                    property_id:data.property_id,
                    hotel_chargable_type_id:data.hotel_chargable_type_id.id,
                    amount:data.amount,
                    date:data.date,
                    available:data.available,
                    occupancy:occupancy,
                    type:'occupancy'
                },
                success:function(resp){
                    console.log(resp);
                    if(resp.success){
                        alert(resp.message);
                        location.reload();
                    }

                }
            })
        })
    </script>
@endsection
