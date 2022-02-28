@extends('layouts.app')
@section('title','Home Page')
@section('contents')
    <div class="main-panel">
        <div class="content-wrapper">
            <div class="card">
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col-md-6">
                            <h4 class="card-title">Property Rate</h4>
                        </div>
                        <div class="col-md-6 text-right">

                        </div>
                    </div>

                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Property Name</label>
                                    <select name="property_id" id="" class="form-control">
                                        <option value="">Select Property Name</option>
                                        @foreach($properties as $key => $val)
                                            <option value="{{$key}}">{{ $val }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">Start Date</label>
                                    <input type="date" class="form-control" name="start_date" >
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="">End Date</label>
                                    <input type="date" class="form-control" name="end_date" >
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="">Type</label>
                                    <select name="" id="" class="form-control">
                                        <option value="">Select Type</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <button class="btn  btn-primary mt-4">Search</button>
                            </div>
                        </div>

                    </div>


                    <div class="table-responsive">
                        <table class="table">
                            <thead class="thead-dark">
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Type</th>
                                <th>Amount</th>
                                <th>Rooms</th>

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
