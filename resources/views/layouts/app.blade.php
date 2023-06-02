<html>

<head>
    @section('head')
        <title>FlightBook</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link rel="stylesheet" href="{{ url('css/app.css') }}">
        <link rel="stylesheet" href="{{ url('css/message-display.css') }}">
        <link rel="stylesheet" href="{{ url('css/loader.css') }}">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Fira+Sans&family=Pacifico:wght@300;400&display=swap"
            rel="stylesheet">
        <script src="{{ url('js/app.js') }}" defer="True"></script>
    @show
</head>

<body>
    @yield('body')
</body>

</html>
