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
                    <h4 class="card-title text-uppercase">Artists</h4>
                </div>
                @can('Event-Management-Artists-create')
                <div class="col-md-6 text-right">
                    <a href="{{ route('artists.create') }}" class="btn btn-sm btn-primary">Add</a>
                </div>
                @endcan
            </div>
            <div class="table-responsive">
                <table class="table" id="example1">
                    <thead class="thead-dark">
                    <tr>
                        <th width="5%">#</th>
                        <th>Name</th>
                        <th>Event</th>

                        <th>Status</th>
                        <th width="10%">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($artists->count() > 0)
                        @foreach($artists as $key => $val)
                            <tr>
                                <th>{{ 1+ $key }}</th>
                                <td>
                                    {{ $val->name }}
                                </td>
                                <td>
                                    @if($val->events->count() > 0)
                                        @foreach($val->events as $event)
                                            {{ $event->name }}@if(!$loop->last), @endif
                                        @endforeach
                                    @else
                                        No Events
                                    @endif
                                </td>
                                <td>
                                @can('Event-Management-Artists-update')
                                        <button class="status-toggle btn btn-sm {{ $val->status == 1 ? 'btn-outline-success' : 'btn-outline-danger' }}" data-id="{{ $val->id }}"   data-name="{{$val->name}}" data-url="{{ route('artists.update',$val->id) }}">
                                            {{ $val->status == 1 ? 'Active' : 'Inactive' }}
                                        </button>
                                    @else
                                        <button class="btn btn-sm {{ $val->status == 1 ? 'btn-outline-success disabled' : 'btn-outline-danger disabled' }}" data-name="{{$val->name}}" disabled>
                                            {{ $val->status == 1 ? 'Active' : 'Inactive' }}
                                        </button>
                                @endcan
                                </td>
                                <td>
                                @can('Event-Management-Artists-update')
                                    <div class="btn-group">
                                        <a href="{{ route('artists.edit',$val->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                    </div>
                                @endcan
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

          $('.status-toggle').on('click', function() {
                const button = $(this);
                const url = button.data('url');
                const name = button.data('name');
                const currentStatus = button.hasClass('btn-outline-success') ? 1 : 0;
                const newStatus = currentStatus === 1 ? 0 : 1;

                $.ajax({
                    type: 'POST',
                    url: url,
                    data: {
                        artist_name: name,
                        status: newStatus,
                        _token: '{{ csrf_token() }}',
                    },
                    success: function(response) {
                        // Update the button text and color after successful update
                        button.text(newStatus === 1 ? 'Active' : 'Inactive');
                        button.removeClass('btn-outline-success btn-outline-danger');
                        button.addClass(newStatus === 1 ? 'btn-outline-success' : 'btn-outline-danger');
                    },
                    error: function(xhr, status, error) {
                        // Handle errors if needed
                        console.error(error);
                    }
                });
            });
        });
    </script>
@endsection
