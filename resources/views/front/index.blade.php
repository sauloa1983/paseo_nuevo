@extends('layouts.front')

@section('content')

<!-- Hero Search (+ banner publicitario lightbox debajo del buscador) -->
@include('partials.hero-search')

<!-- Nuestra oferta — ciudades destacadas -->
@include('partials.city-offer-grid', ['ciudadesPopulares' => $ciudadesPopulares])

<!-- Carousel Propiedades en arriendo  -->
<section class="fullwidth pe-destacados-section" data-background-color="#f9f9f9">
    <div class="container">
        <div class="row">

            <div class="col-md-12 pe-destacados-section__block" style="margin-top: -50px">
                <div class="pe-destacados-section__header">
                    <h3 class="headline">Destacados en Arriendo</h3>
                    <div class="pe-destacados-section__nav" aria-hidden="true"></div>
                </div>

                <div class="carousel">
                    @foreach($destacados_arriendo as $inmueble)
                    <div class="carousel-item">
                        @include('partials.inmueble-card', ['inmueble' => $inmueble, 'modo' => 'arriendo'])
                    </div>
                    @endforeach
                </div>
            </div>
            <!-- Carousel / End -->

        </div>
    </div>
</section>

<!-- Carousel Propiedades en venta -->
<section class="margin-top-35 pe-destacados-section" data-background-color="#f9f9f9">
    <div class="container">

        <div class="row">

            <div class="col-md-12 pe-destacados-section__block">
                <div class="pe-destacados-section__header">
                    <h3 class="headline margin-bottom-25 margin-top-0">Destacados en Venta</h3>
                    <div class="pe-destacados-section__nav" aria-hidden="true"></div>
                </div>

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
	$('.pe-destacados-section .carousel').each(function () {
		var $carousel = $(this);
		var $nav = $carousel.closest('.pe-destacados-section__block').find('.pe-destacados-section__nav');

		$carousel.slick({
		appendArrows: $nav,
		infinite: true,
		slidesToShow: 3,
		slidesToScroll: 1,
		responsive: [
			{
				breakpoint: 1200,
				settings: {
					slidesToShow: 2,
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
				breakpoint: 580,
				settings: {
					slidesToShow: 1,
					slidesToScroll: 1
				}
			}
		]
		});
	});
	</script>

    <script>
    // Valida que el estado (Arriendo/Venta) sea obligatorio cuando hay precio min/max.
    function peValidateStatusRequired(form) {
        const min = form.querySelector('[name="min_price"]');
        const max = form.querySelector('[name="max_price"]');
        const minVal = min ? parseInt((min.value || '').replace(/\D/g, '')) || 0 : 0;
        const maxVal = max ? parseInt((max.value || '').replace(/\D/g, '')) || 0 : 0;

        const checked = form.querySelector('input[name="status"]:checked');
        const select = form.querySelector('select[name="status"]');
        const statusVal = checked ? checked.value : (select ? select.value : '');
        const statusField = form.querySelector('.search-type');

        if ((minVal > 0 || maxVal > 0) && !statusVal) {
            if (statusField) { statusField.classList.add('is-required-error'); }
            return false;
        }

        if (statusField) { statusField.classList.remove('is-required-error'); }
        return true;
    }

    document.querySelector('.main-search-form').addEventListener('submit', function(e) {

    // Estado obligatorio si se establece precio min/max
    if (!peValidateStatusRequired(this)) {
        e.preventDefault();
        e.stopImmediatePropagation();
        return false;
    }

    const status = this.querySelector('input[name="status"]:checked')?.value ?? 'rent';

    if (status === 'codigo') {
        const code = document.getElementById('code').value.trim();

        if (!code) {
            e.preventDefault();
            document.getElementById('code')?.focus();
            return false;
        }

        e.preventDefault();

        const urlPlantilla = '{{ route("inmuebles.show", ":codigo") }}';
        const urlLimpia = urlPlantilla.replace(':codigo', encodeURIComponent(code));

        window.location.href = urlLimpia;
        return false;
    }

    this.action = '{{ route("inmuebles.search") }}';
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

            // Submit safe: deja los precios vacíos como vacíos (sin forzar 0)
            const form = minInput.closest('form');
            if (form) {
                form.addEventListener('submit', (e) => {
                    safeCleanPrice(minInput);
                    safeCleanPrice(maxInput);

                    // Si quedan vacíos, deshabilítalos para que NO viajen en la URL
                    if (!minInput.value.trim()) { minInput.disabled = true; }
                    if (!maxInput.value.trim()) { maxInput.disabled = true; }
                });
            }
        }
    });
    </script>



@endpush
