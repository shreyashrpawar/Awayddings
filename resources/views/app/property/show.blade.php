@extends('layouts.app')
@section('title','Home Page')
@section('contents')
<div class="main-panel">
    <div class="content-wrapper">

       <div class="card">
           <div class="card-body">
               <table class="table">
                   <tr>
                       <th>Name</th>
                       <td>{{ $data->name }}</td>
                       <th>Location</th>
                       <td>{{ $data->location->name }}</td>
                   </tr>
                   <tr>
                       <th>Description</th>
                       <td>{{ $data->featured_image }}</td>
                       <th>GMAP</th>
                       <td>{{ $data->gmap_embedded_code }}</td>
                   </tr>

               </table>
               <hr>
               <h5>Property Rates</h5>
               <table class="table table-sm">
                   <thead class="thead-dark">
                   <tr class="text-center">
                       <th width="5%">#</th>
                       <th>Type</th>
                       <th>Amount</th>
                       <th width="5%">Quantity</th>
                       <th width="10%">Occupancy Percentage</th>
                   </tr>

                   </thead>
                   <tbody>
                       @foreach($data->default_rates as $key => $val)
                           <tr class="text-center">
                               <td>{{ 1 + $loop->index }}</td>
                               <td>{{ $val->hotel_charagable_type->name }}</td>
                               <td>{{ $val->amount }}</td>
                               <td>{{ $val->qty }}</td>
                               <td>{{ $val->chargable_percentage }}</td>
                           </tr>
                       @endforeach
                   </tbody>
               </table>
               <div class="row">
                   <div class="col-md-6">
                       <h5>Property Amenities</h5>
                       <table class="table table-sm">
                           <thead class="thead-dark">
                           <tr class="text-center">
                               <th width="5%">#</th>
                               <th>Name</th>
                           </tr>

                           </thead>
                           <tbody>
                           @foreach($data->amenities as $key => $val)
                               <tr class="text-center">
                                   <td>{{ 1 + $loop->index }}</td>
                                   <td>{{ $val->hotel_facility->name}}</td>
                               </tr>
                           @endforeach
                           </tbody>
                       </table>
                   </div>
                   <div class="col-md-6">
                       <h5>Room Inclusion </h5>
                       <table class="table table-sm">
                           <thead class="thead-dark">
                               <tr class="text-center">
                                   <th width="5%">#</th>
                                   <th>Name</th>
                               </tr>
                           </thead>
                           <tbody>
                           @foreach($data->room_inclusions as $key => $val)
                               <tr class="text-center">
                                   <td>{{ 1 + $loop->index }}</td>
                                   <td>{{ $val }}</td>
                               </tr>
                           @endforeach
                           </tbody>
                       </table>
                   </div>
               </div>

           </div>
       </div>

    </div>

    <footer class="footer">
        <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2018 <a href="https://www.bootstrapdash.com/" target="_blank" class="text-muted">Bootstrapdash</a>. All rights reserved.</span>
            <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Hand-crafted & made with <i class="mdi mdi-heart-outline text-primary"></i></span>
        </div>
    </footer>
    <!-- partial -->
</div>
@endsection
