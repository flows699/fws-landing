<!DOCTYPE html>
<html lang="hu">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">

    <title>FÉM — Ipari formatervező stúdió Budapesten</title>
    <meta name="description" content="A FÉM ipari formatervező stúdió Budapesten. Terméktervezés, prototípus és gyártáselőkészítés.">

    @fonts
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-white font-sans text-ink antialiased">
    {{ $slot }}
</body>
</html>
