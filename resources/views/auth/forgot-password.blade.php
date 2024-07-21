@extends('layouts.auth')
@section('title','Forgot Password')
@section('contents')
    <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth px-0">
            <div class="row w-100 mx-0">
                <div class="col-lg-4 mx-auto">
                    <div class="auth-form-light text-left py-5 px-4 px-sm-5 border">
                        <div class="brand-logo text-center">
                            <img src="{{ asset('assets/images/logo.png') }}" alt="logo">
                        </div>
                        <h4>Forgot Password !</h4>
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                        @if (session('message'))
                            <div class="alert alert-success">
                                {{ session('message') }}
                            </div>
                        @endif
                        <form class="pt-3" method="POST" action="{{ route('forgot-password-submit') }}">
                            @csrf
                            <div class="form-group">
                                <input type="email" class="form-control form-control-lg"  placeholder="Please enter your email address" name="email">
                            </div>
                            <div class="mt-3">
                                <button class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">SEND PASSWORD RESET LINK</button>
                            </div>
                            <div class="my-2 d-flex justify-content-between align-items-center">
                                <div class="form-check">

                                </div>

                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- content-wrapper ends -->
    </div>
@endsection
