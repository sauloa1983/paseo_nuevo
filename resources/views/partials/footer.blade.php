@php
    $footerOffices = [
        [
            'city' => 'Bucaramanga',
            'address' => 'Cra 26 No. 34-53',
            'phones' => ['607 697 8295'],
            'license' => 'M. de A. 0126/96',
        ],
        [
            'city' => 'Floridablanca',
            'address' => 'Calle 29 # 29-33',
            'phones' => ['607 619 6196', '313 805 0296'],
            'license' => 'R.I. 0006-2012',
        ],
        [
            'city' => 'Piedecuesta',
            'address' => 'Calle 9 # 10-96',
            'phones' => ['607 665 6009', '321 343 9492'],
            'license' => 'M. de A. 120-C-G-2013',
        ],
        [
            'city' => 'Girón',
            'address' => 'Calle 39 # 22-07',
            'phones' => ['607 607 1047', '311 897 6591'],
            'license' => 'Res.041 de 2021',
        ],
        [
            'city' => 'Bogotá',
            'sedes' => [
                ['label' => 'Prado', 'phones' => ['601 274 1106'], 'license' => 'M. de A. 20190052'],
                ['label' => 'Chapinero', 'phones' => ['601 533 0775'], 'license' => 'M. de A. 20190052'],
            ]
        ],
    ];

    $footerLinks = [
        ['label' => 'Inicio', 'route' => 'home'],
        ['label' => 'Inmuebles', 'route' => 'inmuebles.search'],
        ['label' => 'Nosotros', 'route' => 'about'],
        ['label' => 'Servicios', 'route' => 'services'],
        ['label' => 'Clientes', 'route' => 'clients'],
        ['label' => 'Requisitos', 'route' => 'tenant'],
        ['label' => 'Centro de Ayuda', 'route' => 'contact'],
    ];
@endphp

<!--
<div class="margin-top-55"></div>
-->
<div id="footer">
	<div class="container">
		<div class="row">
			<div class="col-md-4 col-sm-6 pe-footer-col">
				<img class="footer-logo" src="{{ asset('images/logo.png') }}" alt="Paseo España Inmobiliaria">
				<br><br>
				<p>Buscas estabilidad laboral y cumplimiento en lo pactado de tu contrato. Envía tu hoja de vida al siguiente correo electrónico y te estaremos contactando <b>gerencia@paseoespana.com</b></p>
			</div>

			<div class="col-md-3 col-sm-6 pe-footer-col">
				<h4>Links de interés</h4>
				<ul class="footer-links pe-footer-links-grid">
					@foreach ($footerLinks as $link)
						<li>
							<a href="{{ route($link['route']) }}">{{ $link['label'] }}</a>
						</li>
					@endforeach
				</ul>
				<div class="clearfix"></div>
			</div>

			<div class="col-md-5 col-sm-12 pe-footer-col">
				<h4>Nuestras Oficinas</h4>
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
											<p class="pe-office-tooltip__phones">
												<i class="fa fa-phone" aria-hidden="true"></i>
												<span>{{ implode(' · ', $sede['phones']) }}</span>
											</p>
                                            <p class="pe-office-tooltip__text">
                                                <i class="fa fa-file-text" aria-hidden="true"></i>
                                                <span>{{ $sede['license'] }}</span>
                                            </p>
										</div>
									@endforeach
								@else
									<p class="pe-office-tooltip__address">
										<i class="fa fa-map-marker" aria-hidden="true"></i>
										<span>{{ $office['address'] }}</span>
									</p>
									<p class="pe-office-tooltip__phones">
										<i class="fa fa-phone" aria-hidden="true"></i>
										<span>{{ implode(' · ', $office['phones']) }}</span>
									</p>
                                    <p class="pe-office-tooltip__text">
                                        <i class="fa fa-file-text" aria-hidden="true"></i>
                                        <span>{{ $office['license'] }}</span>
                                    </p>
								@endif
							</div>
						</li>
					@endforeach
				</ul>

				<ul class="social-icons pe-social-right margin-top-20">
					<li><a class="facebook" href="https://m.facebook.com/people/Paseo-Espa%C3%B1a-Inmobiliaria/100063452446558/" target="_blank" rel="noopener noreferrer"><i class="icon-facebook"></i></a></li>
					<li><a class="instagram" href="https://www.instagram.com/paseoespana/" target="_blank" rel="noopener noreferrer"><i class="icon-instagram"></i></a></li>
					<li><a class="youtube" href="https://www.youtube.com/@paseoespana" target="_blank" rel="noopener noreferrer"><i class="icon-youtube"></i></a></li>
				</ul>
			</div>
		</div>

		<div class="row">
			<div class="col-md-12">
				<div class="copyrights">© {{ date('Y') }} Paseo España Inmobiliaria. Todos los derechos reservados.</div>
			</div>
		</div>
	</div>
</div>
