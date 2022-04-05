<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Torrents</title>
    <script>
        @php
            $sharedData = [
                'apiHost' => env('API_HOST'),
            ]
        @endphp

        window.__sharedData = {!! json_encode($sharedData) !!}
    </script>
</head>
<body>
    <div id="root"></div>

    <script src="{{ mix('/js/main.js') }}"></script>
</body>
</html>
