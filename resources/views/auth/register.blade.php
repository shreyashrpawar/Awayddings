@extends('layouts.auth')
@section('title','User Registration')
@section('contents')
    <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth px-0">
            <div class="row w-100 mx-0">
                <div class="col-lg-4 mx-auto">
                    <div class="auth-form-light text-left py-5 px-4 px-sm-5 border">
                        <div class="brand-logo">
                            <img src="{{ asset('assets/images/logo.png') }}" alt="logo">
                        </div>
                        <h4>Registration of the Vendor</h4>

                        <form class="pt-3" method="POST" action="{{ route('users.store') }}">
                            @csrf
                            <div class="form-group">
                                <input type="text" class="form-control form-control-lg" id="exampleInputEmail1" placeholder="Name" name="name">
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control form-control-lg" id="exampleInputEmail1" placeholder="Phone" name="phone">
                            </div>
                            <div class="form-group">
                                <input type="email" class="form-control form-control-lg"  placeholder="email" name="email">
                            </div>

                            <div class="form-group">
                                <input type="password" class="form-control form-control-lg"  placeholder="password" name="password">
                            </div>

                            <div class="mt-3">
                                <button class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" >Register</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- content-wrapper ends -->
    </div>
@endsection
