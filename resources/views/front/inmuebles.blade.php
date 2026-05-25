@extends('layouts.front')

@section('content')

<!-- Search
================================================== -->
<section class="search margin-bottom-50">
<div class="container">
	<div class="row">
		<div class="col-md-12">

			<!-- Title -->
			<h3 class="search-title">Busqueda de inmuebles</h3>

            <!-- Form -->
            <form class="" name="search-property" action="" method="GET">
                @if(request()->filled('layout'))
                    <input type="hidden" name="layout" value="{{ request('layout') }}">
                @endif
                <div class="main-search-box no-shadow">


                    <!-- Row With Forms -->
                    <div class="row with-forms">

                        <!-- Status -->
                        <div class="col-md-2">
                            <select name="status" data-placeholder="Estado del Inmueble" class="custom-select" >
                                <option>Cualquiera</option>
                                <option value="sale" {{ request('status') == 'sale' ? 'selected' : '' }}>Venta</option>
                                <option value="rent" {{ request('status') == 'rent' ? 'selected' : '' }}>Arriendo</option>
                            </select>
                        </div>

                        <!-- Property Type -->
                        <div class="col-md-3">
                            <select name="type" data-placeholder="Tipo Inmueble" class="custom-select">
                                <option value="">Tipo Inmueble</option>
                                @foreach($tipos_inm as $val =>$tipo)
                                    <option value="{{ $val }}" {{ request('type') == $val ? 'selected' : '' }}>
                                        {{ ucfirst($tipo) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-3">
                            <select name="ciudad" id="ciudad-select" data-placeholder="Ciudad" class="custom-select" >
                                <option value="">Ciudad</option>
                                @foreach($ciudades as $id => $nombre)
                                    <option value="{{ $id }}" {{ request('ciudad') == $id ? 'selected' : '' }}>
                                        {{ ucfirst($nombre) }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Main Search Input -->
                        <div class="col-md-4">
                            <div class="main-search-input">
                                <select name="barrio" id="barrio-select" data-placeholder="Barrio" class="custom-select">
                                    <option value="">Barrio</option>
                                </select>
                                <button class="button">Buscar</button>
                            </div>
                        </div>

                    </div>
                    <!-- Row With Forms / End -->


                    <!-- Row With Forms -->
                    <div class="row with-forms">

                        <!-- Min Price -->
                        <div class="col-md-3">
                            <div class="select-input">
                                <input type="text"
                                    name="min_price"
                                    placeholder="Precio minimo"
                                    data-unit="COP"
                                    value="{{ request()->has('min_price') && request('min_price') ? number_format(str_replace('.', '', request('min_price')), 0, ',', '.') : '' }}">
                            </div>
                        </div>


                        <!-- Max Price -->
                        <div class="col-md-3">
                            <div class="select-input">
                                <input type="text"
                                    name="max_price"
                                    placeholder="Precio maximo"
                                    data-unit="COP"
                                    value="{{ request()->has('max_price') && request('max_price') ? number_format(str_replace('.', '', request('max_price')), 0, ',', '.') : '' }}">
                            </div>
                        </div>

                        <!-- No Habitaciones -->
                        <div class="col-md-3">
                            <select name="rooms" data-placeholder="Habitaciones" class="custom-select" >
                                <option value="">Habitaciones (Cualquiera)</option>
                                <option value="1" {{ request('rooms') == '1' ? 'selected' : '' }}>1</option>
                                <option value="2" {{ request('rooms') == '2' ? 'selected' : '' }}>2</option>
                                <option value="3" {{ request('rooms') == '3' ? 'selected' : '' }}>3</option>
                                <option value="4" {{ request('rooms') == '4' ? 'selected' : '' }}>4</option>
                                <option value="5" {{ request('rooms') == '5' ? 'selected' : '' }}>5+</option>
                            </select>
                        </div>

                        <!-- No Baños -->
                        <div class="col-md-3">
                            <select name="bathrooms" data-placeholder="Baños" class="custom-select" >
                                <option value="">Baños (Cualquiera)</option>
                                <option value="1" {{ request('bathrooms') == '1' ? 'selected' : '' }}>1</option>
                                <option value="2" {{ request('bathrooms') == '2' ? 'selected' : '' }}>2</option>
                                <option value="3" {{ request('bathrooms') == '3' ? 'selected' : '' }}>3</option>
                                <option value="4" {{ request('bathrooms') == '4' ? 'selected' : '' }}>4</option>
                                <option value="5" {{ request('bathrooms') == '5' ? 'selected' : '' }}>5+</option>
                            </select>
                        </div>
                    </div>
                    <!-- Row With Forms / End -->


                    <!-- More Search Options -->
                    <a href="#" class="more-search-options-trigger margin-top-10" data-open-title="Más Opciones" data-close-title="Menos Opciones"></a>

                    <div class="more-search-options relative">
                        <div class="more-search-options-container">
                            <div class="row with-forms">
                                <!-- Area min -->
                                <div class="col-md-4
                                ">
                                    <div class="select-input">
                                        <input type="text"
                                            name="min_area"
                                            placeholder="Desde"
                                            data-unit="m<sup>2</sup>"
                                            value="{{ request()->has('min_area') && request('min_area') ? number_format(str_replace('.', '', request('min_area')), 0, ',', '.') : '' }}">
                                    </div>
                                </div>

                                <!-- Area max -->
                                <div class="col-md-4">
                                    <div class="select-input">
                                        <input type="text"
                                            name="max_area"
                                            placeholder="Hasta"
                                            data-unit="m<sup>2</sup>"
                                            value="{{ request()->has('max_area') && request('max_area') ? number_format(str_replace('.', '', request('max_area')), 0, ',', '.') : '' }}">
                                    </div>
                                </div>

                                <!-- Estrato -->
                                <div class="col-md-4">
                                    <select name="estrato" data-placeholder="Estrato" class="custom-select" >
                                        <option value="">Estrato (Cualquiera)</option>
                                        <option value="1" {{ request('estrato') == '1' ? 'selected' : '' }}>1</option>
                                        <option value="2" {{ request('estrato') == '2' ? 'selected' : '' }}>2</option>
                                        <option value="3" {{ request('estrato') == '3' ? 'selected' : '' }}>3</option>
                                        <option value="4" {{ request('estrato') == '4' ? 'selected' : '' }}>4</option>
                                        <option value="5" {{ request('estrato') == '5' ? 'selected' : '' }}>5</option>
                                        <option value="6" {{ request('estrato') == '6' ? 'selected' : '' }}>6</option>
                                        <option value="Comercial" {{ request('estrato') == '10' ? 'selected' : '' }}>Comercial</option>
                                    </select>
                                </div>

                            </div>
                            <!-- Row With Forms / End -->


                            <!-- Checkboxes -->
                            <div class="checkboxes in-row">
                                <input id="ascensor" type="checkbox" name="ascensor" value="1"
                                {{ request('ascensor') ? 'checked' : '' }}>
                            <label for="ascensor">Ascensor</label>

                            <input id="piscina" type="checkbox" name="piscina" value="1"
                                {{ request('piscina') ? 'checked' : '' }}>
                            <label for="piscina">Piscina</label>

                            <input id="conjunto_cerrado" type="checkbox" name="conjunto_cerrado" value="1"
                                {{ request('conjunto_cerrado') ? 'checked' : '' }}>
                            <label for="conjunto_cerrado">Conjunto Cerrado</label>

                            <input id="alcoba_servicio" type="checkbox" name="alcoba_servicio" value="1"
                                {{ request('alcoba_servicio') ? 'checked' : '' }}>
                            <label for="alcoba_servicio">Alcoba de Servicio</label>

                            <input id="gimnasio" type="checkbox" name="gimnasio" value="1"
                                {{ request('gimnasio') ? 'checked' : '' }}>
                            <label for="gimnasio">Gimnasio</label>

                            <input id="salon_social" type="checkbox" name="salon_social" value="1"
                                {{ request('salon_social') ? 'checked' : '' }}>
                            <label for="salon_social">Salón Social</label>

                            <input id="balcon" type="checkbox" name="balcon" value="1"
                                {{ request('balcon') ? 'checked' : '' }}>
                            <label for="balcon">Balcón</label>

                            </div>

                            <!-- Checkboxes / End -->

                        </div>

                    </div>
                    <!-- More Search Options / End -->


                </div>
            </form>
			<!-- Box / End -->
		</div>
	</div>
</div>
</section>
<!-- End search -->

<div class="container">
	<div class="row fullwidth-layout">
		<div class="col-md-12"></div>
			<!-- Validamos que hallan resultados -->

				<!-- Sorting / Layout Switcher -->
            <div class="row margin-bottom-15">
                <div class="col-md-6">
                    <!-- Sort by -->
                    <div class="sort-by">
                        <label>Ordenar por:</label>

                        <!-- MINI-FORM para ordenamiento -->
                        <form action="{{ url()->current() }}" method="GET" style="display: inline;">
                            <!-- Copiar TODOS los filtros actuales -->
                            @foreach(request()->except('order', 'page') as $key => $value)
                                @if(is_scalar($value))
                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                @endif
                            @endforeach

                            <div class="sort-by-select">
                                <select data-placeholder="Default order" class="chosen-select-no-single" name="order" onchange="this.form.submit()">
                                    <option value="">Predeterminado</option>
                                    <option value="precio_asc" {{ request('order') == 'precio_asc' ? 'selected' : '' }}>Precio: Bajo → Alto</option>
                                    <option value="precio_desc" {{ request('order') == 'precio_desc' ? 'selected' : '' }}>Precio: Alto → Bajo</option>
                                </select>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="col-md-6">
                    @php
                        $activeLayout = request('layout', 'list');
                        $layoutQuery = fn (string $layout) => '?' . http_build_query(array_merge(request()->except('page'), ['layout' => $layout]));
                    @endphp
                    <!-- Layout Switcher -->
                    <div class="layout-switcher">
                        <a href="{{ $layoutQuery('list') }}"
                        class="list {{ $activeLayout === 'list' ? 'active' : '' }}" data-layout="list">
                            <i class="fa fa-th-list"></i>
                        </a>
                        <a href="{{ $layoutQuery('grid') }}"
                        class="grid {{ $activeLayout === 'grid' ? 'active' : '' }}" data-layout="grid">
                            <i class="fa fa-th-large"></i>
                        </a>
                        <a href="{{ $layoutQuery('grid-three') }}"
                        class="grid-three {{ $activeLayout === 'grid-three' ? 'active' : '' }}" data-layout="grid-three">
                            <i class="fa fa-th"></i>
                        </a>
                    </div>

                </div>
            </div>


			<!-- RESULTADOS (contenedor AJAX) -->
			<div id="listings-container_gral">
				@include('partials.lista', [
                    'properties' => $properties ?? collect(),
                    'layout' => $layout ?? 'list',
                ])
			</div>
		</div>
    </div>
</div>
@endsection


@push('scripts')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function() {


    // Filtros en tiempo real (opcional)
    $('#searchForm select, #searchForm input').on('change', function() {
        $('#searchForm').trigger('submit');
    });

    // Submit form AJAX
    $('#searchForm').on('submit', function(e) {
        e.preventDefault();
        let url = $(this).attr('action');

        $.ajax({
            url: url,
            type: 'GET',
            data: $(this).serialize(),
            success: function(data) {
                $('#inmuebles-container').html(data.html);
                window.history.pushState({}, '', url + '?' + $('#searchForm').serialize());
            }
        });
    });
});

document.addEventListener('DOMContentLoaded', function() {

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

    const urlParams = new URLSearchParams(window.location.search);
    const ciudadId = urlParams.get('ciudad');
    const barrioId = urlParams.get('barrio');

    const ciudadSelect = document.getElementById('ciudad-select');
    const barrioSelect = document.getElementById('barrio-select');

    if (!ciudadSelect || !barrioSelect) {
        console.log('❌ Selects no encontrados');
        return;
    }

    // Restaurar desde URL
    if (ciudadId) {
        ciudadSelect.value = ciudadId;
    }

    // Evento cambio ciudad (único)
    ciudadSelect.addEventListener('change', function() {
        const newCiudadId = this.value;
        //barrioSelect.innerHTML = '<option value="">Todos los barrios</option>';

        if (newCiudadId) {
            barrioSelect.innerHTML = '<option value="">Cargando...</option>';
            fetch(`/barrios/${newCiudadId}`)
                .then(r => r.json())
                .then(barrios => {
                    barrioSelect.innerHTML = '<option value="">Todos los barrios</option>';
                    barrios.forEach(barrio => {
                        const option = new Option(barrio.nombre, barrio.codigo_barrio);
                        barrioSelect.add(option);
                    });

                    // Restaurar barrio SOLO si coincide la ciudad actual
                    if (barrioId && newCiudadId == ciudadId) {
                        barrioSelect.value = barrioId;
                    }
                })
                .catch(err => {
                    console.error('Error cargando barrios:', err);
                });
        } else {
            barrioSelect.innerHTML = '<option value="">Selecciona ciudad</option>';
        }
    });

    // Cargar barrios inicial si hay ciudad en URL
    if (ciudadId) {
        ciudadSelect.dispatchEvent(new Event('change'));
    }

    // ========== PRECIOS ==========

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
