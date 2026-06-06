<header id="header-container" class="header-style-2 pe-navbar">
    {{-- Barra superior --}}
    <div id="top-bar" class="pe-top-bar">
        <div class="container pe-top-bar__inner">
            <div class="pe-top-bar__left left-side">
                <ul class="top-bar-menu">
                    <li class="pe-top-bar__hours">
                        <i class="fa fa-clock-o" aria-hidden="true"></i>
                        @php
                            $officeHoursNewSchedule = now()->startOfDay()->gte(
                                \Illuminate\Support\Carbon::parse('2026-07-16')->startOfDay()
                            );
                        @endphp
                        <span class="pe-top-bar__hours-text">
                            @if ($officeHoursNewSchedule)
                                <span class="pe-top-bar__hours-line">Lun-Jue: 7:30 AM - 12:00 PM / 1:00 PM - 5:00 PM</span>
                                <span class="pe-top-bar__hours-line pe-top-bar__hours-line--sep">Vie: 7:30 AM - 12:00 PM / 1:00 PM - 4:30 PM</span>
                            @else
                                <span class="pe-top-bar__hours-line">Lun-Vie: 8:00 AM - 12:00 PM / 1:00 PM - 5:00 PM</span>
                                <span class="pe-top-bar__hours-line pe-top-bar__hours-line--sat">Sáb: 8:00 AM - 12:00 PM</span>
                            @endif
                        </span>
                    </li>
                </ul>
            </div>

            <div class="pe-top-bar__right right-side-top">
                <a href="https://www.mipagoamigo.com/MPA_WebSite/ServicePayments" target="_blank" rel="noopener noreferrer" class="pe-pay-btn">
                    <i class="fa fa-credit-card" aria-hidden="true"></i>
                    <span>Paga fácil</span>
                </a>

                <ul class="social-icons">
                    <li><a class="facebook" href="https://m.facebook.com/people/Paseo-Espa%C3%B1a-Inmobiliaria/100063452446558/" target="_blank" rel="noopener noreferrer"><i class="icon-facebook"></i></a></li>
                    <li><a class="instagram" href="https://www.instagram.com/paseoespana/" target="_blank" rel="noopener noreferrer"><i class="icon-instagram"></i></a></li>
                    <li><a class="youtube" href="https://www.youtube.com/@paseoespana" target="_blank" rel="noopener noreferrer"><i class="icon-youtube"></i></a></li>
                </ul>
            </div>
        </div>
    </div>

    {{-- Navbar principal: logo + menú en una sola fila --}}
    <div id="header" class="pe-main-navbar">
        <div class="container pe-main-navbar__container">
            <div class="pe-main-navbar__inner">
                <div class="left-side pe-main-navbar__brand">
                    <div id="logo">
                        <a href="{{ route('home') }}"><img src="{{ asset('images/logo.png') }}" alt="Paseo España Inmobiliaria"></a>
                        <a href="{{ route('home') }}" class="sticky-logo"><img src="{{ asset('images/logo2.png') }}" alt="Paseo España Inmobiliaria"></a>
                    </div>

                    <div class="mmenu-trigger">
                        <button class="hamburger hamburger--collapse" type="button" aria-label="Abrir menú">
                            <span class="hamburger-box">
                                <span class="hamburger-inner"></span>
                            </span>
                        </button>
                    </div>
                </div>

                <nav id="navigation" class="style-2 pe-main-navbar__nav">
                    <div class="container">
                        <ul id="responsive">
                            <li><a class="{{ request()->routeIs('home') ? 'current' : '' }}" href="{{ route('home') }}">Inicio</a></li>
                            <li><a class="{{ request()->routeIs('inmuebles.*') ? 'current' : '' }}" href="{{ route('inmuebles.search') }}">Inmuebles</a></li>
                            <li><a class="{{ request()->routeIs('about') ? 'current' : '' }}" href="{{ route('about') }}">Nosotros</a></li>
                            <li><a class="{{ request()->routeIs('services') ? 'current' : '' }}" href="{{ route('services') }}">Servicios</a></li>
                            <li class="pe-nav-dropdown">
                                <a
                                    class="pe-nav-dropdown__trigger {{ request()->routeIs('requirements', 'tenant', 'property') ? 'current' : '' }}"
                                    href="#"
                                    aria-haspopup="true"
                                    aria-expanded="false"
                                >Requisitos</a>
                                <ul class="pe-nav-dropdown__menu" role="menu" aria-label="Submenú Requisitos">
                                    <li role="none">
                                        <a href="{{ route('tenant') }}" class="{{ request()->routeIs('tenant') ? 'current' : '' }}" role="menuitem">
                                            Arrendatarios
                                        </a>
                                    </li>
                                    <li role="none">
                                        <a href="{{ route('property') }}" class="{{ request()->routeIs('property') ? 'current' : '' }}" role="menuitem">
                                            Propietarios
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <li><a class="{{ request()->routeIs('clients') ? 'current' : '' }}" href="{{ route('clients') }}">Clientes</a></li>
                            <li><a class="{{ request()->routeIs('contact') ? 'current' : '' }}" href="{{ route('contact') }}">Centro de Ayuda</a></li>
                        </ul>
                    </div>
                </nav>
            </div>
        </div>
    </div>
</header>
<div class="clearfix"></div>
