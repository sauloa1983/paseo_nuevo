@extends('layouts.front')
@section('content')

<!-- Titlebar
================================================== -->
<div id="titlebar" class="property-titlebar margin-bottom-0">
	<div class="container">
		<div class="row">
			<div class="col-md-12">

                <a href="javascript:history.back()" class="back-to-listings" title="Volver atrás"></a>
				<div class="property-title">
					<h2>{{ $property->tipo_inmueble->tipo ?? 'Tipo no asignado' }} - {{ $property->barrio->nombre ?? 'Barrio no asignado'  }} <span class="property-badge">{{ 'Código: ' . $property->codigo }}</span></h2>
					<span>
						<a href="#location" class="listing-address">
							<i class="fa fa-map-marker"></i>
							{{ optional($property->ciudad()->first())->nombre ?? 'No asignada' }}
						</a>
					</span>
				</div>

				<div class="property-pricing">
                    @if($property->arriendo && $property->venta)
                        <div class="property-price">${{ str_replace(',', '.', number_format($property->valor_arriendo + $property->administracion)) }} <span class="text-xs font-normal text-gray-500">/ mes</span></div>
                        <div class="sub-price">${{ str_replace(',', '.', number_format($property->valor_venta)) }} </div>
                    @elseif ($property->arriendo)
                        <div class="property-price">${{ str_replace(',', '.', number_format($property->valor_arriendo + $property->administracion)) }} <span class="text-xs font-normal text-gray-500">/ mes</span></div>
                    @elseif ($property->venta)
                        <div class="property-price">${{ str_replace(',', '.', number_format($property->valor_venta)) }}</div>
                    @endif
				</div>


			</div>
		</div>
	</div>
</div>

<!-- Content
================================================== -->
<div class="container">
	<div class="row margin-bottom-50">
		<div class="col-md-12">
            @php
                $fotos = $property->fotos->pluck('foto')->toArray();
            @endphp
			<!-- Slider -->
			<div class="property-slider default">
                @if(!empty($fotos))
                    @foreach($fotos as $foto)
                        <a href="https://www.paseoespanainmobiliaria.com/{{ $foto }}" data-background-image="https://www.paseoespanainmobiliaria.com/{{ $foto }}" class="item mfp-gallery"></a>
                        <!--<a href="{{ Storage::url($foto) }}" data-background-image="{{ Storage::url($foto) }}" class="item mfp-gallery"></a>-->
                    @endforeach
                @else
                    <!-- Si no hay fotos reales, usa la imagen personalizada -->
                    <div>
                        <img src="{{ asset('images/no_foto.jpg') }}" alt="Sin fotos" class="thumb">
                    </div>
                @endif
			</div>

			<!-- Slider Thumbs -->
			<div class="property-slider-nav">
                @if(!empty($fotos))
                    @foreach($fotos as $foto)
                        <!--<div class="item">
                            <img src="{{ Storage::url($foto) }}" alt="Inmueble">
                        </div>-->
                        <div class="item">
                            <img src="https://www.paseoespanainmobiliaria.com/{{ $foto }}" alt="">
                        </div>
                    @endforeach
                @else
                    <!-- Si no hay fotos reales, usa la imagen personalizada -->
                    <div>
                        <img src="{{ asset('images/no_foto.jpg') }}" alt="Sin fotos" class="thumb">
                    </div>
                @endif
			</div>

		</div>
	</div>
</div>


