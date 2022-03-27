@extends('layouts.app')
@section('title','Home Page')
@section('contents')
    <div class="card">
        <div class="card-body">
            <div class="row form-group">
                <div class="col-md-6">
                    <h4 class="card-title">Vendors List</h4>
                </div>
                <div class="col-md-6 text-right">
                    <a href="{{ route('vendors.create') }}" class="btn btn-sm btn-primary">Register Vendor</a>
                </div>
            </div>


            <div class="table-responsive">
                <table class="table table-sm">
                    <thead class="thead-dark">
                    <tr>
                        <th width="5%">#</th>
                        <th>Name</th>
                        <th>Phone</th>
                        <th>City</th>
                        <th>Address</th>
                        <th>Status</th>
                        <th width="10%">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($vendors as $key => $val)
                        <tr>
                            <th>{{ 1+ $key }}</th>
                            <td>{{ $val->name }}</td>
                            <td>{{ $val->phone }}</td>

                            <td>{{ $val->city }}</td>
                            <td>{{ $val->address }}</td>
                            <td>
                                @if($val->status == 0)
                                    <span class="badge badge-warning">In Active</span>
                                @else
                                    <span class="badge badge-success">Active</span>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="{{ route('vendors.show',$val->id) }}" class="btn btn-sm btn-outline-primary">View</a>
                                    <a href="{{ route('vendors.edit',$val->id) }}" class="btn btn-sm btn-outline-success">Edit</a>
                                    <a href="{{ url('property/vendor/'.$val->id.'/associate') }}" class="btn btn-sm btn-outline-warning">Associate</a>
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
