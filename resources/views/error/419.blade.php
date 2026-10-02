<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="refresh" content="0;url={{ url('/') }}">
    <title>Redirecting...</title>
    <script>
        window.location.href = "{{ url('/') }}";
    </script>
</head>
<body>
    <p>Session expired. Redirecting to <a href="{{ url('/') }}">website home page</a>...</p>
</body>
</html>
