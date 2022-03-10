@extends('layouts.app')
@section('title','Home Page')
@section('contents')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="card">
                <div class="card-body">
                    <div class="row form-group">
                        <div class="col-md-6">
                            <h4 class="card-title">Users List</h4>
                        </div>
                        <div class="col-md-6 text-right">
                            <a href="{{ route('users.create') }}" class="btn btn-sm btn-primary"> Register</a>
                        </div>
                    </div>


                    <div class="table-responsive">
                        <table class="table">
                            <thead class="thead-dark">
                            <tr>
                                <th width="5%">#</th>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Phone</th>
                                <th>Roles</th>
                                <th>Status</th>
                                <th width="10%">Actions</th>
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
                                        @foreach($val->roles as $key => $val)
                                            <span class="badge badge-info">{{ $val->name }}</span>
                                        @endforeach
                                    </td>
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
                                        </div>

                                    </td>
                                </tr>

                            @endforeach



                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <!-- content-wrapper ends -->
        <!-- partial:../../partials/_footer.html -->

        <footer class="footer">
            <div class="d-sm-flex justify-content-center justify-content-sm-between">
                <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2018 <a href="https://www.bootstrapdash.com/" target="_blank" class="text-muted">Bootstrapdash</a>. All rights reserved.</span>
                <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Hand-crafted & made with <i class="mdi mdi-heart-outline text-primary"></i></span>
            </div>
        </footer>
        <!-- partial -->
    </div>
@endsection
