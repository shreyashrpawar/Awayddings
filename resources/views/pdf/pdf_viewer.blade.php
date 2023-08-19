<!DOCTYPE html>
<html>
<head>
    <title>PDF Viewer</title>
</head>
<body>
    <iframe src="data:application/pdf;base64,{{ base64_encode($pdfContent) }}" width="100%" height="600px"></iframe>
</body>
</html>
