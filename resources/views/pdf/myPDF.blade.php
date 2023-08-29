<!DOCTYPE html>
<html>
<head>
    <title>Event Booking Details</title>

    <style>
    .block {
        border: 1px solid #000;
        padding: 20px;
        margin-bottom: 20px;
        display: flex; /* Use flexbox for layout */
    }

    .block-image {
        flex: 1; /* Occupy 1/3 of the available space */
        padding-right: 20px;
    }

    .block-content {
        flex: 2; /* Occupy 2/3 of the available space */
        display: flex;
        flex-direction: column;
    }

    .block-content h2 {
        margin-top: 0;
    }
</style>
</head>
<body>
    <div class="block">
        <div class="block-content">
            <h2>{{ $basicDetails['property_name'] }}</h2>
        </div>
        <div class="block-content">
            <p>Guests - {{ $basicDetails['adult'] }}</p>
            <p>{{ $basicDetails['duration'] }}</p>
        </div>
        <div class="block-content">
            <p>Rs. {{ $basicDetails['amount'] }}</p>
        </div>
    </div>

    @foreach ($groupedPdfData as $date => $group)
        @php 
            $total_event = count($group); 
        @endphp
        <div class="block">
            <div class="block-content">
                <h2>Total Event - {{ $total_event }}</h2>
                <p>{{ $date }}</p>
            </div>
        </div>
        @foreach ($group as $key => $val)
            <div class="block">
                <div class="block-image">
                    <img src="{{ $val['decor_image_url'] }}" alt="Image">
                </div>
                <div class="block-content">
                    <h2>{{ $val['event'] }}</h2>
                    <p>{{ $date }}</p>
                    <p>Rs.{{ $val['decor_amount'] }}</p>
                    <p>{{ $val['event'] }} starts at {{ $val['start_time'] }} and ends at {{ $val['end_time'] }}</p>
                    <p>{{ $val['decor'] }}</p>
                    <div>
                        <div class="block-image">
                            <img src="{{ $val['artist_image_url'] }}" alt="Image">
                        </div>
                        <h2>{{ $val['artist'] }}</h2>
                        <p>{{ $val['artist_amount'] }}</p>
                    </div>
                </div>
            </div>
        @endforeach
    @endforeach
    <!-- Additional Event -->
        <div class="block">
            <div class="block-content">
                <h2>Total Event - 1</h2>
            @foreach ($additional_data as $key => $val)
                @php 
                    $total_event = count($val); 
                    //dd($val);
                @endphp
                
                @if (isset($val['facility_id']))
                <!-- <div class="block"> -->
                    <div class="block-image">
                        <img src="{{ $val['facility_image_url'] }}" alt="Image">
                    </div>
                    <div class="block-content">
                        <h2>Event - {{ $val['facility']}}</h2>
                        <p>{{ $val['amount'] }}</p>
                    </div>
                <!-- </div> -->
                @else
                <!-- <div class="block"> -->
                    <div class="block-image">
                        <img src="{{ $val['artist_person_image_url'] }}" alt="Image">
                    </div>
                    <div class="block-content">
                        <h2>Type - Artist</h2>
                        <p>{{ $val['amount'] }}</p>
                    </div>
                <!-- </div> -->
                @endif
            @endforeach
            </div>
        </div>
   
    <!-- resources/views/pdf/block_design.blade.php -->
    

</body>
</html>
