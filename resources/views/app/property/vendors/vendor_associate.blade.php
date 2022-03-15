@extends('layouts.app')
@section('title','Home Page')
@section('contents')
    <div class="main-panel">
        <div class="content-wrapper">

            <div class="card">
                <div class="card-body">
                    <div class="row mb-2">
                        <div class="col-md-6">
                            <h5 class="text-uppercase mb-2">Property Alignment of <i>{{ $vendor->name }} </i></h5>
                        </div>
                        <div class="col-md-6 text-right">
                            <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModalCenter">
                                Associate New Property
                            </button>
                        </div>
                    </div>


                    <table class="table table-sm">
                        <thead class="thead-dark">
                        <tr>
                            <th>#</th>
                            <th>Property Name</th>
                        </tr>

                        </thead>
                        <tbody>
                        @if($propertyVendorAlignments->count() > 0 )
                        @foreach($propertyVendorAlignments as $key => $val)
                        <tr>
                            <th>{{ 1 + $loop->index }}</th>
                            <td>{{ $val->property->name }}</td>
                        </tr>
                        @endforeach
                        @else
                            <tr>
                                <th colspan="2" class="text-center">Not Aligned to any property</th>

                            </tr>
                        @endif
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="modal fade" id="exampleModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog " role="document">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="exampleModalLongTitle">Associate New Property</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-body">
                            <form action="{{ url('property-vendor/'.$vendor->id.'/associate') }}" method="POST">
                                @csrf
                                <input type="hidden" name="vendor_id" value="{{ $vendor->id }}">
                                <div class="form-group">
                                    <label for="">Property</label>
                                    <select name="property_id" id="property_id" class="form-control">
                                        <option value="">Select Property</option>
                                        @foreach($properties as $key => $val)
                                        <option value="{{$key}}">{{$val}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <button class="btn btn-sm btn-primary">Associate</button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>

        </div>

    @include('includes/footer')
    <!-- partial -->
    </div>
@endsection
