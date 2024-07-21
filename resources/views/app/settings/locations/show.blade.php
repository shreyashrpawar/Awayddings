@extends('layouts.app')
@section('title','Home Page')
@section('contents')
   <div class="card">
           <div class="card-body">
               <h5 class="text-uppercase mb-2">Location Details</h5>
               <table class="table table-sm">
                   <tr>
                       <th>Name</th>
                       <td>{{ $data->name }}</td>
                   </tr>
                   <tr>
                       <th>Description</th>
                       <td>{{ $data->description }}</td>
                   </tr>
               </table>
           </div>
       </div>
@endsection
