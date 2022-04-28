@extends('layouts.app')
@section('title','Home Page')
@section('contents')
    <div class="card">
        <div class="card-body">
            <div class="row form-group">
                <div class="col-md-6">
                    <h4 class="card-title text-uppercase">Pre Booking List</h4>
                </div>
                <div class="col-md-6 text-right">
{{--                    <a href="{{ route('property.create') }}" class="btn btn-sm btn-primary">Add</a>--}}
                </div>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead class="thead-dark">
                    <tr>
                        <th width="5%">#</th>
                        <th>User</th>
                        <th>Phone</th>
                        <th>Property</th>
                        <th>Check In</th>
                        <th>Check Out</th>
                        <th>PAX</th>
                        <th>Client Budget</th>

                        <th>Status</th>
                        <th width="10%">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($booking_summary->count() > 0)
                        @foreach($booking_summary as $key => $val)
                            <tr>
                                <th>{{ 1+ $key }}</th>
                                <td>
                                    {{ $val->user->name }}
                                </td>
                                <td>{{  $val->user->phone  }}</td>
                                <td>{{ $val->property->name }}</td>
                                <td>{{ $val->check_in->format('d-m-Y') }}</td>
                                <td>{{ $val->check_out->format('d-m-Y') }}</td>
                                <td>{{ $val->pax }}</td>
                                <td>{{ $val->budget }}</td>
                                <td>
                                    <span class="badge badge-pill badge-info text-uppercase">{{ $val->pre_booking_summary_status->name }}</span>

                                </td>
                                <td>
                                    <div class="btn-group">
                                        @can('property show')
                                            <a href="{{ route('pre-bookings.show',$val->id) }}" class="btn btn-sm btn-outline-primary">View</a>
                                        @endcan

                                    </div>

                                </td>
                            </tr>

                        @endforeach
                    @else
                        <tr>
                            <td colspan="9" class="text-center">No Result Found</td>
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
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('.activeButton').click(async (e) => {
            let property_id = e.target.value;
            let resp  = $.ajax({
                url: '/property/status',
                data: {
                    property_id: property_id,
                    status : 1
                },
                type: 'POST',
                success:function(resp){
                    location.reload();
                }
            })
        })

        $('.inactiveButton').click(async (e) => {
            let property_id = e.target.value;

            $.ajax({
                url: '/property/status',
                data: {
                    property_id: property_id,
                    status : 0
                },
                type: 'POST',
                success:(resp) => {
                    location.reload();
                }
            })
        })
    </script>
@endsection
