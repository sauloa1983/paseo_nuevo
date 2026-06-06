<!DOCTYPE html>
<head>
    <title>@yield('title', 'Paseo España Inmobiliaria')</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}?v={{ filemtime(public_path('css/custom.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}?v={{ filemtime(public_path('css/style.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/color.css') }}?v={{ filemtime(public_path('css/color.css')) }}">
    @stack('styles')

</head>
<body>

    <!-- Wrapper -->
<div id="wrapper">
    <!-- Navbar del HTML -->
    @include('partials.navbar')



    <!-- Contenido principal -->
    @yield('content')

    <!-- Footer -->
    @include('partials.footer')


    {{-- Botones flotantes: WhatsApp arriba, flecha ir arriba debajo (más cerca de la esquina) --}}
    <div class="floating-stack">
        @include('partials.whatsapp-router')

        <div id="backtotop"><a href="#"></a></div>
    </div>


    <!-- Scripts
    ================================================== -->
    <script type="text/javascript" src="{{ asset('scripts/jquery-3.6.0.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('scripts/jquery-migrate-3.3.2.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('scripts/chosen.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('scripts/magnific-popup.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('scripts/owl.carousel.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('scripts/rangeSlider.js') }}"></script>
    <script type="text/javascript" src="{{ asset('scripts/sticky-kit.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('scripts/slick.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('scripts/masonry.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('scripts/mmenu.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('scripts/tooltips.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('scripts/custom.js') }}"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>



    @stack('scripts')

</div>
<!-- Wrapper / End -->

</body>
</html>
