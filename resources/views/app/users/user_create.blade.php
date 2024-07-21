@extends('layouts.app')
@section('title','User Registration')
@section('contents')
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Users Registration</h4>
            <hr>
            <form id="propertyRegistrationFrom" action="{{ route('users.store') }}" method="POST" >
                @csrf
                <div>
                    <div class="form-group">
                        <label for="">Name</label>
                        <input type="text" class="form-control"  name="name" required >
                    </div>
                    <div class="form-group">
                        <label for="">Email</label>
                        <input type="email" class="form-control"  name="email"  required>
                    </div>
                    <div class="form-group">
                        <label for="">Phone</label>
                        <input type="text" class="form-control"  name="phone" required >
                    </div>
                    <div class="form-group">
                        <label for="">Role</label>
                        <select name="role_id" id="role_id" class="form-control" required>
                            <option value="">Select Role</option>
                            @foreach($roles as $key => $val)
                                <option value="{{ $key }}">{{ $val }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="">Password</label>
                        <input type="password" class="form-control"  name="password" required >
                    </div>
                    <div class="form-group">
                        <button class="btn btn-primary">Save</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
@section('js')
    <script src="{{ asset('/assets/vendors/js/vendor.bundle.base.js') }}"></script>
    <script>

    </script>
@endsection
