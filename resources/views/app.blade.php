<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Security-Policy" content="default-src 'self' * data: blob: 'unsafe-inline' 'unsafe-eval'; script-src 'self' 'unsafe-inline' 'unsafe-eval' *; style-src 'self' 'unsafe-inline' *; font-src 'self' data: *; img-src 'self' data: blob: *; frame-src 'self' *; connect-src 'self' * blob: ws: wss:;">

    <title>SnapFit | Revitalisasi Wastra Nusantara Melalui Immersive AI & AR Virtual Try-On</title>
    <meta name="description" content="SnapFit merevolusi pelestarian warisan budaya dengan mendigitalisasi wastra Nusantara langsung dari pengrajin lokal, didukung teknologi Immersive AI Studio dan AR Virtual Try-On.">
    <meta name="keywords" content="SnapFit, busana adat, batik, tenun, virtual try-on, augmented reality, AI fitting room, warisan budaya, kebaya, surjan, fashion nusantara">
    <link rel="icon" type="image/png" href="/images/logo1.png">

    <!-- Midtrans Snap JS -->
    <script type="text/javascript"
            src="https://app.{{ config('midtrans.is_production') ? 'midtrans' : 'sandbox.midtrans' }}.com/snap/snap.js"
            data-client-key="{{ config('midtrans.client_key') }}"></script>



    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <div id="app"></div>
</body>
</html>