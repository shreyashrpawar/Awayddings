@extends('layouts.app')
@section('title','Home Page')
@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" integrity="sha512-1sCRPdkRXhBV2PBLUdRb4tMg1w2YPf37qatUFeS7zlBy7jJI8Lf4VHwWfZZfpXtYSLy85pkm9GaYVYMfw5BC1A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        /* ======================= Cards ====================== */
        .cardBox {
            position: relative;
            width: 100%;
            padding: 20px;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            grid-gap: 30px;
        }

        .cardBox .card {
            position: relative;
            background: var(--white);
            padding: 30px;
            border-radius: 20px;
            display: flex;
            justify-content: space-between;
            cursor: pointer;
            box-shadow: 0 7px 25px rgba(0, 0, 0, 0.08);
        }

        .cardBox .card .numbers {
            position: relative;
            font-weight: 500;
            font-size: 2.5rem;
            color: var(--blue);
        }

        .cardBox .card .cardName {
            color: var(--black2);
            font-size: 1.1rem;
            margin-top: 5px;
        }

        .cardBox .card .iconBx {
            font-size: 3.5rem;
            color: var(--black2);
        }

        .cardBox .card:hover {
            background: var(--blue);
        }
        .cardBox .card:hover .numbers,
        .cardBox .card:hover .cardName,
        .cardBox .card:hover .iconBx {
            color: var(--white);
        }
    </style>
@endsection
@section('contents')

<div class="row">
    <div class="cardBox">
        <div class="card">
            <div>
                <div class="numbers">{{ $properties_count ?? '0' }}</div>
                <div class="cardName">Listed Properties</div>
            </div>
            <a href="{{ route('property.index') }}" class="btn btn-sm btn-success mt-2 rounded-pill">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>

        <div class="card">
            <div>
                <div class="numbers">{{ $pre_bookings_count ?? '0' }}</div>
                <div class="cardName">Total Pre Bookings</div>
            </div>
            <a href="{{ route('pre-bookings.index') }}" class="btn btn-sm btn-success mt-2 rounded-pill">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>

        <div class="card">
            <div>
                <div class="numbers">{{ $bookings_count ?? '0' }}</div>
                <div class="cardName">Confirmed Bookings</div>
            </div>

            <a href="{{ route('bookings.index') }}" class="btn btn-sm btn-success mt-2 rounded-pill">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>

  </div>
<div class="row ml-2">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">Lead Report</h4>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                    <tr>
                        <th class="pt-1">

                        </th>
                        <th class="pt-1 pl-0">
                            Status
                        </th>
                        <th class="pt-1">
                            Count
                        </th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($leads_count as $lead)
                    <tr>
                        <td class="py-1 pl-0">
                            @if($lead['status'] == 'new')
                                <label class="badge badge-light"> </label>
                            @elseif( $lead['status'] == 'recce_planned' or $lead['status'] == 'potential_recce' or $lead['status'] == 'recce_done')
                                <label class="badge badge-info"> </label>
                            @elseif( $lead['status'] == 'lost_general_inquiry')
                                <label class="badge badge-danger"> </label>
                            @elseif( $lead['status'] == 'under_discussion')
                                <label class="badge badge-warning"> </label>
                            @elseif( $lead['status'] == 'call_not_picked')
                                <label class="badge badge-secondary"> </label>
                            @else
                                <label class="badge badge-success"> </label>
                            @endif
                        </td>
                        <td>
                            {{str_replace('_', ' ', $lead['status'])}}
                        </td>
                        <td>
                            {{$lead['count']}}
                        </td>
                    </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
