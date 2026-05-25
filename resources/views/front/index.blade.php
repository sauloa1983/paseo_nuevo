@extends('layouts.front')

@section('content')

<!-- Hero Search -->
@include('partials.hero-search')

<!-- Container -->
<div class="container">
	<div class="row">

		<div class="col-md-12 margin-top-60">
			<h3 class="headline centered margin-bottom-35 margin-top-10">Nuestra oferta  <span>Encuentra tu inmueble ideal</span></h3>
		</div>

		@php $ciudades = $ciudadesPopulares->shuffle(); @endphp

        @foreach($ciudades as $index => $ciudadItem)
            @if($loop->iteration % 2 == 1)
                {{-- INICIO NUEVA FILA --}}
                <div class="" style="">
            @endif
                    <div class="col-md-{{ ($loop->iteration == 1 || $loop->iteration == 4) ? '4' : '8' }}">
                        <a href="{{ route('inmuebles.search') }}?ciudad={{ $ciudadItem->ciudad }}" class="img-box" data-background-image="/storage/{{ $ciudadItem->ciudadRelacion()->first()->imagen }}">

                            <div class="img-box-content visible">
                                <h4>{{ $ciudadItem->ciudadRelacion()->first()->nombre ?? 'Nombre no disponible' }}</h4>
                                <span>{{ $ciudadItem->total }} Propiedades</span>
                            </div>
                        </a>
                    </div>
            @if($loop->iteration % 2 == 0 || $loop->last)
                {{-- FIN FILA --}}
                </div>
            @endif
        @endforeach


	</div>
</div>
<!-- Container / End -->

<!-- Carousel Propiedades en arriendo  -->
<section class="fullwidth " data-background-color="#f9f9f9">
    <div class="container">
        <div class="row">

            <div class="col-md-12" style="margin-top: -50px">
                <h3 class="headline ">Destacados en Arriendo</h3>
            </div>

            <!-- Carousel -->
            <div class="col-md-12">
                <div class="carousel">

                    @foreach($destacados_arriendo as $inmueble)
                    <div class="carousel-item">
                        @include('partials.inmueble-card', ['inmueble' => $inmueble, 'modo' => 'arriendo'])
                    </div>
                    @endforeach

                    <!-- Listing Item / End -->

                </div>
            </div>
            <!-- Carousel / End -->

        </div>
    </div>
</section>

<!-- Carousel Propiedades en arriendo -->
<section class="margin-top-35" data-background-color="#f9f9f9">
    <div class="container">

        <div class="row">

            <div class="col-md-12">
                <h3 class="headline margin-bottom-25 margin-top-0">Destacados en Venta</h3>
            </div>

            <!-- Carousel -->
            <div class="col-md-12">
                <div class="carousel">


                    @foreach($destacados_ventas as $inmueble)
                    <div class="carousel-item">
                        @include('partials.inmueble-card', ['inmueble' => $inmueble, 'modo' => 'venta'])
                    </div>
                    @endforeach

                </div>
            </div>
            <!-- Carousel / End -->

        </div>
    </div>
</section>




<!-- Socios -->
@include('partials.socios')



@endsection

