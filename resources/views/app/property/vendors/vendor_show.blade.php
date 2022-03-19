@extends('layouts.app')
@section('title','Home Page')
@section('contents')
    <div class="card">
        <div class="card-body">
            <h5 class="text-uppercase mb-2">Vendor Details</h5>

            <table class="table table-sm">
                <thead class="thead-dark">
                <tr>
                    <th>Info</th>
                    <th>Details</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <th>Company Name</th>
                    <td>{{ $data->name }}</td>
                </tr>
                <tr>
                    <th>Contact Name</th>
                    <td>{{ $data->first_name }} {{  $data->last_name  }}</td>
                </tr>
                <tr>
                    <th>Email</th>
                    <td>{{ $data->email }} </td>
                </tr>
                <tr>
                    <th>Phone</th>
                    <td>{{ $data->phone }}</td>
                </tr>
                <tr>
                    <th>Address</th>
                    <td>{{ $data->address }}</td>
                </tr>
                <tr>
                    <th>City</th>
                    <td>{{ $data->city }}</td>
                </tr>
                <tr>
                    <th>State</th>
                    <td>{{ $data->state }}</td>
                </tr>
                <tr>
                    <th>Pin Code</th>
                    <td>{{ $data->pin_code }}</td>
                </tr>
                <tr>
                    <th>GST</th>
                    <td>{{ $data->gst }}</td>
                </tr>

                </tbody>
            </table>
        </div>
    </div>
@endsection
