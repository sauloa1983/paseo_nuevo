@php
    use App\Models\Ciudad;

    $footerOffices = Ciudad::footerOffices();

    $footerLinks = [
        ['label' => 'Inicio', 'route' => 'home'],
        ['label' => 'Inmuebles', 'route' => 'inmuebles.search'],
        ['label' => 'Nosotros', 'route' => 'about'],
        ['label' => 'Servicios', 'route' => 'services'],
        ['label' => 'Requisitos', 'route' => 'tenant'],
        ['label' => 'Clientes', 'route' => 'clients'],
        ['label' => 'Centro de Ayuda', 'route' => 'contact'],
    ];

@endphp

<div id="footer">
	<div class="container pe-footer-container">
		<div class="row pe-footer-main">
			<div class="col-lg-4 col-md-6 col-sm-12 pe-footer-col pe-footer-col--brand">
				<img class="footer-logo" src="{{ asset('images/logo.png') }}" alt="Paseo España Inmobiliaria">
				<div class="pe-footer-careers">
					<h4 class="pe-footer-careers__title">Trabaje con Nosotros</h4>
					<p class="pe-footer-careers__text">
						Buscamos personas comprometidas, responsables y con vocación de servicio. Si desea hacer parte de nuestro equipo, envíe su hoja de vida a:
					</p>
					<a class="pe-footer-careers__email" href="mailto:gerencia@paseoespana.com">gerencia@paseoespana.com</a>
					<p class="pe-footer-careers__tagline">¡Esperamos conocer su talento!</p>
				</div>
				<ul class="social-icons pe-social-footer">
					<li><a class="facebook" href="https://m.facebook.com/people/Paseo-Espa%C3%B1a-Inmobiliaria/100063452446558/" target="_blank" rel="noopener noreferrer" aria-label="Facebook"><i class="icon-facebook"></i></a></li>
					<li><a class="instagram" href="https://www.instagram.com/paseoespana/" target="_blank" rel="noopener noreferrer" aria-label="Instagram"><i class="icon-instagram"></i></a></li>
					<li><a class="youtube" href="https://www.youtube.com/@PaseoEspa%C3%B1aSantander" target="_blank" rel="noopener noreferrer" aria-label="YouTube"><i class="icon-youtube"></i></a></li>
					<!--<li><a class="linkedin" href="https://www.linkedin.com/company/paseo-espana-inmobiliaria" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn"><i class="icon-linkedin"></i></a></li>-->
				</ul>
			</div>

			<div class="col-lg-4 col-md-6 col-sm-12 pe-footer-col pe-footer-col--links">
				<h4>Links de interés</h4>
				<ul class="footer-links pe-footer-links-grid">
					@foreach ($footerLinks as $link)
						<li>
							<a href="{{ route($link['route']) }}">{{ $link['label'] }}</a>
						</li>
					@endforeach
				</ul>
			</div>

			<div class="col-lg-4 col-md-6 col-sm-12 pe-footer-col pe-footer-col--offices">
				<h4>Nuestras oficinas</h4>
				<ul class="pe-offices" role="list">
					@foreach ($footerOffices as $office)
						<li
							class="pe-office-item"
							x-data="{
								open: false,
								hoverCapable: window.matchMedia('(hover: hover)').matches,
								show() { this.open = true; },
								hide() { if (this.hoverCapable) this.open = false; },
								toggle() { if (!this.hoverCapable) this.open = !this.open; }
							}"
							@mouseenter="show()"
							@mouseleave="hide()"
							@click.outside="open = false"
						>
							<button
								type="button"
								class="pe-office-city"
								@click="toggle()"
								:aria-expanded="open"
								aria-haspopup="true"
							>
								{{ $office['city'] }}
							</button>

							<div
								class="pe-office-tooltip"
								x-show="open"
								x-cloak
								x-transition:enter="pe-office-tooltip-enter"
								x-transition:enter-start="pe-office-tooltip-enter-start"
								x-transition:enter-end="pe-office-tooltip-enter-end"
								x-transition:leave="pe-office-tooltip-leave"
								x-transition:leave-start="pe-office-tooltip-leave-start"
								x-transition:leave-end="pe-office-tooltip-leave-end"
								role="tooltip"
							>
								@foreach ($office['offices'] ?? [] as $branch)
									@php
										$hasAddress = ! empty($branch['address']);
										$hasPhones = ! empty($branch['phones']);
										$hasLicense = ! empty($branch['license']);
										$showBranchName = filled($branch['name'] ?? null) && count($office['offices']) > 1;
										$hasVisibleBranch = $showBranchName || $hasAddress || $hasPhones || $hasLicense;
									@endphp

									@if (! $hasVisibleBranch)
										@continue
									@endif

									@if ($showBranchName)
										<p class="pe-office-tooltip__name">{{ $branch['name'] }}</p>
									@endif

									@if ($hasAddress)
										<p class="pe-office-tooltip__row">
											<i class="bi bi-geo-alt" aria-hidden="true"></i>
											<span>{{ $branch['address'] }}</span>
										</p>
									@endif

									@if ($hasPhones)
										<p class="pe-office-tooltip__row">
											<i class="bi bi-telephone" aria-hidden="true"></i>
											<span>{{ implode(' · ', $branch['phones']) }}</span>
										</p>
									@endif

									@if ($hasLicense)
										<p class="pe-office-tooltip__row">
											<i class="bi bi-file-earmark-text" aria-hidden="true"></i>
											<span>{{ $branch['license'] }}</span>
										</p>
									@endif

									@if (! $loop->last)
										<div class="pe-office-tooltip__divider" aria-hidden="true"></div>
									@endif
								@endforeach
							</div>
						</li>
					@endforeach
				</ul>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<div class="pe-footer-copyright-bar">
					<div class="copyrights pe-footer-copyright-text">© {{ date('Y') }} Paseo España Inmobiliaria. Todos los derechos reservados.</div>
					<nav class="pe-footer-legal" aria-label="Enlaces legales">
						<a href="{{ route('about') }}#policy-data">Política de privacidad</a>
						<span aria-hidden="true">|</span>
						<a href="{{ route('about') }}#policy-terms">Términos y condiciones</a>
					</nav>
					<div class="pe-footer-credits">
						<span class="pe-footer-credits__label">Diseño y desarrollo</span>
						<a
							href="https://valdesoft.com.co"
							class="pe-footer-credits__link"
							target="_blank"
							rel="noopener noreferrer"
							aria-label="Valdesoft - Diseño y desarrollo"
						>
							<img
								src="{{ asset('images/logo_h.svg') }}"
								alt="Valdesoft"
								class="pe-footer-credits__logo"
								loading="lazy"
							>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
