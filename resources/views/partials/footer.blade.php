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

    $footerPhone = '(607) 697 8295';
    $footerPhoneHref = 'tel:+576076978295';
    $footerEmail = 'comercial@paseoespana.com';
    $footerAddress = 'Cra 26 No. 34-53, Bucaramanga';
@endphp

<div id="footer">
	<div class="container">
		<div class="row">
			<div class="col-lg-3 col-md-6 col-sm-12 pe-footer-col">
				<img class="footer-logo" src="{{ asset('images/logo.png') }}" alt="Paseo España Inmobiliaria">
				<div class="text-widget pe-footer-brand-text">
					<span class="address">Más de 30 años conectando personas con propiedades en Bucaramanga y el área metropolitana.</span>
				</div>
				<ul class="social-icons pe-social-footer">
					<li><a class="facebook" href="https://m.facebook.com/people/Paseo-Espa%C3%B1a-Inmobiliaria/100063452446558/" target="_blank" rel="noopener noreferrer" aria-label="Facebook"><i class="icon-facebook"></i></a></li>
					<li><a class="instagram" href="https://www.instagram.com/paseoespana/" target="_blank" rel="noopener noreferrer" aria-label="Instagram"><i class="icon-instagram"></i></a></li>
					<li><a class="youtube" href="https://www.youtube.com/@paseoespana" target="_blank" rel="noopener noreferrer" aria-label="YouTube"><i class="icon-youtube"></i></a></li>
					<li><a class="linkedin" href="https://www.linkedin.com/company/paseo-espana-inmobiliaria" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn"><i class="icon-linkedin"></i></a></li>
				</ul>
			</div>

			<div class="col-lg-3 col-md-6 col-sm-12 pe-footer-col">
				<h4>Links de interés</h4>
				<ul class="footer-links pe-footer-links-grid">
					@foreach ($footerLinks as $link)
						<li>
							<a href="{{ route($link['route']) }}">{{ $link['label'] }}</a>
						</li>
					@endforeach
				</ul>
			</div>

			<div class="col-lg-3 col-md-6 col-sm-12 pe-footer-col">
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
								@if (! empty($office['sedes']))
									@foreach ($office['sedes'] as $sede)
										<div class="pe-office-tooltip__block">
											<p class="pe-office-tooltip__sede">{{ $sede['label'] }}</p>
											@if (! empty($sede['address']))
												<p class="pe-office-tooltip__address">
													<i class="fa fa-map-marker" aria-hidden="true"></i>
													<span>{{ $sede['address'] }}</span>
												</p>
											@endif
											@if (! empty($sede['phones']))
												<p class="pe-office-tooltip__phones">
													<i class="fa fa-phone" aria-hidden="true"></i>
													<span>{{ implode(' · ', $sede['phones']) }}</span>
												</p>
											@endif
											@if (! empty($sede['email']))
												<p class="pe-office-tooltip__text">
													<i class="fa fa-envelope-o" aria-hidden="true"></i>
													<span>{{ $sede['email'] }}</span>
												</p>
											@endif
											@if (! empty($sede['license']))
												<p class="pe-office-tooltip__text">
													<i class="fa fa-file-text" aria-hidden="true"></i>
													<span>{{ $sede['license'] }}</span>
												</p>
											@endif
											@foreach ($sede['dependencias'] ?? [] as $dependencia)
												<p class="pe-office-tooltip__text">
													<i class="fa fa-user" aria-hidden="true"></i>
													<span>
														{{ $dependencia['nombre'] }}
														@if (filled($dependencia['contacto'] ?? null))
															— {{ $dependencia['contacto'] }}
														@endif
														@if (filled($dependencia['telefono'] ?? null))
															· {{ $dependencia['telefono'] }}
														@endif
														@if (filled($dependencia['email'] ?? null))
															· {{ $dependencia['email'] }}
														@endif
													</span>
												</p>
											@endforeach
										</div>
									@endforeach
								@else
									@if (! empty($office['address']))
										<p class="pe-office-tooltip__address">
											<i class="fa fa-map-marker" aria-hidden="true"></i>
											<span>{{ $office['address'] }}</span>
										</p>
									@endif
									@if (! empty($office['phones']))
										<p class="pe-office-tooltip__phones">
											<i class="fa fa-phone" aria-hidden="true"></i>
											<span>{{ implode(' · ', $office['phones']) }}</span>
										</p>
									@endif
									@if (! empty($office['license']))
										<p class="pe-office-tooltip__text">
											<i class="fa fa-file-text" aria-hidden="true"></i>
											<span>{{ $office['license'] }}</span>
										</p>
									@endif
								@endif
							</div>
						</li>
					@endforeach
				</ul>
			</div>

			<div class="col-lg-3 col-md-6 col-sm-12 pe-footer-col">
				<h4>Contáctanos</h4>
				<ul class="pe-footer-contact">
					<li>
						<i class="fa fa-phone" aria-hidden="true"></i>
						<a href="{{ $footerPhoneHref }}">{{ $footerPhone }}</a>
					</li>
					<li>
						<i class="fa fa-envelope-o" aria-hidden="true"></i>
						<a href="mailto:{{ $footerEmail }}">{{ $footerEmail }}</a>
					</li>
					<li>
						<i class="fa fa-map-marker" aria-hidden="true"></i>
						<span>{{ $footerAddress }}</span>
					</li>
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
				</div>
			</div>
		</div>
	</div>
</div>
