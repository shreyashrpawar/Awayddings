@extends('layouts.app')
@section('title','Home Page')
@section('css')
    <style>
        .small-box.bg-info.dashboard-box {
            padding: 15px 0px 15px 0px;
        }
    </style>
@endsection
@section('contents')

<div class="row">
    <div class="col-lg-4 col-6">
        <center>
            <div class="small-box bg-info dashboard-box">
                <div class="inner">
                    <h3>{{ $properties_count ?? '0' }}</h3>
                    <p>Total Properties</p>
                </div>
                <div>
                    <a href="{{ route('property.index') }}" class="btn btn-sm btn-primary">More info <i class="fas fa-arrow-circle-right"></i></a>
                </div>
            </div>
        </center>
    </div>

    <div class="col-lg-4 col-6">
      <!-- small box -->
      <div class="small-box bg-success">
        <div class="inner">
          <h3>{{ $pre_bookings_count ?? '0' }}</h3>

          <p>Total Pre Bookings</p>
        </div>
        <div class="icon">
          <i class="ion ion-stats-bars"></i>
        </div>
        <a href="{{ route('pre-bookings.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-4 col-6">
      <!-- small box -->
      <div class="small-box bg-warning">
        <div class="inner">
            <h3>{{ $bookings_count ?? '0' }}</h3>

          <p>Total Bookings</p>
        </div>
        <div class="icon">
          <i class="ion ion-person-add"></i>
        </div>
        <a href="{{ route('bookings.index') }}" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>

  </div>
  
@endsection
