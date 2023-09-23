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
                <div class="col-md-6 text-right">
                    <button class="btn btn-sm btn-primary" data-toggle="modal" data-target="#addFacilityDetailsModal">Add New Facility Details</button>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table" id="example1">
                    <thead class="thead-dark">
                    <tr>
                        <th width="5%">#</th>
                        <th>Price</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th width="10%">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($facilityDetails->count() > 0)
                        @foreach($facilityDetails as $key => $val)
                            <tr>
                                <th>{{ 1+ $key }}</th>
                                <td>
                                    {{ $val->price }}
                                </td>
                                <td>
                                    {{ $val->description }}
                                </td>
                                <td>
                                    <button class="status-toggle btn btn-sm {{ $val->status == 1 ? 'btn-outline-success' : 'btn-outline-danger' }}" data-id="{{ $val->id }}">
                                        {{ $val->status == 1 ? 'Active' : 'Inactive' }}
                                    </button>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('addon_facility_details.edit', $val->id) }}" class="btn btn-sm btn-outline-primary" data-toggle="modal" data-target="#editFacilityDetailsModal{{ $val->id }}">Edit</a>
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

                <!-- Modal for adding new addon facility details -->
                <div class="modal fade" id="addFacilityDetailsModal" tabindex="-1" role="dialog" aria-labelledby="addFacilityDetailsModalLabel" aria-hidden="true">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="addFacilityDetailsModalLabel">Add New Facility Details</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="{{ route('addon_facility_details.store') }}" method="post" enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group">
                                        <label for="facility_id">Select Addon Facility:</label>
                                        <select name="facility_id" id="facility_id" class="form-control" required>
                                            @foreach($facilities as $facility)
                                                <option value="{{ $facility->id }}">{{ $facility->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="price">Price:</label>
                                        <input type="number" class="form-control" id="price" name="price" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Description:</label>
                                        <textarea class="form-control" id="description" name="description"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="description">Image:</label>
                                        <input type="file" id="facility_details_image" name="facility_details_image" accept="image/*">
                                    </div>
                                    <button class="btn btn-primary" type="submit">Add Facility Details</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Modals for editing addon facility details -->
                @foreach($facilityDetails as $detail)
                    <div class="modal fade" id="editFacilityDetailsModal{{ $detail->id }}" tabindex="-1" role="dialog" aria-labelledby="editFacilityDetailsModalLabel{{ $detail->id }}" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="editFacilityDetailsModalLabel{{ $detail->id }}">Edit Facility Details</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('addon_facility_details.update', $detail->id) }}" method="post">
                                        @csrf
                                        @method('PUT')
                                        <div class="form-group">
                                            <label for="price">Price:</label>
                                            <input type="number" class="form-control" id="price" name="price" value="{{ $detail->price }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="description">Description:</label>
                                            <textarea class="form-control" id="description" name="description">{{ $detail->description }}</textarea>
                                        </div>

                                        <div class="form-group">
                                            <label for="description">Image:</label>
                                            <input type="file" id="facility_details_image" name="facility_details_image" accept="image/*">
                                            @if ($detail->image)
                                                <img src="{{ $detail->image->url }}" alt="Facility Image" width="300" height="200">
                                            @else
                                                <p>No image available.</p>
                                            @endif
                                        </div>
                                        <button class="btn btn-primary" type="submit">Update Facility Details</button>
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
                const id = button.data('id');
                const currentStatus = button.hasClass('btn-outline-success') ? 1 : 0;
                const newStatus = currentStatus === 1 ? 0 : 1;

                $.ajax({
                    type: 'POST',
                    url: '{{ route('facilityDetails_updateStatus') }}',
                    data: {
                        id: id,
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