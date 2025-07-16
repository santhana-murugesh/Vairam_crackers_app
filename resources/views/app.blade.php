<!DOCTYPE html>
 <html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', ) }} - Buy Best quality fire crackers from Sivakasi</title>

        @php
            $settings = app(\App\Settings\GeneralSettings::class);
        @endphp
        
        {{-- Dynamic Favicon --}}
        @if($settings->getFaviconUrl())
            <link rel="icon" type="image/x-icon" href="{{ $settings->getFaviconUrl() }}">
            <link rel="shortcut icon" type="image/x-icon" href="{{ $settings->getFaviconUrl() }}">
        @else
            <link rel="icon" type="image/x-icon" href="image/logo/logo-1.png">
        @endif
        
        {{-- Apple Touch Icon --}}
        @if($settings->getAppleTouchIconUrl())
            <link rel="apple-touch-icon" sizes="180x180" href="{{ $settings->getAppleTouchIconUrl() }}">
        @endif
        
        {{-- Additional Favicon Sizes --}}
        @if($settings->favicon_32x32)
            <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('storage/' . $settings->favicon_32x32) }}">
        @endif
        
        @if($settings->favicon_16x16)
            <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('storage/' . $settings->favicon_16x16) }}">
        @endif
        <link rel="stylesheet" href="/css/style.css">
        <link rel="stylesheet" href="/css/add-cart.css">
        <link rel="stylesheet" href="/css/fontawsome.css">
        <link rel="stylesheet" href="/css/lightbox.min.css">
        <link rel="stylesheet" href="/css/flatpickr.css">
        <link rel="stylesheet" href="/css/typeahead.css">
        <link rel="stylesheet" href="/css/node-waves.css">
        <link rel="stylesheet" href="/css/perfect-scrollbar.css">
        <link rel="stylesheet" href="/css/app-invoice.css">

        <!-- bootstrap links -->
        <link rel="stylesheet" href="/css/font-min.css">
        <link href="/css/Bootstrap-5.0.2-min.css" rel="stylesheet">
        <link href="assets/vendor/bootstrap/css/bootstrap.min.css" rel="stylesheet">
        <link href="assets/vendor/bootstrap-icons/bootstrap-icons.css" rel="stylesheet">
        <link href="assets/vendor/boxicons/css/boxicons.min.css" rel="stylesheet">
        <link href="assets/vendor/remixicon/remixicon.css" rel="stylesheet">
        <link href="assets/vendor/swiper/swiper-bundle.min.css" rel="stylesheet">
        <link href="/assets/css/magnific-popup.css" rel="stylesheet">
        <script src="https://kit.fontawesome.com/1d433b36eb.js" crossorigin="anonymous"></script>
        <!-- bootstrap links -->

       
        <!-- Vendor CSS Files -->
        <link href="assets/vendor/aos/aos.css" rel="stylesheet">
       
        <!-- Template Main CSS File -->
        <link href="assets/css/style.css" rel="stylesheet">

        {{-- mdb bootstrap  --}}
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
{{-- mdb bootstrap  --}}

        <!-- fonts links -->
        <!-- {{-- <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin> --}} -->
        <link href="css/googlefonts.css" rel="stylesheet">
        <!-- fonts links -->

        <!-- bootstrap shopping cards css -->
        <link rel="stylesheet" href="css/bootstrap.min.css">

         <!-- Fonts -->
        <!-- <link rel="preconnect" href="https://fonts.bunny.net">  -->
        <!-- <link href="css/bunnyfonts.css" rel="stylesheet" /> -->

        <!-- Scripts -->
        @routes
        @vite(['resources/js/app.js'])
        @inertiaHead
    </head>
    <body class="font-sans antialiased">
        @inertia


        <script src="/js/main.js"></script>

        <script src="/js/Lottie.js"></script>

        <script src="/js/Bootstrap-5.0.2-min.js"></script>

        <!-- bootstrap shopping card -->
        <!-- {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha1/dist/js/bootstrap.bundle.min.js"></script> --}} -->
        <script src="/js/jquery.min.js"></script>
        <script id="jquery-js" src="/assets/js/jquery.min.js"></script>

        <!-- {{-- <script src="js/jquery.popup.lightbox.min.js"></script> --}} -->


        <!-- {{-- <script>
            const lb = lightbox();
        </script> --}} -->


        {{-- mdb bootstrap  --}}
        <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        {{-- mdb bootstrap  --}}

    </body>

