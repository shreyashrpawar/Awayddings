@extends('layouts.app')
@section('title','Home Page')
@section('contents')
    <div class="main-panel">
        <div class="content-wrapper">
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
                                <th>Name</th>
                                <th>Location</th>
                                <th>Status</th>
                                <th width="10%">Actions</th>
                            </tr>
                            </thead>
                            <tbody>



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
