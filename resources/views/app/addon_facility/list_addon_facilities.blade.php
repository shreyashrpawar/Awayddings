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
                    <h4 class="card-title text-uppercase">Addon Facilities Listing</h4>
                </div>
                @can('Event-Management-Facility-create')
                <div class="col-md-6 text-right">
                    <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addFacilityModal">Add New Facility</button>
                </div>
                @endcan
            </div>
            <div class="table-responsive">
                <table class="table" id="example1">
                    <thead class="thead-dark">
                    <tr>
                        <th width="5%">#</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th width="10%">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($facilities->count() > 0)
                        @foreach($facilities as $key => $val)
                            <tr>
                                <th>{{ 1+ $key }}</th>
                                <td>
                                    {{ $val->name }}
                                </td>
                                <td>
                                @can('Event-Management-Facility-update')
                                    <button class="status-toggle btn btn-sm {{ $val->status == 1 ? 'btn-outline-success' : 'btn-outline-danger' }}" data-id="{{ $val->id }}" data-url="{{ route('addon_facilities.update', $val->id) }}" data-name="{{$val->name}}">

                                        {{ $val->status == 1 ? 'Active' : 'Inactive' }}
                                    </button>
                                    @else
                                        <button class="btn btn-sm {{ $val->status == 1 ? 'btn-outline-success disabled' : 'btn-outline-danger disabled' }}" disabled>
                                            {{ $val->status == 1 ? 'Active' : 'Inactive' }}
                                        </button>
                                @endcan    
                                </td>
                                <td>
                                @can('Event-Management-Facility-update')
                                    <div class="btn-group">
                                        <button class="btn btn-sm btn-outline-primary" data-toggle="modal"  data-target="#editFacilityModal{{ $val->id }}">Edit</button>
                                    </div>
                                @endcan

                                    <div class="btn-group">
                                        <a href="{{ route('addon_facility_details.index', $val->id) }}" class="btn btn-sm btn-outline-info">View Details</a>
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

                <!-- Modal for adding new addon facility -->
                <div class="modal fade" id="addFacilityModal" tabindex="-1" role="dialog" aria-labelledby="addFacilityModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addFacilityModalLabel">Add New Facility</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('addon_facilities.store') }}" method="post">
                                    @csrf
                                    <div class="form-group">
                                        <label for="facility_name">Facility Name:</label>
                                        <input type="text" class="form-control" id="facility_name" name="facility_name" required>
                                    </div>
                                    <button class="btn btn-primary" type="submit">Add Facility</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modals for editing addon facilities -->
                @foreach($facilities as $facility)
                    <div class="modal fade" id="editFacilityModal{{ $facility->id }}" tabindex="-1" role="dialog" aria-labelledby="editFacilityModalLabel{{ $facility->id }}" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editFacilityModalLabel{{ $facility->id }}">Edit Facility</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('addon_facilities.update', $facility->id) }}" method="post">
                                        @csrf
                                        <input type="hidden" name="status" value="{{$facility->status}}">
                                        <div class="form-group">
                                            <label for="facility_name">Facility Name:</label>
                                            <input type="text" class="form-control" id="facility_name" name="facility_name" value="{{ $facility->name }}" required>
                                        </div>
                                        <button class="btn btn-primary" type="submit">Update Facility</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
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
                const name = button.data('name')
                const currentStatus = button.hasClass('btn-outline-success') ? 1 : 0;
                const newStatus = currentStatus === 1 ? 0 : 1;

                $.ajax({
                    type: 'POST',
                    url: url,
                    data: {
                        facility_name : name,
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
