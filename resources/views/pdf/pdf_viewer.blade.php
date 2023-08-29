<!DOCTYPE html>
<html>
<head>
    <title>PDF Viewer</title>
</head>
<body>
    <iframe src="data:application/pdf;base64,{{ base64_encode($pdfContent) }}" width="100%" height="600px"></iframe>
</body>
</html>


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
        <div class="block-image">
            <h2>{{ $basicDetails['property_name'] }}</h2>
        </div>
        <div class="block-content">
            <p>Guests - {{ $basicDetails['adult'] }}</p>
            <p>{{ $basicDetails['duration'] }}</p>
        </div>
    </div>
    <!-- Display other basic details -->


    @foreach ($groupedPdfData as $group)
    <h3>Date: {{ $group['date'] }}</h3>
    <div class="block">
        <div class="block-content">
            <h2>Total Event - </h2>
            
        </div>
        @foreach ($group['events'] as $event)
        <div class="block-image">
            <img src="{{ $event['image_url'] }}" alt="Image">
        </div>
        <div class="block-content">
            <h2>Title</h2>
            <p>This is some content within the block.</p>
        </div>
        @endforeach
    </div>
    @endforeach
<!-- <div class="block">
    <div class="block-image">
        <img src="another-image-source.jpg" alt="Image">
    </div>
    <div class="block-content">
        <h2>Another Block</h2>
        <p>More content here.</p>
    </div>
</div> -->

    
    <h2>Date-wise Event Booking Details</h2>
    @foreach ($groupedPdfData as $group)
        <div>
            <h3>Date: {{ $group['date'] }}</h3>
            <p>Date: {{ $group['date'] }} | Total Events: {{ $group['dateWiseEventCounts'][$group['date']] }}</p>
            @foreach ($group['events'] as $event)
                <div>
                    <h4>{{ $event['event'] }}</h4>
                    <p>Time: {{ $event['time'] }}</p>
                    <p>Particular: {{ $event['particular'] }}</p>
                    <p>Amount: {{ $event['amount'] }}</p>
                    <!-- Display other event details -->
                    @if ($event['image_url'])
                        <img src="{{ asset('storage/' . $event['image_url']) }}" alt="Image">
                    @endif
                </div>
            @endforeach
        </div>
    @endforeach

    <h2> Additional Event</h2>
        @foreach ($pdfData as $data)
        <div>
            <p>Particular: {{ $data['particular'] }}</p>
            <p>Total Amount: {{ $data['amount'] }}</p>
            <!-- Display other date-wise details -->
            @if ($data['image_url'])
                <img src="{{ asset('storage/' . $data['image_url']) }}" alt="Image">
            @endif
        </div>
    @endforeach 

    <h2> Facility Event</h2>
        @foreach ($facilityData as $data)
        <div>
            <p>Facility: {{ $data['facility'] }}</p>
            <p>Facility Details: {{ $data['facility_description'] }}</p>
            <p>Total Amount: {{ $data['amount'] }}</p>
            <!-- Display other date-wise details -->
            @if ($data['image_url'])
                <img src="{{ asset('storage/' . $data['image_url']) }}" alt="Image">
            @endif
        </div>
    @endforeach 
    <!-- resources/views/pdf/block_design.blade.php -->
    

</body>
</html>
