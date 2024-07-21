@extends('layouts.app')
@section('title','Home Page')
@section('css')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" integrity="sha512-1sCRPdkRXhBV2PBLUdRb4tMg1w2YPf37qatUFeS7zlBy7jJI8Lf4VHwWfZZfpXtYSLy85pkm9GaYVYMfw5BC1A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        /* ======================= Cards ====================== */
        .cardBox {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 100%;
            padding: 100px;
            display: flex;
            justify-content: center;
            align-items: center;
            left: calc(50% + 90px);
        }

        .cardBox .card {
            position: relative;
            background: var(--white);
            padding: 200px;
            border-radius: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            cursor: pointer;
            box-shadow: 0 7px 25px rgba(0, 0, 0, 0.08);
        }

        .cardBox .card .numbers {
            position: relative;
            font-weight: bold;
            font-size: 3rem;
            color: red; /* reddish text */
        }

        .cardBox .card .cardName {
            color: red; /* reddish text */
            font-size: 1.5rem;
            margin-top: 10px;
            font-weight: bold;
        }
    </style>
@endsection

@section('contents')

<div class="row">
    <div class="cardBox">
        <div class="card">
            <div>
                <div class="numbers">Welcome</div>
                <div class="cardName">to Awayddings Portal !</div>
            </div>
        </div>
    </div>
</div>

@endsection