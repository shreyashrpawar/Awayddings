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
                    <h4 class="card-title text-uppercase">Leads List</h4>
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
                        <th>Bride & Groom</th>
                        <th>Wedding Date</th>
                        <th>Pax</th>
                        {{--                    <th>Origin</th>--}}
                        <th>Created On</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($leads as $key => $val)
                        @if($val->status == 'new')
                            <tr style="background: whitesmoke">
                        @elseif( $val->status == 'recce_planned' or $val->status == 'potential_recce' or $val->status == 'recce_done')
                            <tr style="background: #7cfffc">
                        @elseif( $val->status == 'lost_general_inquiry')
                            <tr style="background: #ff8989">
                        @elseif( $val->status == 'under_discussion')
                            <tr style="background: #ffea99">
                        @else
                            <tr style="background: #b9fd84">
                                @endif
                                <th>{{ $leads->firstItem() + $loop->index }}</th>
                                <td>{{ $val->name }}</td>
                                <td>{{ $val->email }}</td>
                                <td>{{ $val->mobile }}</td>
                                <td>{{ $val->bride_groom }}</td>
                                <td>{{ $val->wedding_date }}</td>
                                <td>{{ $val->pax }}</td>
                                {{--                    <td>{{ $val->origin }}</td>--}}
                                <td>{{ date('d-m-Y', strtotime($val->created_at))}}</td>
                                <td>
                                    @if($val->status == 'new')
                                        <span class="badge badge-light">{{ $val->status }}</span>
                                    @elseif( $val->status == 'recce_planned' or $val->status == 'potential_recce' or $val->status == 'recce_done')
                                        <span class="badge badge-info">{{ $val->status }}</span>
                                    @elseif( $val->status == 'lost_general_inquiry')
                                        <span class="badge badge-danger">{{ $val->status }}</span>
                                    @elseif( $val->status == 'under_discussion')
                                        <span class="badge badge-warning">{{ $val->status }}</span>
                                    @else
                                        <span class="badge badge-success">{{ $val->status }}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        @if($val->status != 'lost_general_inquiry')
                                            <button type="button" class="btn btn-sm btn-outline-primary"
                                                    data-toggle="modal"
                                                    data-target="#editLead-{{$val->id}}">Action<i
                                                    class="mdi mdi-pencil ml-1"></i>
                                            </button>
                                        @endif
                                        <button type="button" class="btn btn-sm btn-primary"
                                                data-toggle="modal"
                                                data-target="#viewRemark-{{$val->id}}">View<i
                                                class="mdi mdi-eye ml-1"></i>
                                        </button>
                                        <div class="btn-group">
                            </tr>

                            @endforeach
                    </tbody>
                </table>
                {!! $leads->render() !!}
            </div>
        </div>
    </div>

    @foreach($leads as $key => $val)
        <div class="modal fade" id="viewRemark-{{$val->id}}" tabindex="-1" role="dialog" aria-labelledby="ModalLabel"
             style="display: none;" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ModalLabel">View Remark for: {{$val->bride_groom}} </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <p>{{trim($val->remarks) == "" ? 'No remark found' : $val->remarks}}</p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach

    @foreach($leads as $key => $val)
        <div class="modal fade" id="editLead-{{$val->id}}" tabindex="-1" role="dialog" aria-labelledby="ModalLabel"
             style="display: none;" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="ModalLabel">Update Lead for: {{$val->bride_groom}} </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">×</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form id="edit-lead-form-{{$val->id}}"
                              action="{{ route('leads.update', ['lead' => $val->id]) }}"
                              method="post">
                            {{ method_field('PATCH') }}
                            {{ csrf_field() }}
                            <label>Current Status: <b>{{strtoupper($val->status)}}</b></label>
                            <div class="form-group">
                                <label>Update Status to</label>
                                <select name="lead_status" id="lead_status" class="form-control">
                                    <option value="recce_planned">
                                        Recce Planned
                                    </option>
                                    <option value="recce_done">Recce Done
                                    </option>
                                    <option value="under_discussion">Under Discussion</option>
                                    <option value="booked">Booked</option>
                                    <option value="lost_general_inquiry">Lost General Inquiry</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="message-text" class="col-form-label">Remark:</label>
                                <textarea class="form-control" id="lead_remarks" name="lead_remarks"
                                          rows="8">{{$val->remarks}}</textarea>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <input type="submit" class="btn btn-success" value="Update" form="edit-lead-form-{{$val->id}}">
                        <button type="button" class="btn btn-light" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
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
@endsection
