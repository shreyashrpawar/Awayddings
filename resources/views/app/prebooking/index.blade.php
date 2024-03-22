@extends('layouts.app')
@section('title','Home Page')
@section('css')
    <link rel="stylesheet" href="{{ asset('datatable/datatables-bs4/css/dataTables.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('datatable/datatables-responsive/css/responsive.bootstrap4.min.css')}}">
    <link rel="stylesheet" href="{{ asset('datatable/datatables-buttons/css/buttons.bootstrap4.min.css')}}">
    <style>
        thead.thead-dark {
            color: white;
        }
    </style>
@endsection
@section('contents')
    <div class="card">
        <div class="card-body">
            <div class="row form-group">
                <div class="col-md-6">
                    <h4 class="card-title text-uppercase">Pre Booking List</h4>
                </div>
                <div class="col-md-6 text-right">
                    {{--<a href="{{ route('property.create') }}" class="btn btn-sm btn-primary">Add</a>--}}
                </div>
            </div>
            <div class="table-responsive">
                <table class="table" id="example1">
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
                <th>{{ 1 + $key }}</th>
                <td>{{ optional($val->user)->name }}</td>
                <td>{{ optional($val->user)->phone }}</td>
                <td>{{ optional($val->property)->name }}</td>
                <td>{{ $val->check_in->format('d-m-Y') }}</td>
                <td>{{ $val->check_out->format('d-m-Y') }}</td>
                <td>{{ $val->pax }}</td>
                <td>{{ $val->budget }}</td>
                <td>
                    <span class="badge badge-pill badge-info text-uppercase">{{ optional($val->pre_booking_summary_status)->name }}</span>
                </td>
                <td>
                    <div class="btn-group">
                        @can('property-read')
                            <a href="{{ route('pre-bookings.show', $val->id) }}" class="btn btn-sm btn-outline-primary">View</a>
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
    <script src="{{ asset('datatable/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('datatable/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('datatable/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ asset('datatable/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('datatable/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ asset('datatable/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ asset('datatable/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('datatable/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ asset('datatable/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ asset('datatable/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ asset('datatable/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ asset('datatable/datatables-buttons/js/buttons.colVis.min.js') }}"></script>
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
    <script>
        $(function () {
          $("#example1").DataTable({
            "responsive": true, "lengthChange": false, "autoWidth": false,
            "buttons": ["csv", "pdf", "print"]
          }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
          $('#example2').DataTable({
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
          });
        });
    </script>
@endsection
