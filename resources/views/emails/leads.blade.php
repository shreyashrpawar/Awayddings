@extends('emails.layout')
@section('content')
    <body marginheight="0" topmargin="0" marginwidth="0" style="margin: 0px; background-color: #f2f3f8;" leftmargin="0">
        Customer Name: {{ $customer_name }} <br/>
        Customer Phone: {{ $customer_mobile }} <br/>
        Customer Email: {{ $customer_email }} <br/>
        Customer Location: {{ $customer_location }} <br/>
        Customer Wedding Date: {{ $customer_date }} <br/>
        pax: {{ $customer_pax }}
    </body>
@endsection
