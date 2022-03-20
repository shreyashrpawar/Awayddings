@extends('layouts.app')
@section('title','Home Page')
@section('contents')
    <div class="card">
        <div class="card-body">
            <div class="row form-group">
                <div class="col-md-6">
                    <h4 class="card-title">Property List</h4>
                </div>
                <div class="col-md-6 text-right">
                    <a href="{{ route('property.create') }}" class="btn btn-sm btn-primary">Register</a>
                </div>
            </div>
            <div class="table-responsive">
                <table class="table">
                    <thead class="thead-dark">
                    <tr>
                        <th width="5%">#</th>
                        <th>Image</th>
                        <th>Name</th>
                        <th>Location</th>
                        <th>Status</th>
                        <th width="10%">Actions</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($properties->count() > 0)
                        @foreach($properties as $key => $val)
                            <tr>
                                <th>{{ 1+ $key }}</th>
                                <td>
                                    <img src="{{ $val->featured_image }}" alt="" width="50px" height="50px">
                                </td>
                                <td>{{ $val->name }}</td>
                                <td>{{ $val->location->name }}</td>
                                <td>
                                    @if($val->status == 0)
                                        <span class="badge badge-warning">In Active</span>
                                    @else
                                        <span class="badge badge-success">Active</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('property.show',$val->id) }}" class="btn btn-sm btn-outline-primary">View</a>
                                        <a href="{{ route('property.edit',$val->id) }}" class="btn btn-sm btn-outline-warning">Edit</a>
                                        <a href="{{ route('rate.show',$val->id) }}" class="btn btn-sm btn-outline-info">Rates</a>
                                    </div>

                                </td>
                            </tr>

                        @endforeach
                    @else
                        <tr>
                            <td colspan="5" class="text-center">No Result Found</td>

                        </tr>
                    @endif


                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
