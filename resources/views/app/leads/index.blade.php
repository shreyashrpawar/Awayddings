@extends('layouts.app')
@section('title','Home Page')
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
                            <tr style="background: #7cfffc">
                        @elseif($val->status == 'booked')
                            <tr style="background: #bdff7c">
                        @elseif($val->status == 'lost')
                            <tr style="background: #ff5959">
                        @elseif($val->status == 'recce' or $val->status == 'potential_recce' or $val->status == 'recce_done')
                            <tr style="background: #fff389">
                        @else
                            <tr>
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
                                        <span class="badge badge-info">{{ $val->status }}</span>
                                    @elseif($val->status == 'lost')
                                        <span class="badge badge-danger">{{ $val->status }}</span>
                                    @elseif( $val->status == 'recce' or $val->status == 'potential_recce' or $val->status == 'recce_done')
                                        <span class="badge badge-warning">{{ $val->status }}</span>
                                    @else
                                        <span class="badge badge-success">{{ $val->status }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($val->status != 'lost')
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
                        <form id="edit-lead-form-{{$val->id}}" action="{{ route('leads.update', ['lead' => $val->id]) }}"
                              method="post">
                            {{ method_field('PATCH') }}
                            {{ csrf_field() }}
                            <label>Current Status: <b>{{strtoupper($val->status)}}</b></label>
                            <div class="form-group">
                                <label>Update Status to</label>
                                <select name="lead_status" id="lead_status" class="form-control">
                                    <option value="contacted">
                                        Contacted
                                    </option>
                                    <option value="potential_recce">Potential Recce
                                    </option>
                                    <option value="recce">Recce</option>
                                    <option value="recce_done">Recce
                                        Done
                                    </option>
                                    <option value="booked">Booked</option>
                                    <option value="lost">Lost</option>
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
