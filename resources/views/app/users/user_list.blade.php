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
                    <h4 class="card-title text-uppercase">Users List</h4>
                </div>
                <div class="col-md-6 text-right">
                    <a href="{{ route('users.create') }}" class="btn btn-sm btn-primary"> Add</a>
                </div>
            </div>


            <div class="table-responsive">
                <table class="table" id="example1">
                    <thead class="thead-dark">
                    <tr>
                        <th width="5%">#</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Roles</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($users as $key => $val)
                        <tr>
                            <th>{{ 1+ $key }}</th>
                            <td>{{ $val->name }}</td>
                            <td>{{ $val->email }}</td>
                            <td>{{ $val->phone }}</td>
                            <td>
                                @foreach($val->roles as $key => $val1)
                                    <span class="badge badge-info">{{ $val1->name }}</span>
                                @endforeach
                            </td>
                            <td>

                                @if($val->status == 0)
                                    <span class="badge badge-warning">In Active</span>
                                @elseif($val->status == 1)
                                    <span class="badge badge-success">Active</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    @if(request()->has('trashed'))
                                        <a href="{{ route('users.restore', $val->id) }}" class="btn btn-success">Restore</a>
                                    @else
                                        <form method="POST" action="{{ route('users.destroy', $val->id) }}">
                                            @csrf
                                            <input name="_method" type="hidden" value="DELETE">
                                            <button type="submit" class="btn btn-danger delete" title='Delete'>Delete</button>
                                        </form>
                                    @endif
                                </div>

                            </td>
                        </tr>

                    @endforeach
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
    <script type="text/javascript">
        $(document).ready(function() {
            $('.delete').click(function(e) {
                if(!confirm('Are you sure you want to delete this user?')) {
                    e.preventDefault();
                }
            });
        });
    </script>
@endsection
