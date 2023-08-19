<!DOCTYPE html>
<html>
<head>
    <title>PDF</title>
</head>
<body>
    @foreach ($pdfData as $data)
        <h1>{{ $data['title'] }}</h1>
        <p>ID: {{ $data['id'] }}</p>
        <!-- Add other fields as needed -->
        @if ($data['image_url'])
            <img src="{{ $data['image_url'] }}" alt="Image">
        @endif
    @endforeach
</body>
</html>
