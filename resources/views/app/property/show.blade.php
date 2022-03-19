@extends('layouts.app')
@section('title','Home Page')
@section('contents')



       <div class="card">
           <div class="card-body">
               <h5 class="text-uppercase mb-2">{{ $data->name }}</h5>
               <div class="row">
                   <div class="col-md-4 mt">
                       <img src="{{ $data->featured_image }}" alt="" class="img-fluid img-thumbnail" width="400px" height="400px">
                       <p class="font-weight-bold text-center">Cover Image</p>
                   </div>
                   <div class="col-md-4">
                       <iframe src="{{ $data->gmap_embedded_code }}" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                       <p class="font-weight-bold text-center">Google Map</p>
                   </div>

                   <div class="col-md-4">

                       <iframe src="{{ $data->videos->media_url }}"
                               title="YouTube video player"
                               allow="accelerometer; autoplay; clipboard-write; encrypted-media;
                               gyroscope; picture-in-picture"
                               ></iframe>

                   </div>
                   <div class="row mt-3 mb-3">
                       @foreach($data->images as $key => $image)
                           <div class="col-md-2">
                               <img src="{{ $image->media_url }}" alt="" class="img-fluid img-thumbnail" width="200px" height="200px">
                               <p class="font-weight-bold">{{ $image->MediaSubCategory->name }}</p>
                           </div>
                       @endforeach
                   </div>
               </div>
               <table class="table">
                   <tr>
                       <th>Name</th>
                       <td>{{ $data->name }}</td>
                       <th>Location</th>
                       <td>{{ $data->location->name }}</td>
                   </tr>
                   <tr>
                       <th>Description</th>
                       <td title="{{ $data->description }}">{{ Str::limit($data->description,50,'....') }}</td>
                       <th>Address</th>
                       <td>{{ $data->address }}</td>
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
                                   <td>{{ $val->room_inclusion->name }}</td>
                               </tr>
                           @endforeach
                           </tbody>
                       </table>
                   </div>
                   <div class="col-md-6">
                       <h5>Menus </h5>
                       <table class="table table-sm">
                           <tr class="thead-dark">
                               <th>#</th>
                               <th>Details</th>
                           </tr>
                           @foreach($data->pdfs as $key => $pdf)
                               <tr>
                                   <th>{{ $loop->index  + 1 }}</th>
                                   <th>  <a href="{{$pdf->media_url}}" download="">{{ $pdf->MediaSubCategory->name }}</a></th>
                               </tr>
                           @endforeach
                       </table>
                   </div>
               </div>

           </div>
       </div>




@endsection
