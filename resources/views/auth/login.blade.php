@extends('layouts.auth')
@section('title','Login Page')
@section('contents')
    <div class="container-fluid page-body-wrapper full-page-wrapper">
        <div class="content-wrapper d-flex align-items-center auth px-0">
            <div class="row w-100 mx-0">
                <div class="col-lg-4 mx-auto">
                    <div class="auth-form-light text-left py-5 px-4 px-sm-5 border">
                        <div class="brand-logo text-center">
                            <img src="{{ asset('assets/images/logo.png') }}" alt="logo">
                        </div>
                        <h4>Welcome Back !</h4>
                        <h6 class="font-weight-light">Sign in to continue.</h6>
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
                        <form class="pt-3" method="POST" action="{{ url('login') }}">
                            @csrf
                            <div class="form-group">
                                <input type="email" class="form-control form-control-lg"  placeholder="Username" name="email">
                            </div>
                            <div class="form-group">
                                <input type="password" class="form-control form-control-lg"  placeholder="Password"  name="password">
                            </div>
                           
                            <div class="mt-3">
                                <button class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" >SIGN IN</button>
                            </div>
                            <center>
                                <a class="btn btn-link" href="{{ route('forgot-password') }}">
                                    Forgot Your Password?
                                </a>
                            </center>

                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- content-wrapper ends -->
    </div>
@endsection
