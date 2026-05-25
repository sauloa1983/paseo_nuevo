<header id="header-container" class="header-style-2">
    <!-- Topbar -->
    <div id="top-bar">
        <div class="container">

            <!-- Left Side Content -->
            <div class="left-side" style="width: 50%; float: left;">

                <!-- Top bar -->
                <ul class="top-bar-menu">
                    <!-- <li><i class="fa fa-phone"></i> +57 6076978295 </li> -->
                    <li><i class="fa fa-clock-o"></i> <a href="#">Lun-Jue 7:30 - 12:00 / 13:00 - 17:00 | Vie 7:30 - 12:00 / 13:00 - 16:30</a></li>
                </ul>

            </div>
            <!-- Left Side Content / End -->


            <!-- Left Side Content -->
            <div class="right-side-top">

                <!-- Social Icons -->
                <ul class="social-icons">
                    <li><a class="facebook" href="https://m.facebook.com/people/Paseo-Espa%C3%B1a-Inmobiliaria/100063452446558/" target="_blank()"><i class="icon-facebook"></i></a></li>
                    <li><a class="instagram" href="https://www.instagram.com/paseoespana/" target="_blank()"><i class="icon-instagram"></i></a></li>
                </ul>

            </div>
            <!-- Left Side Content / End -->

        </div>
    </div>
    <div class="clearfix"></div>
    <!-- Topbar / End -->


    <!-- Header -->
     <div id="header">
		<div class="container pe-header-bar">

			<div class="pe-header-bar__inner">
				<!-- Logo + menú móvil -->
				<div class="left-side pe-header-bar__brand">
					<div id="logo">
						<a href="{{ route('home') }}"><img src="{{ asset('images/logo.png') }}" alt="Paseo España Inmobiliaria"></a>
						<a href="{{ route('home') }}" class="sticky-logo"><img src="{{ asset('images/logo2.png') }}" alt="Paseo España Inmobiliaria"></a>
					</div>

					<div class="mmenu-trigger">
						<button class="hamburger hamburger--collapse" type="button">
							<span class="hamburger-box">
								<span class="hamburger-inner"></span>
							</span>
						</button>
					</div>
				</div>

				{{-- Horario — solo escritorio --}}
				<div class="pe-header-hours" aria-label="Horario de atención">


				</div>
			</div>

		</div>

		<!-- Main Navigation -->
		<nav id="navigation" class="style-2">
			<div class="container">
                <ul id="responsive">
                    <li><a class="{{ request()->routeIs('home') ? 'current' : '' }}" href="{{ route('home') }}">Inicio</a></li>
                    <li><a class="{{ request()->routeIs('inmuebles.search') ? 'current' : '' }}" href="{{ route('inmuebles.search') }}">Inmuebles</a></li>
                    <li><a class="{{ request()->routeIs('about') ? 'current' : '' }}" href="{{ route('about') }}">Nosotros</a></li>
                    <li><a class="{{ request()->routeIs('services') ? 'current' : '' }}" href="{{ route('services') }}">Servicios</a></li>
                    <li><a class="{{ request()->routeIs('clients') ? 'current' : '' }}" href="{{ route('clients') }}">Nuestros Clientes</a></li>
                    <li><a class="{{ request()->routeIs('requirements') ? 'current' : '' }}" href="{{ route('requirements') }}">Requisitos</a></li>
                    <li><a class="{{ request()->routeIs('faq') ? 'current' : '' }}" href="{{ route('faq') }}">FAQ</a></li>
                    <li><a class="{{ request()->routeIs('contact') ? 'current' : '' }}" href="{{ route('contact') }}">Contacto</a></li>
                    <li><a href="https://www.mipagoamigo.com/MPA_WebSite/ServicePayments" target="_blank">Zona de Pagos</a></li>
                </ul>
			</div>
		</nav>

		<div class="clearfix"></div>
		<!-- Main Navigation / End -->
	</div>
    <!-- Header / End -->

</header>
<div class="clearfix"></div>
