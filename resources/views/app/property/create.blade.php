@extends('layouts.app')
@section('title','Home Page')
@section('contents')
    <property-create></property-create>

@endsection
@section('js')
    <script src="{{ asset('js/app.js') }}"> </script>
    <script src="{{ asset('/assets/vendors/js/vendor.bundle.base.js') }}"></script>
@endsection
