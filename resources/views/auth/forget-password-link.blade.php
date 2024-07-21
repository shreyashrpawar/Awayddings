@extends('layouts.auth')
@section('title','Reset Password')
@section('contents')
    <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth px-0">
            <div class="row w-100 mx-0">
                <div class="col-lg-4 mx-auto">
                    <div class="auth-form-light text-left py-5 px-4 px-sm-5 border">
                        <div class="brand-logo text-center">
                            <img src="{{ asset('assets/images/logo.png') }}" alt="logo">
                        </div>
                        <h4>Reset Password !</h4>
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
                        @if (session('error'))
                            <div class="alert alert-danger">
                                {{ session('error') }}
                            </div>
                        @endif
                        <form class="pt-3" method="POST" action="{{ route('reset-password-submit') }}">
                            @csrf
                            <input type="hidden" name="token" value="{{ $token }}">
                            <input type="hidden" name="email" value="{{ $_GET['email'] ?? ''}}">

                            <div class="form-group">
                                <input type="password" class="form-control form-control-lg"  placeholder="Enter New Password"  name="password">
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control form-control-lg"  placeholder="Enter Confirm Password"  name="password_confirmation">
                            </div>

                            <div class="mt-3">
                                <button class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn">RESET PASSWORD</button>
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