@push('scripts')
    <!-- JS específicos para carousel, search... del HTML -->
	<script>
	// Inicializa carousel propiedades dinámicas
	$('.carousel').slick({
		infinite: true,
		slidesToShow: 4,
		slidesToScroll: 1,
		responsive: [
			{
				breakpoint: 1024,
				settings: {
					slidesToShow: 3,
					slidesToScroll: 1
				}
			},
			{
				breakpoint: 768,
				settings: {
					slidesToShow: 2,
					slidesToScroll: 1
				}
			},
			{
				breakpoint: 480,
				settings: {
					slidesToShow: 1,
					slidesToScroll: 1
				}
			}
		]
	});
	</script>

    <script>
    document.querySelector('.main-search-form').addEventListener('submit', function(e) {

    const code = document.getElementById('code').value.trim();

        if (code) {
            e.preventDefault();

            // 🔍 1. Construir URL plantilla
            const urlPlantilla = '{{ route("inmuebles.show", ":codigo") }}';

            // 🔍 2. Reemplazar SOLO :codigo
            const urlLimpia = urlPlantilla.replace(':codigo', encodeURIComponent(code));

            // 🔍 3. LIMPIAR TODOS params (sin searchParams.set!)
            console.log('🚀 URL final:', urlLimpia);  // DEBUG

            window.location.href = urlLimpia;  // /inmuebles/7264 ✅ SIN query string
            return false;
        } else {
            this.action = '{{ route("inmuebles.search") }}';   // /inmuebles
        }
    });
    </script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        'use strict';

        // 🔧 FUNCIONES SEGUROS (null-proof)
        function safeFormatPrice(input) {
            if (!input || !input.value) return;  // ← FIX: ignora undefined
            let value = input.value.replace(/\D/g, '');
            value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            input.value = value;
        }

        function safeCleanPrice(input) {
            if (!input || !input.value) return;
            input.value = input.value.replace(/\D/g, '');
        }

        // 1️⃣ BARRIOS POR CIUDAD (intacto + safe)
        const ciudadSelect = document.querySelector('select[name="ciudad"]');
        const barrioSelect = document.querySelector('select[name="barrio"]');

        if (ciudadSelect && barrioSelect) {
            ciudadSelect.addEventListener('change', function() {
                const ciudadId = this.value;

                if (ciudadId) {
                    $(barrioSelect).empty().append('<option value="">Cargando...</option>').trigger('chosen:updated');
                    //barrioSelect.innerHTML = '<option value="">Cargando...</option>';
                    fetch(`/barrios/${ciudadId}`)
                    .then(r => r.json())
                    .then(barrios => {
                        /*barrioSelect.innerHTML = '<option value="">Todos los barrios</option>';
                        barrios.forEach(b => barrioSelect.add(new Option(b.nombre, b.codigo_barrio)));
                        barrioSelect.classList.add('chosen-select-no-single');
                        // Chosen safe
                        if (typeof $ !== 'undefined' && $(barrioSelect).hasClass('chosen-select')) {
                            $(barrioSelect).trigger('chosen:updated');*/
                            // Limpiar y poblar opciones usando jQuery + Chosen
                        $(barrioSelect).empty().append('<option value="">Todos los barrios</option>');

                        barrios.forEach(b => {
                            $(barrioSelect).append(new Option(b.nombre, b.codigo_barrio));
                        });

                        // Actualizar Chosen ⚠️ ESTO ES CLAVE
                        $(barrioSelect).trigger('chosen:updated');

                    })
                    .catch(e => {
                        console.error('Fetch error:', e);
                        $(barrioSelect).empty().append('<option value="">Error</option>').trigger('chosen:updated');$(barrioSelect).empty().append('<option value="">Error</option>').trigger('chosen:updated');
                        //barrioSelect.innerHTML = '<option value="">Error</option>';
                    });
                } else {
                    $(barrioSelect).empty().append('<option value="">Selecciona ciudad</option>').trigger('chosen:updated');
                    //barrioSelect.innerHTML = '<option value="">Selecciona ciudad</option>';
                }
            });
        }

        // 2️⃣ PRECIOS + VALIDACIÓN (solo si EXISTEN)
        const minInput = document.querySelector('[name="min_price"]');
        const maxInput = document.querySelector('[name="max_price"]');

        if (minInput && maxInput) {  // ← Solo ejecuta si inputs existen
            // Eventos safe
            ['input', 'keyup', 'blur'].forEach(event => {
                minInput.addEventListener(event, () => safeFormatPrice(minInput));
                maxInput.addEventListener(event, () => safeFormatPrice(maxInput));
            });

            // Validación (de tu código anterior)
            function validatePrices() {
                const minVal = parseInt(minInput.value?.replace(/\D/g, '') || '0') || 0;
                const maxVal = parseInt(maxInput.value?.replace(/\D/g, '') || '0') || 0;

                if (minVal > 0 && maxVal === 0 && !maxInput.value?.trim()) {
                    maxInput.value = '0';
                }
            }


            // Submit safe
            const form = minInput.closest('form');
            if (form) {
                form.addEventListener('submit', (e) => {
                    safeCleanPrice(minInput);
                    safeCleanPrice(maxInput);
                    validatePrices();
                    // ... resto validación submit
                });
            }
        }
    });
    </script>



@endpush
