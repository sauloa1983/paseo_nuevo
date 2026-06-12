@extends('layouts.front')
@section('content')

@php
    $fotoUrls = $property->fotos
        ->pluck('foto')
        ->map(fn ($foto) => foto_inmueble_url($foto))
        ->filter()
        ->values()
        ->toArray();
    $fotosCount = count($fotoUrls);
@endphp

<div class="container pe-property-layout">
	<div class="row">

		<!-- Property Description -->
		<div class="col-lg-8 col-md-7 sp-content">
            <header id="titlebar" class="property-titlebar pe-property-header">
                <a href="javascript:history.back()" class="pe-back-link" title="Volver atrás">
                    <span class="pe-back-link__arrow" aria-hidden="true">←</span>
                    <span>Volver a resultados</span>
                </a>

                <div class="pe-property-header__title-row">
                    <h2 class="pe-property-header__title">
                        {{ $property->tipo_inmueble->tipo ?? 'Tipo no asignado' }} - {{ $property->barrio->nombre ?? 'Barrio no asignado' }}
                    </h2>
                    <span class="pe-property-header__badge">{{ 'Código: ' . $property->codigo }}</span>
                </div>

                <a href="#location" class="pe-property-header__location listing-address">
                    <i class="fa fa-map-marker" aria-hidden="true"></i>
                    <span>{{ optional($property->ciudad()->first())->nombre ?? 'No asignada' }}</span>
                </a>
            </header>

            <div class="pe-gallery-block margin-bottom-35">
                <div class="pe-gallery pe-gallery--in-column">
                    <div id="pe-property-slider" class="property-slider default">
                        @if(!empty($fotoUrls))
                            @foreach($fotoUrls as $fotoUrl)
                                <a href="{{ $fotoUrl }}" data-background-image="{{ $fotoUrl }}" class="item mfp-gallery" role="button" aria-label="Ampliar foto"></a>
                            @endforeach
                        @else
                            <div>
                                <img src="{{ asset('images/no_foto.jpg') }}" alt="Sin fotos" class="thumb">
                            </div>
                        @endif
                    </div>

                    <div class="pe-gallery__counter" aria-hidden="true">
                        <span id="pe-photo-counter">1 / {{ max(1, $fotosCount) }}</span>
                    </div>

                    <button type="button" id="pe-gallery-fullscreen" class="pe-gallery__fullscreen" aria-label="Expandir galería">
                        <i class="fa fa-expand" aria-hidden="true"></i>
                    </button>

                    @if($fotosCount > 1)
                        <button type="button" class="pe-gallery__nav pe-gallery__nav--prev" aria-label="Foto anterior">
                            <i class="fa fa-angle-left" aria-hidden="true"></i>
                        </button>
                        <button type="button" class="pe-gallery__nav pe-gallery__nav--next" aria-label="Foto siguiente">
                            <i class="fa fa-angle-right" aria-hidden="true"></i>
                        </button>
                    @endif
                </div>

                <div id="pe-property-thumbs" class="property-slider-nav pe-gallery-thumbs">
                    @if(!empty($fotoUrls))
                        @foreach($fotoUrls as $fotoUrl)
                            <div class="item">
                                <img src="{{ $fotoUrl }}" alt="">
                            </div>
                        @endforeach
                    @else
                        <div>
                            <img src="{{ asset('images/no_foto.jpg') }}" alt="Sin fotos" class="thumb">
                        </div>
                    @endif
                </div>
            </div>

			<div class="property-description">

				<!-- Main Features -->
				<div class="pe-quick-stats">
                    <div class="pe-quick-stats__item">
                        <div class="pe-quick-stats__icon" aria-hidden="true"><i class="fa fa-file-text-o"></i></div>
                        <div class="pe-quick-stats__text">
                            <div class="pe-quick-stats__value">{{ $property->area_construida }} m²</div>
                            <div class="pe-quick-stats__label">Área</div>
                        </div>
                    </div>

                    <div class="pe-quick-stats__item">
                        <div class="pe-quick-stats__icon" aria-hidden="true"><i class="fa fa-bed"></i></div>
                        <div class="pe-quick-stats__text">
                            <div class="pe-quick-stats__value">{{ (int) ($property->no_alcobas ?? 0)}}</div>
                            <div class="pe-quick-stats__label">Habitaciones</div>
                        </div>
                    </div>

                    <div class="pe-quick-stats__item">
                        <div class="pe-quick-stats__icon" aria-hidden="true"><i class="sl sl-icon-drop"></i></div>
                        <div class="pe-quick-stats__text">
                            <div class="pe-quick-stats__value">{{ (int) ($property->no_banos ?? 0)}}</div>
                            <div class="pe-quick-stats__label">Baños</div>
                        </div>
                    </div>

                    @php
                        $parqMoto = (string) ($property->parq_moto ?? '') === '1';
                        $parqComunal = (string) ($property->parq_comunal ?? '') === '1';
                        $garajes = (int) ($property->garajes ?? 0);
                        $showParqueadero = $parqMoto || $parqComunal || $garajes > 0;

                        if ($parqMoto) {
                            $parqIcono = 'fa fa-motorcycle';
                            $parqValor = '1';
                            $parqEtiqueta = 'Parqueadero';
                        } elseif ($parqComunal) {
                            $parqIcono = 'fa fa-car';
                            $parqValor = 'Parqueadero';
                            $parqEtiqueta = 'comunal';
                        } else {
                            $parqIcono = 'fa fa-car';
                            $parqValor = (string) $garajes;
                            $parqEtiqueta = 'Parqueadero';
                        }
                    @endphp
                    @if($showParqueadero)
                        <div class="pe-quick-stats__item">
                            <div class="pe-quick-stats__icon" aria-hidden="true"><i class="{{ $parqIcono }}"></i></div>
                            <div class="pe-quick-stats__text">
                                <div class="pe-quick-stats__value">{{ $parqValor }}</div>
                                @if($parqEtiqueta)
                                    <div class="pe-quick-stats__label">{{ $parqEtiqueta }}</div>
                                @endif
                            </div>
                        </div>
                    @endif
				</div>

                {{-- Solo muestra la administración en la parte inferior si NO es un arriendo (es decir, es solo venta) --}}
                @if(!$property->arriendo && $property->venta)
                    <div class="pe-admin-note">
                        @if($property->administracion !== null && $property->administracion != "0")
                            <span>Vlr Admon</span>
                            <strong>${{ number_format($property->administracion, 0, ',', '.') }}</strong>
                        @else
                            <span>Vlr Admon</span>
                            <strong>No aplica</strong>
                        @endif
                    </div>
                @endif

				<!-- Details -->
				<h3 class="desc-headline">Detalles</h3>
                @php
                    $detalles = [];

                    $detalles[] = ['Estrato', ($property->estrato == 10 ? 'Comercial' : $property->estrato)];

                    if ($property->no_banos && $property->no_banos != "0") {
                        $detalles[] = ['Baños', $property->no_banos];
                    }

                    if ($property->piso) {
                        $detalles[] = ['Piso', $property->piso];
                    }

                    if ($property->no_alcobas && $property->no_alcobas != "0") {
                        $detalles[] = ['Alcobas', $property->no_alcobas];
                    }

                    if ($property->tipo_cocina && $property->tipo_cocina != "0" && $property->tipo_cocina != "No tiene") {
                        $detalles[] = ['Cocina', $property->tipo_cocina];
                    }

                    if ($property->no_closets && $property->no_closets != "0") {
                        $detalles[] = ['Closets', $property->no_closets];
                    }

                    if ($property->garajes && $property->garajes != "0" && $property->garajes != null) {
                        $detalles[] = ['Parqueadero', $property->garajes];
                    }

                    if($property->ubicacion && $property->ubicacion != "-1") {
                        $detalles[] = ['Ubicación', ubicacion($property->ubicacion)];
                    }

                    if($property->acceso && $property->acceso != "-1") {
                        $detalles[] = ['Tipo Acceso', acceso($property->acceso)];
                    }

                    if($property->no_bodega && $property->no_bodega != "0") {
                        $detalles[] = ['Bodegas', $property->no_bodega];
                    }

                    if($property->no_oficina && $property->no_oficina != "0") {
                        $detalles[] = ['Oficinas', $property->no_oficina];
                    }

                    if($property->no_salon && $property->no_salon != "0") {
                        $detalles[] = ['Salones', $property->no_salon];
                    }

                    if ($property->unidad && $property->unidad != "0" && $property->unidad != null) {
                        $detalles[] = ['No. Piso', $property->unidad];
                    }
                @endphp

                <div class="pe-details-table">
                    <div class="pe-details-flow">
                        @foreach($detalles as [$label, $value])
                            <div class="pe-details-item">
                                <strong>{{ $label }}:</strong>
                                <span>{{ $value }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>


				<!-- Features -->
				<h3 class="desc-headline">Características</h3>
				<ul class="property-features checkboxes margin-top-0 pe-check-grid">
					@if($property->parq_moto == "1")
                        <li>Parq. Moto</li>
                    @endif

                    @if($property->parq_comunal == "1")
                        <li>Parq. Comunal</li>
                    @endif

                    @if($property->sala_comedor == "1")
                        <li>Sala Comedor</li>
                    @endif

                    @if($property->alcoba_servicio == "1")
                        <li>Alcoba Servicio</li>
                    @endif

                    @if($property->hall == "1")
                        <li>Hall TV</li>
                    @endif

                    @if($property->estudio == "1")
                        <li>Estudio</li>
                    @endif

                    @if($property->patio == "1")
                        <li>Patio</li>
                    @endif

                    @if($property->mirador == "1")
                        <li>Mirador</li>
                    @endif

                    @if($property->balcon == "1")
                        <li>Balcón</li>
                    @endif

                    @if($property->zona_ropas == "1")
                        <li>Zona de Ropa</li>
                    @endif

                    @if($property->terraza == "1")
                        <li>Terraza</li>
                    @endif

                    @if($property->salon_n == "1")
                        <li>Salón</li>
                    @endif

                    @if($property->bodega == "1")
                        <li>Bodega</li>
                    @endif

                    @if($property->oficina == "1")
                        <li>Oficina</li>
                    @endif

                    @if($property->lobby == "1")
                        <li>Lobby</li>
                    @endif

                    @if($property->ascensor == "1")
                        <li>Ascensor</li>
                    @endif

                    @if($property->vigilancia == "1")
                        <li>Celaduría</li>
                    @endif

                    @if($property->juegos == "1")
                        <li>Juegos</li>
                    @endif

                    @if($property->salon_social == "1")
                        <li>Salón Social</li>
                    @endif

                    @if($property->gimnasio == "1")
                        <li>Gimnasio</li>
                    @endif

                    @if($property->piscina == "1")
                        <li>Piscina</li>
                    @endif

                    @if($property->sauna == "1")
                        <li>Sauna</li>
                    @endif

                    @if($property->turco == "1")
                        <li>Turco</li>
                    @endif

                    @if($property->cancha == "1")
                        <li>Cancha</li>
                    @endif

                    @if($property->bbq == "1")
                        <li>BBQ</li>
                    @endif

                    @if($property->conjunto_cerrado == "1")
                        <li>Conjunto Cerrado</li>
                    @endif

                    @if($property->edificio == "1")
                        <li>Edificio</li>
                    @endif

                    @if($property->calentador == "1")
                        <li>Calentador</li>
                    @endif

                    @if($property->aire_acondicionado == "1")
                        <li>Aire Acondicionado</li>
                    @endif

				</ul>

				@if(!empty($property->observaciones))
				<h3 class="desc-headline">Información Adicional</h3>
				<div class="pe-mv-card__body">
					<p>{!! nl2br(e($property->observaciones)) !!}</p>
				</div>
				@endif

				@php
					$ciudadNombre = optional($property->ciudad()->first())->nombre ?? 'No asignada';
					$mapsQuery = trim(($property->barrio->nombre ?? '') . ', ' . $ciudadNombre);
					$hasPropertyVideo = filled($property->video_principal_embed);
				@endphp

				<div class="pe-media-stack">
					@if($hasPropertyVideo)
					<section class="pe-media-block">
						<h3 class="desc-headline no-border">Video</h3>
						<div class="responsive-iframe pe-media-card">
							<iframe width="560" height="315" src="{{ $property->video_principal_embed }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
						</div>
					</section>
					@endif

					<section class="pe-media-block" id="location">
						<h3 class="desc-headline no-border">Ubicación</h3>
						<div class="pe-media-card pe-map-card pe-map-card--compact">
							<div class="pe-map-card__frame">
								<iframe
									src="https://www.google.com/maps?q={{ rawurlencode($mapsQuery) }}&output=embed"
									loading="lazy"
									referrerpolicy="no-referrer-when-downgrade"
								></iframe>
							</div>
						</div>
					</section>

					@if(! $hasPropertyVideo && ! empty($promotionalVideo))
						@php
							$promotionalEmbed = \App\Models\Property::videoUrlToEmbed($promotionalVideo->video_url);
						@endphp

						@if($promotionalEmbed)
						<section class="pe-media-block">
							<h3 class="desc-headline no-border">{{ $promotionalVideo->title ?: 'Video promocional' }}</h3>
							<div class="responsive-iframe pe-media-card">
								<iframe width="560" height="315" src="{{ $promotionalEmbed }}" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe>
							</div>
						</section>
						@endif
					@endif
				</div>

			</div>
		</div>
		<!-- Property Description / End -->


		<!-- Sidebar -->
		<div class="col-lg-4 col-md-5 sp-sidebar sticky top-4 h-fit">
			<div class="sidebar right">

				@php
					$asesorTelefonosRaw = $property->asesorData?->telefonos ?? '';
					$whatsAppMessage = "Hola, estoy interesado en este inmueble (Código: {$property->codigo}). ¿Me puedes ayudar?";
					$whatsAppUrl = whatsapp_url_for_property_asesor($asesorTelefonosRaw, $whatsAppMessage);

					$ivaSuffix = ! empty($property->iva) ? ' + IVA' : '';
					$tieneAdmin = ! empty($property->administracion) && (float) $property->administracion > 0;
					$adminTexto = $tieneAdmin ? '$' . number_format($property->administracion, 0, ',', '.') : null;
				@endphp

				<div class="widget pe-price-widget">
					<div class="pe-price-widget__label">
						@if($property->arriendo)
							Canon arrendamiento
						@else
							Precio de venta
						@endif
					</div>

					<div class="property-pricing pe-price-widget__pricing">
						@if($property->arriendo && $property->venta)
							<div class="property-price">${{ str_replace(',', '.', number_format($property->valor_arriendo)) }}{{ $ivaSuffix }} <span class="pe-price-widget__period">/ mes</span></div>
							<div class="sub-price">Venta: ${{ str_replace(',', '.', number_format($property->valor_venta)) }}</div>
						@elseif ($property->arriendo)
							<div class="property-price">${{ str_replace(',', '.', number_format($property->valor_arriendo)) }}{{ $ivaSuffix }} <span class="pe-price-widget__period">/ mes</span></div>
						@elseif ($property->venta)
							<div class="property-price">${{ str_replace(',', '.', number_format($property->valor_venta)) }}</div>
						@endif

						@if($adminTexto && $property->arriendo)
							<div class="pe-price-widget__admin">
								<span>+ Administración {{ $adminTexto }}</span>
								<i class="fa fa-info-circle" aria-hidden="true" title="Valor de administración adicional al canon"></i>
							</div>
						@endif
					</div>

					<div class="pe-price-widget__actions">
						<button
							type="button"
							class="button fullwidth pe-btn-primary pe-btn-share"
							x-data="propertyShare()"
							@click="share()"
							:disabled="busy"
							:class="{ 'is-copied': copied }"
							:aria-label="label"
						>
							<i class="fa fa-share-alt" aria-hidden="true"></i>
							<span x-text="label">Compartir</span>
						</button>

						@if($whatsAppUrl)
							<a
								href="{{ $whatsAppUrl }}"
								target="_blank"
								rel="noopener noreferrer"
								class="button fullwidth pe-btn-whatsapp"
							>
								<i class="fa fa-whatsapp" aria-hidden="true"></i> WhatsApp
							</a>
						@else
							<button
								type="button"
								class="button fullwidth pe-btn-whatsapp"
								disabled
								aria-disabled="true"
								title="WhatsApp no disponible para este asesor"
							>
								<i class="fa fa-whatsapp" aria-hidden="true"></i> WhatsApp
							</button>
						@endif
					</div>
				</div>

				<!-- Widget -->
				<div class="widget" id="contacto">

					<!-- Agent Widget -->
					<div class="agent-widget">
                        <div class="agent-title">
                            <div class="agent-photo">
                                <img src="{{ $property->asesorData?->foto_url ?? asset('images/agent-avatar.jpg') }}" alt="Asesor" />
                            </div>

                            <div class="agent-details">
                                <h4>
                                    <a href="#">
                                        {{ ($property->asesorData->nombres ?? null)
                                            ? $property->asesorData->nombres . ' ' . $property->asesorData->apellidos
                                            : 'Paseo España' }}
                                    </a>
                                </h4>
                                <span>
                                    <i class="sl sl-icon-call-in"></i>
                                    {{ $property->asesorData->telefonos ?? '(607) 697 8295' }}
                                </span>
                            </div>

                            <div class="clearfix"></div>
                        </div>
                        <div id="form-message" style="display:none; margin-bottom:15px;"></div>

                        <form id="propertyContactForm" action="{{ route('property.contact') }}" method="POST">
                            @csrf

                            <input type="hidden" name="property_id" value="{{ $property->id }}">

                            <input
                                type="text"
                                name="email"
                                placeholder="Email (opcional)"
                                value="{{ old('email') }}"
                                pattern="^[A-Za-z0-9](([_\.\-]?[a-zA-Z0-9]+)*)@([A-Za-z0-9]+)(([\.\-]?[a-zA-Z0-9]+)*)\.([A-Za-z]{2,})$"
                            >

                            <input
                                type="tel"
                                name="phone"
                                placeholder="Teléfono"
                                value="{{ old('phone') }}"
                                inputmode="tel"
                                pattern="^[0-9+\(\)\- ]+$"
                                title="Solo se permiten números, espacios, paréntesis, guiones y el signo +"
                                required
                            >

                            <textarea name="message" required>{{ old('message', "Estoy interesado en este inmueble [Cod: {$property->codigo}] y me gustaría saber más detalles.") }}</textarea>

                            <button type="submit" id="submitBtn" class="button fullwidth margin-top-5">
                                Contactar Asesor
                            </button>
                        </form>
                    </div>

					<!-- Agent Widget / End -->

				</div>
				<!-- Widget / End -->

				<!--<div class="widget pe-trust-widget">
					<div class="pe-trust-widget__icon" aria-hidden="true">
						<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 36" fill="none" stroke="#c81517" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round">
							<path d="M16 2.5 4 8v10c0 7.2 5.1 13.9 12 15.5 6.9-1.6 12-8.3 12-15.5V8L16 2.5Z"/>
							<path d="M16 10.5 10.5 13v4.5c0 3.6 2.55 6.95 5.5 7.75 2.95-.8 5.5-4.15 5.5-7.75V13L16 10.5Z"/>
							<path d="M12.5 17.5 15 20l4.5-5"/>
						</svg>
					</div>
					<div class="pe-trust-widget__text">
						<strong>Compra segura</strong>
						<span>Tu información está protegida<br>y tus datos seguros.</span>
					</div>
				</div>-->


				@if($similarProperties->isNotEmpty())
				<!-- Widget -->
				<div class="widget">
					<div class="pe-similar-wrap pe-similar-wrap--sidebar{{ $similarProperties->count() <= 1 ? ' pe-similar-wrap--single' : '' }}">
						<div class="pe-similar-widget__head">
							<h3 class="pe-similar-widget__title">Inmuebles Similares</h3>

							@if($similarProperties->count() > 1)
								<div class="pe-similar-nav" aria-label="Navegación de inmuebles similares">
									<button type="button" class="pe-similar-arrow pe-similar-arrow--prev" aria-label="Anterior">
										<i class="fa fa-angle-left" aria-hidden="true"></i>
									</button>
									<button type="button" class="pe-similar-arrow pe-similar-arrow--next" aria-label="Siguiente">
										<i class="fa fa-angle-right" aria-hidden="true"></i>
									</button>
								</div>
							@endif
						</div>

						<div class="pe-similar-track">
							@foreach($similarProperties as $similar)
								<div class="pe-similar-slide">
									@include('partials.inmueble-card', ['inmueble' => $similar, 'modo' => 'auto'])
								</div>
							@endforeach
						</div>
					</div>
				</div>
				<!-- Widget / End -->
				@endif

			</div>
		</div>
		<!-- Sidebar / End -->

	</div>
</div>

@if(isset($property_featured) && $property_featured->isNotEmpty())
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h3 class="desc-headline no-border margin-bottom-35 margin-top-60">Propiedades destacadas</h3>

            <div class="pe-similar-wrap pe-similar-wrap--wide">
                <button type="button" class="pe-similar-arrow pe-similar-arrow--prev" aria-label="Anterior">
                    <i class="fa fa-angle-left" aria-hidden="true"></i>
                </button>

                <div class="pe-similar-track">
                    @foreach($property_featured as $featuredProperty)
                        <div class="pe-similar-slide">
                            @include('partials.inmueble-card', ['inmueble' => $featuredProperty, 'modo' => 'auto'])
                        </div>
                    @endforeach
                </div>

                <button type="button" class="pe-similar-arrow pe-similar-arrow--next" aria-label="Siguiente">
                    <i class="fa fa-angle-right" aria-hidden="true"></i>
                </button>
            </div>
        </div>
    </div>
</div>
@endif

<div class="margin-top-35"></div>
@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('propertyContactForm');
    const messageBox = document.getElementById('form-message');
    const galleryFullscreenBtn = document.getElementById('pe-gallery-fullscreen');
    const photoCounter = document.getElementById('pe-photo-counter');
    const sliderRoot = document.getElementById('pe-property-slider');
    const thumbsRoot = document.getElementById('pe-property-thumbs');

    if (!form || !messageBox) {
        console.error('Falta form o form-message');
        // keep other widgets functional
    }

    const submitBtn = form ? form.querySelector('button[type="submit"]') : null;

    if (form && submitBtn) {
    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        messageBox.style.display = 'none';
        messageBox.innerHTML = '';

        submitBtn.disabled = true;
        submitBtn.innerText = 'Enviando...';

        const formData = new FormData(form);

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (response.ok) {
                messageBox.style.display = 'block';
                messageBox.style.color = 'green';
                messageBox.innerHTML = data.message;
                form.reset();

                setTimeout(() => {
                    messageBox.style.display = 'none';
                    messageBox.innerHTML = '';
                }, 4000);
            } else {
                let errors = data.message || 'Ocurrió un error al enviar.';

                if (data.errors) {
                    errors = Object.values(data.errors).flat().join('<br>');
                }

                messageBox.style.display = 'block';
                messageBox.style.color = 'white';
                messageBox.style.background = 'red';
                messageBox.style.padding = '10px';
                messageBox.innerHTML = errors;
            }
        } catch (error) {
            messageBox.style.display = 'block';
            messageBox.style.color = 'white';
            messageBox.style.background = 'red';
            messageBox.style.padding = '10px';
            messageBox.innerHTML = 'Error de conexión.';
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerText = 'Contactar Asesor';
        }
    });
    } else {
        console.error('No se encontró el formulario o el botón submit');
    }

    function getGalleryItems() {
        if (!sliderRoot) {
            return [];
        }

        const links = sliderRoot.querySelectorAll('.slick-slide:not(.slick-cloned) a.mfp-gallery');
        const source = links.length ? links : sliderRoot.querySelectorAll('a.mfp-gallery');

        return Array.from(source).map(function (el) {
            return {
                src: el.getAttribute('href'),
                type: 'image',
            };
        });
    }

    function getCurrentSlideIndex() {
        if (!sliderRoot || !window.jQuery || typeof window.jQuery.fn.slick === 'undefined') {
            return 0;
        }

        const $main = window.jQuery(sliderRoot);

        if (!$main.hasClass('slick-initialized')) {
            return 0;
        }

        const slick = $main.slick('getSlick');
        const total = slick?.slideCount || 0;

        return normalizeSlideIndex($main.slick('slickCurrentSlide') || 0, total);
    }

    function normalizeSlideIndex(index, total) {
        if (!total || total <= 0) {
            return 0;
        }

        return ((index % total) + total) % total;
    }

    function getNavThumbItems($nav) {
        const $fromSlides = $nav.find('.slick-slide:not(.slick-cloned) .item');

        if ($fromSlides.length) {
            return $fromSlides;
        }

        return $nav.children('.item');
    }

    function clearThumbOverflowBadge($nav) {
        $nav.find('.pe-thumb-more__overlay').remove();
        $nav.find('.item').removeClass('pe-thumb-more');
    }

    function updateThumbOverflowBadge($nav, currentIndex, totalCount) {
        clearThumbOverflowBadge($nav);

        const width = window.innerWidth;
        let visible = 6;
        if (width <= 767) {
            visible = 3;
        } else if (width <= 992) {
            visible = 4;
        }

        const extra = totalCount - visible;
        const index = normalizeSlideIndex(currentIndex, totalCount);

        if (extra <= 0 || index >= visible) {
            return;
        }

        const $target = getNavThumbItems($nav).eq(visible - 1);
        if (!$target.length) {
            return;
        }

        $target.addClass('pe-thumb-more');
        $target.append(
            '<span class="pe-thumb-more__overlay" aria-hidden="true">'
            + '<span class="pe-thumb-more__count">+' + extra + '</span>'
            + '<span class="pe-thumb-more__rule"></span>'
            + '<span class="pe-thumb-more__label">fotos</span>'
            + '</span>'
        );
    }

    function bindThumbOverflowClick($nav, $main) {
        $nav.off('click.thumbMore').on('click.thumbMore', '.item.pe-thumb-more', function (event) {
            event.preventDefault();
            event.stopImmediatePropagation();
            const width = window.innerWidth;
            const jump = width <= 767 ? 3 : (width <= 992 ? 4 : 6);
            $main.slick('slickGoTo', jump);
        });
    }

    function refreshGalleryUi($nav, $main) {
        if (!$main.hasClass('slick-initialized')) {
            return;
        }

        const slick = $main.slick('getSlick');
        const total = slick?.slideCount || 0;
        const index = normalizeSlideIndex($main.slick('slickCurrentSlide') || 0, total);

        updatePhotoCounter(index + 1, total);
        updateThumbOverflowBadge($nav, index, total);
    }

    function bindGalleryImageLightbox($main) {
        if (!$main || !$main.length) {
            return;
        }

        $main.off('click.mfpOpen').on('click.mfpOpen', 'a.mfp-gallery', function (event) {
            event.preventDefault();
            openGalleryLightbox(getCurrentSlideIndex());
        });
    }

    function openGalleryLightbox(index) {
        const $ = window.jQuery;

        if (!$ || typeof $.magnificPopup === 'undefined') {
            return;
        }

        const items = getGalleryItems();

        if (!items.length) {
            return;
        }

        const idx = Math.min(Math.max(0, index ?? 0), items.length - 1);

        $.magnificPopup.open({
            items: items,
            type: 'image',
            gallery: {
                enabled: true,
                arrows: true,
                navigateByImgClick: true,
                arrowMarkup: '<button title="%title%" type="button" class="mfp-arrow mfp-arrow-%dir% pe-mfp-arrow" aria-label="%title%"><i class="fa fa-angle-%dir%" aria-hidden="true"></i></button>',
            },
            showCloseBtn: true,
            closeBtnInside: false,
            closeMarkup: '<button title="Cerrar (Esc)" type="button" class="mfp-close pe-mfp-close" aria-label="Cerrar"><i class="fa fa-times" aria-hidden="true"></i></button>',
            fixedContentPos: true,
            fixedBgPos: true,
            overflowY: 'auto',
            preloader: true,
            removalDelay: 0,
            mainClass: 'mfp-fade',
            enableEscapeKey: true,
        }, idx);
    }

    function galleryGoPrev() {
        if (!sliderRoot || !window.jQuery || typeof window.jQuery.fn.slick === 'undefined') {
            return;
        }

        const $ = window.jQuery;
        const $main = $(sliderRoot);
        const $nav = $('#pe-property-thumbs');

        if ($main.hasClass('slick-initialized')) {
            $main.slick('slickPrev');
            window.setTimeout(function () {
                refreshGalleryUi($nav, $main);
            }, 0);
        }
    }

    function galleryGoNext() {
        if (!sliderRoot || !window.jQuery || typeof window.jQuery.fn.slick === 'undefined') {
            return;
        }

        const $ = window.jQuery;
        const $main = $(sliderRoot);
        const $nav = $('#pe-property-thumbs');

        if ($main.hasClass('slick-initialized')) {
            $main.slick('slickNext');
            window.setTimeout(function () {
                refreshGalleryUi($nav, $main);
            }, 0);
        }
    }

    if (galleryFullscreenBtn) {
        galleryFullscreenBtn.addEventListener('click', function () {
            openGalleryLightbox(getCurrentSlideIndex());
        });
    }

    document.querySelector('.pe-gallery__nav--prev')?.addEventListener('click', galleryGoPrev);
    document.querySelector('.pe-gallery__nav--next')?.addEventListener('click', galleryGoNext);

    function updatePhotoCounter(currentIndex, total) {
        if (!photoCounter) return;
        const safeTotal = total > 0 ? total : 1;
        const safeCurrent = Math.min(Math.max(1, currentIndex), safeTotal);
        photoCounter.textContent = `${safeCurrent} / ${safeTotal}`;
    }

    function initPropertyGallery() {
        const $ = window.jQuery;

        if (!$ || typeof $.fn.slick === 'undefined') {
            return;
        }

        const $main = $('#pe-property-slider');
        const $nav = $('#pe-property-thumbs');

        if (!$main.length || !$nav.length) {
            return;
        }

        const total = $main.children('.item').length;
        const thumbTotal = $nav.children('.item').length;

        if (total <= 1 || thumbTotal <= 1) {
            updatePhotoCounter(1, Math.max(total, 1));
            return;
        }

        function visibleThumbs() {
            const width = window.innerWidth;
            if (width <= 767) {
                return Math.min(3, thumbTotal);
            }
            if (width <= 992) {
                return Math.min(4, thumbTotal);
            }
            return Math.min(6, thumbTotal);
        }

        function onSlideChange(event, slick, index) {
            const current = typeof index === 'number' ? index : (slick?.currentSlide ?? 0);
            const count = slick?.slideCount ?? total;
            updatePhotoCounter(current + 1, count);
            updateThumbOverflowBadge($nav, current, count);
        }

        bindThumbOverflowClick($nav, $main);

        $nav.slick({
            slidesToShow: visibleThumbs(),
            slidesToScroll: 1,
            asNavFor: '#pe-property-slider',
            dots: false,
            arrows: false,
            focusOnSelect: true,
            infinite: false,
            responsive: [
                { breakpoint: 993, settings: { slidesToShow: Math.min(4, thumbTotal) } },
                { breakpoint: 767, settings: { slidesToShow: Math.min(3, thumbTotal) } },
            ],
        });

        $main.slick({
            slidesToShow: 1,
            slidesToScroll: 1,
            arrows: false,
            fade: true,
            infinite: true,
            asNavFor: '#pe-property-thumbs',
            centerMode: false,
            adaptiveHeight: false,
            slide: '.item',
        });

        $main.on('init afterChange', onSlideChange);
        bindGalleryImageLightbox($main);
        onSlideChange(null, $main.slick('getSlick'), 0);
    }

    initPropertyGallery();

    document.querySelectorAll('.pe-similar-wrap').forEach(function (wrap) {
        const track = wrap.querySelector('.pe-similar-track');
        const prev = wrap.querySelector('.pe-similar-arrow--prev');
        const next = wrap.querySelector('.pe-similar-arrow--next');

        if (!track || (!prev && !next)) {
            return;
        }

        const slides = () => Array.from(track.querySelectorAll('.pe-similar-slide'));

        if (slides().length <= 1) {
            return;
        }

        track.addEventListener('wheel', function (e) {
            e.preventDefault();
        }, { passive: false });

        const getCurrentIndex = () => {
            const slideList = slides();
            const scrollLeft = track.scrollLeft;
            let closestIndex = 0;
            let minDistance = Infinity;

            slideList.forEach(function (slide, index) {
                const distance = Math.abs(slide.offsetLeft - scrollLeft);

                if (distance < minDistance) {
                    minDistance = distance;
                    closestIndex = index;
                }
            });

            return closestIndex;
        };

        const scrollToIndex = (index) => {
            const slideList = slides();
            const targetIndex = Math.max(0, Math.min(index, slideList.length - 1));

            track.scrollTo({
                left: slideList[targetIndex].offsetLeft,
                behavior: 'smooth',
            });
        };

        prev?.addEventListener('click', function () {
            scrollToIndex(getCurrentIndex() - 1);
        });

        next?.addEventListener('click', function () {
            scrollToIndex(getCurrentIndex() + 1);
        });
    });
});
</script>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('propertyShare', () => ({
        label: 'Compartir',
        defaultLabel: 'Compartir',
        copiedLabel: '¡Enlace copiado! ✅',
        busy: false,
        copied: false,
        _resetTimer: null,

        prefersNativeShare() {
            if (typeof navigator.share !== 'function') {
                return false;
            }

            const isTouchDevice = window.matchMedia('(pointer: coarse)').matches;
            const isMobileUa = /Android|iPhone|iPad|iPod|Mobile/i.test(navigator.userAgent);

            return isTouchDevice || isMobileUa;
        },

        async copyToClipboard(url) {
            if (navigator.clipboard?.writeText) {
                await navigator.clipboard.writeText(url);
                return;
            }

            const textarea = document.createElement('textarea');
            textarea.value = url;
            textarea.setAttribute('readonly', '');
            textarea.style.position = 'fixed';
            textarea.style.left = '-9999px';
            document.body.appendChild(textarea);
            textarea.select();
            document.execCommand('copy');
            document.body.removeChild(textarea);
        },

        showCopiedFeedback() {
            this.copied = true;
            this.label = this.copiedLabel;

            if (this._resetTimer) {
                clearTimeout(this._resetTimer);
            }

            this._resetTimer = setTimeout(() => {
                this.label = this.defaultLabel;
                this.copied = false;
            }, 2000);
        },

        async share() {
            if (this.busy) {
                return;
            }

            this.busy = true;

            const url = window.location.href;
            const title = document.title;

            try {
                if (this.prefersNativeShare()) {
                    await navigator.share({ title, url });
                    return;
                }

                await this.copyToClipboard(url);
                this.showCopiedFeedback();
            } catch (error) {
                if (error?.name === 'AbortError') {
                    return;
                }

                try {
                    await this.copyToClipboard(url);
                    this.showCopiedFeedback();
                } catch (copyError) {
                    window.prompt('Copia este enlace:', url);
                }
            } finally {
                this.busy = false;
            }
        },
    }));
});
</script>
@endpush