<div class="container">
	<div class="row">

		<!-- Property Description -->
		<div class="col-lg-8 col-md-7 sp-content">
			<div class="property-description">

				<!-- Main Features -->
				<ul class="property-main-features">
					<li>Área <span>{{ $property->area_construida }} m²</span></li>
					<li>Habitaciones <span>{{ (int) ($property->no_alcobas ?? 0)}}</span></li>
					<li>Baños <span>{{ (int) ($property->no_banos ?? 0)}}</span></li>
					<li>Parqueadero <span>{{ (int) ($property->garajes ?? 0)}}</span></li>
                    {{-- Solo muestra la administración en la parte inferior si NO es un arriendo (es decir, es solo venta) --}}
                    @if(!$property->arriendo && $property->venta)
                        @if($property->administracion !== null && $property->administracion != "0")
                            <li>Vlr Admon <span>${{ number_format($property->administracion, 0, ',', '.') }}</span></li>
                        @else
                            <li>Vlr Admon <span>No aplica</span></li>
                        @endif
                    @endif
				</ul>

				<!-- Details -->
				<h3 class="desc-headline">Detalles</h3>
				<ul class="property-features margin-top-0">
                    <li>Estrato: <span>{{ $property->estrato == 10 ? 'Comercial' : $property->estrato }}</span></li>
                    @if($property->piso)
                        <li>Piso: <span>{{ $property->piso }}</span></li>
                    @endif

                    @if($property->ubicacion && $property->ubicacion != "-1")
                        <li>Ubicación: <span>{{ ubicacion($property->ubicacion) }}</span></li>
                    @endif

                    @if($property->acceso && $property->acceso != "-1")
                        <li>Tipo Acceso: <span>{{ acceso($property->acceso) }}</span></li>
                    @endif

                    @if($property->no_alcobas && $property->no_alcobas != "0")
                        <li>Alcobas: <span>{{ $property->no_alcobas }}</span></li>
                    @endif

                    @if($property->no_closets && $property->no_closets != "0")
                        <li>Closets: <span>{{ $property->no_closets }}</span></li>
                    @endif

                    @if($property->no_banos && $property->no_banos != "0")
                        <li>Baños: <span>{{ $property->no_banos }}</span></li>
                    @endif

                    @if($property->tipo_cocina && $property->tipo_cocina != "0" && $property->tipo_cocina != "No tiene")
                        <li>Cocina: <span>{{ $property->tipo_cocina }}</span></li>
                    @endif

                    @if($property->garajes && $property->garajes != "0" && $property->garajes != null)
                        <li>Parqueadero: <span>{{ $property->garajes }}</span></li>
                    @endif

                    @if($property->no_bodega && $property->no_bodega != "0")
                        <li>No. Bodegas: <span>{{ $property->no_bodega }}</span></li>
                    @endif

                    @if($property->no_oficina && $property->no_oficina != "0")
                        <li>No. Oficinas: <span>{{ $property->no_oficina }}</span></li>
                    @endif

                    @if($property->no_salon && $property->no_salon != "0" )
                        <li>No. Salones: <span>{{ $property->no_salon }}</span></li>
                    @endif

                    @if ($property->unidad && $property->unidad != "0" && $property->unidad != null)
                        <li>Piso: <span>{{ $property->unidad }}</span></li>
                    @endif
				</ul>


				<!-- Features -->
				<h3 class="desc-headline">Características</h3>
				<ul class="property-features checkboxes margin-top-0">
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


				<!-- Video -->
				<h3 class="desc-headline no-border">Video</h3>
				<div class="responsive-iframe">
					<iframe width="560" height="315" src="https://www.youtube-nocookie.com/embed/gnUu-4B3Ykw?rel=0&amp;showinfo=0" frameborder="0" allowfullscreen></iframe>
				</div>

                @if($similarProperties->isNotEmpty())
				<!-- Similar Listings Container -->
				<h3 class="desc-headline no-border margin-bottom-35 margin-top-60">Inmuebles Similares</h3>

				<!-- Layout Switcher -->
				<div class="layout-switcher hidden"><a href="#" class="list"><i class="fa fa-th-list"></i></a></div>
				<div class="listings-container list-layout pe-listings-modern">
                    @foreach($similarProperties as $similar)
                        @include('partials.inmueble-card', ['inmueble' => $similar, 'modo' => 'auto'])
                    @endforeach

				</div>
				<!-- Similar Listings Container / End -->
                @endif
			</div>
		</div>
		<!-- Property Description / End -->


		<!-- Sidebar -->
		<div class="col-lg-4 col-md-5 sp-sidebar">
			<div class="sidebar sticky right">

				<div class="pe-property-share pe-property-share--sidebar">
					<button
						type="button"
						class="pe-share-btn"
						x-data="propertyShare()"
						@click="share()"
						:disabled="busy"
						:class="{ 'is-copied': copied }"
						:aria-label="label"
					>
						<svg class="pe-share-btn__icon" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
							<path stroke-linecap="round" stroke-linejoin="round" d="M7.217 10.907a2.25 2.25 0 1 0 0 2.186m0-2.186c.18.324.283.696.283 1.093s-.103.77-.283 1.093m0-2.186 9.566-5.314m-9.566 7.5 9.566 5.314m0 0a2.25 2.25 0 1 0 3.935 2.186 2.25 2.25 0 0 0-3.935-2.186Zm0-12.814a2.25 2.25 0 1 0 3.933-2.185 2.25 2.25 0 0 0-3.933 2.185Z"/>
						</svg>
						<span x-text="label">Compartir propiedad</span>
					</button>
				</div>

				<!-- Widget -->
				<div class="widget">

					<!-- Agent Widget -->
					<div class="agent-widget">
                        <div class="agent-title">
                            <div class="agent-photo">
                                <img src="{{ asset('images/agent-avatar.jpg') }}" alt="Asesor" />
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
                                placeholder="Email"
                                value="{{ old('email') }}"
                                pattern="^[A-Za-z0-9](([_\.\-]?[a-zA-Z0-9]+)*)@([A-Za-z0-9]+)(([\.\-]?[a-zA-Z0-9]+)*)\.([A-Za-z]{2,})$"
                                required
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


				<!-- Widget -->
				<div class="widget">
					<h3 class="margin-bottom-35">Propiedades destacadas</h3>

					<div class="listing-carousel outer">
						<!-- Item -->
                        @foreach($property_featured as $property)
						<div class="item">
							<div class="listing-item compact">

								<a href="{{ route('inmuebles.show', $property->codigo) }}" class="listing-img-container">

									<div class="listing-badges">
										<span>{{'Código: ' . $property->codigo }}</span>
									</div>

									<div class="listing-img-content">
										<span class="listing-compact-title">{{ $property->tipo_inmueble->tipo ?? 'Tipo no asignado' }} - {{ $property->barrio->nombre ?? 'Barrio no asignado'  }} <i>$275,000</i></span>

										<ul class="listing-hidden-content">
											<li>Área <span>{{ $property->area_construida }} m²</span></li>
											<li>Baños <span>{{ $property->no_banos }}</span></li>
											<li>Alcobas <span>{{ empty($property->no_alcobas) ? 'N/A' : $property->no_alcobas }}</span></li>
										</ul>
									</div>

                                    @php
                                        $firstFoto = $property->fotos->isNotEmpty() ? $property->fotos->first()->foto : null;
                                    @endphp

									@if($firstFoto)
                                        <img src="https://www.paseoespanainmobiliaria.com/{{ $firstFoto }}" alt="Foto inmueble">
                                    @else
                                        <img src="{{ asset('images/no_foto.jpg') }}" alt="Sin foto">
                                    @endif
								</a>

							</div>
						</div>
                        @endforeach
						<!-- Item / End -->
					</div>

				</div>
				<!-- Widget / End -->

			</div>
		</div>
		<!-- Sidebar / End -->

	</div>
</div>

@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('propertyContactForm');
    const messageBox = document.getElementById('form-message');

    if (!form || !messageBox) {
        console.error('Falta form o form-message');
        return;
    }

    const submitBtn = form.querySelector('button[type="submit"]');

    if (!submitBtn) {
        console.error('No se encontró el botón submit dentro del formulario');
        return;
    }

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
});
</script>

<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('propertyShare', () => ({
        label: 'Compartir propiedad',
        defaultLabel: 'Compartir propiedad',
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
