@extends('layouts.app')
@section('title','Home Page')
@section('contents')
<div class="card">
    <div class="card-body">
        <div class="row form-group">
            <div class="col-md-6">
                <h4 class="card-title text-uppercase">Leads List</h4>
            </div>
        </div>


        <div class="table-responsive">
            <table class="table" id="example1">
                <thead class="thead-dark">
                <tr>
                    <th width="5%">#</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Bride & Groom</th>
                    <th>Wedding Date</th>
                    <th>Pax</th>
{{--                    <th>Origin</th>--}}
                    <th>Created On</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
                </thead>
                <tbody>
                @foreach($leads as $key => $val)
                <tr>
                    <th>{{ $leads->firstItem() + $loop->index }}</th>
                    <td>{{ $val->name }}</td>
                    <td>{{ $val->email }}</td>
                    <td>{{ $val->mobile }}</td>
                    <td>{{ $val->bride_groom }}</td>
                    <td>{{ $val->wedding_date }}</td>
                    <td>{{ $val->pax }}</td>
{{--                    <td>{{ $val->origin }}</td>--}}
                    <td>{{ date('d-m-Y', strtotime($val->created_at))}}</td>
                    <td>
                        @if($val->status == 'new')
                            <span class="badge badge-info">{{ $val->status }}</span>
                        @else
                            <span class="badge badge-success">{{ $val->status }}</span>
                        @endif
                    </td>
                    <td>
                    @if($val->status == 'new')
                            <form class="btn-group" action="{{ route('leads.update', ['lead' => $val->id]) }}" method="post">
                                {{ method_field('PATCH') }}
                                {{ csrf_field() }}
                                <button type="submit" class="btn btn-success btn-sm">Mark Contacted</button>
                            </form>
                    @endif
                </tr>

                @endforeach
                </tbody>
            </table>
            {!! $leads->render() !!}
        </div>
    </div>
</div>
@endsection
