@extends('layouts.app')
@section('title','Change Password')
@section('contents')
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Change Password</h4>
            <hr>
            <form id="changePasswordFrom" action="{{ route('change-password.submit') }}" method="POST" >
                @csrf
                @if (session('message'))
                    <div class="alert alert-success">
                        {{ session('message') }}
                    </div>
                @endif
                <div>
                    <div class="form-group">
                        <label for="current_password">Current Password</label>
                        <input type="password" class="form-control"  name="current_password" required >
                    </div>
                    <div class="form-group">
                        <label for="new_password">New Password</label>
                        <input type="password" class="form-control"  name="new_password" required >
                    </div>
                    <div class="form-group">
                        <label for="new_confirm_password">New Confirm Password</label>
                        <input type="password" class="form-control"  name="new_confirm_password" required >
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary">Update Password</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
