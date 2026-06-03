@php
    $heroYoutubeId = 'WmemTYmRPiM';
    $heroYoutubeEmbed = 'https://www.youtube-nocookie.com/embed/' . $heroYoutubeId
        . '?autoplay=1&mute=1&controls=0&loop=1&playlist=' . $heroYoutubeId
        . '&playsinline=1&rel=0&modestbranding=1&iv_load_policy=3&disablekb=1&fs=0&enablejsapi=1';
@endphp
<div class="parallax parallax--has-video"
     data-color="#000000"
     data-color-opacity="0.45"
     data-img-width="2500"
     data-img-height="1600">

    <div class="parallax-video" aria-hidden="true">
        <iframe
            src="{{ $heroYoutubeEmbed }}"
            title="Paseo España Inmobiliaria"
            loading="eager"
            frameborder="0"
            referrerpolicy="strict-origin-when-cross-origin"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
            allowfullscreen></iframe>
    </div>

    <div class="parallax-content">
        <div class="container">
            <div class="row">
                <div class="col-md-12">

                    <!-- Main Search Container -->
                    <div class="main-search-container">

                        {{-- Propuesta de valor (centrada sobre el buscador) --}}
                        <div class="hero-value-proposition text-center mx-auto">
                            <h1 class="hero-value-proposition__title font-bold">
                                Encuentra el hogar de tus sueños o el espacio ideal para tu negocio.
                            </h1>
                            <p class="hero-value-proposition__subtitle text-lg">
                                EFICACIA EN EL SERVICIO Y SEGURIDAD EN LA INVERSION INMOBILIARIA
                            </p>
                        </div>

                        <!-- Form Búsqueda (Livewire o estándar) -->
                        <form class="main-search-form" name="search-property" action="" method="GET">

                            <!-- Type (Status) -->
                            <div class="search-type">
                                <label class="{{ request('status') != 'rent' ? 'active' : '' }}">
                                    <input class="first-tab" name="status" type="radio" value="rent" {{ request('status', 'rent') == 'rent' ? 'checked' : '' }}>Arriendo
                                </label>
                                <label class="{{ request('status') == 'sale' ? 'active' : '' }}">
                                    <input name="status" type="radio" value="sale" {{ request('status') == 'sale' ? 'checked' : '' }}>Venta
                                </label>
                                <label class="{{ request('status') == 'codigo' ? 'active' : '' }}">
                                    <input name="status" type="radio" value="codigo" {{ request('status') == 'codigo' ? 'checked' : '' }}>Código
                                </label>
                                <div class="search-type-arrow"></div>
                            </div>

                            <div class="main-search-box">
                                {{-- Panel Arriendo / Venta: solo filtros --}}
                                <div class="pe-search-panel pe-search-panel--filters">
                                    <div class="row with-forms pe-search-filters-row">
                                        <div class="col-md-3 col-sm-6">
                                            <x-property-type-multi-select
                                                :options="$types"
                                                :selected="request()->query('type', [])"
                                            />
                                        </div>

                                        <div class="col-md-3 col-sm-6">
                                            <select name="ciudad" class="custom-select" id="ciudad-select">
                                                <option value="">Ciudad</option>
                                                @foreach($ciudades as $id => $nombre)
                                                    <option value="{{ $id }}" {{ request('ciudad') == $id ? 'selected' : '' }}>
                                                        {{ $nombre }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div class="col-md-3 col-sm-6">
                                            <select name="barrio" id="barrio-select" class="custom-select">
                                                <option value="">Todos los barrios</option>
                                            </select>
                                        </div>

                                        <div class="col-md-3 col-sm-6 pe-search-filters-actions">
                                            <button type="submit" class="button pe-search-submit-btn">
                                                <i class="fa fa-search" aria-hidden="true"></i>
                                                <span>Buscar</span>
                                            </button>
                                        </div>
                                    </div>

                                    <a href="#" class="more-search-options-trigger" data-open-title="Mas opciones" data-close-title="Menos opciones"></a>
                                    <div class="more-search-options">
                                        <div class="more-search-options-container">
                                            <div class="row with-forms">
                                                <div class="col-md-6">
                                                    <div class="select-input">
                                                        <input type="text"
                                                            name="min_price"
                                                            placeholder="Precio minimo"
                                                            data-unit="COP"
                                                            value="{{ request('min_price') }}">
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <div class="select-input">
                                                        <input type="text"
                                                            name="max_price"
                                                            placeholder="Precio maximo"
                                                            data-unit="COP"
                                                            value="{{ request('max_price') }}">
                                                    </div>
                                                </div>

                                                <div class="col-md-6">
                                                    <select name="rooms" data-placeholder="Habitaciones" class="custom-select">
                                                        <option value="">Habitaciones (Cualquiera)</option>
                                                        <option value="1" {{ request('rooms') == '1' ? 'selected' : '' }}>1</option>
                                                        <option value="2" {{ request('rooms') == '2' ? 'selected' : '' }}>2</option>
                                                        <option value="3" {{ request('rooms') == '3' ? 'selected' : '' }}>3</option>
                                                        <option value="4" {{ request('rooms') == '4' ? 'selected' : '' }}>4</option>
                                                        <option value="5" {{ request('rooms') == '5' ? 'selected' : '' }}>5+</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-6">
                                                    <select name="bathrooms" data-placeholder="Baños" class="custom-select">
                                                        <option value="">Baños (Cualquiera)</option>
                                                        <option value="1" {{ request('bathrooms') == '1' ? 'selected' : '' }}>1</option>
                                                        <option value="2" {{ request('bathrooms') == '2' ? 'selected' : '' }}>2</option>
                                                        <option value="3" {{ request('bathrooms') == '3' ? 'selected' : '' }}>3</option>
                                                        <option value="4" {{ request('bathrooms') == '4' ? 'selected' : '' }}>4</option>
                                                        <option value="5" {{ request('bathrooms') == '5' ? 'selected' : '' }}>5+</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-6">
                                                    <select name="garaje" data-placeholder="Parqueadero" class="custom-select">
                                                        <option value="">Parqueadero (Cualquiera)</option>
                                                        <option value="0" {{ request('garaje') === '0' ? 'selected' : '' }}>Sin parqueadero</option>
                                                        <option value="1" {{ request('garaje') == '1' ? 'selected' : '' }}>1 o más</option>
                                                        <option value="2" {{ request('garaje') == '2' ? 'selected' : '' }}>2 o más</option>
                                                        <option value="3" {{ request('garaje') == '3' ? 'selected' : '' }}>3 o más</option>
                                                    </select>
                                                </div>

                                                <div class="col-md-6">
                                                    <select name="admon" data-placeholder="Administración" class="custom-select">
                                                        <option value="">Administración (Cualquiera)</option>
                                                        <option value="con" {{ request('admon') == 'con' ? 'selected' : '' }}>Con administración</option>
                                                        <option value="sin" {{ request('admon') == 'sin' ? 'selected' : '' }}>Sin administración</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Panel Código: solo búsqueda por código --}}
                                <div class="pe-search-panel pe-search-panel--code" hidden>
                                    <div class="main-search-input larger-input">
                                        <input type="text"
                                            class="ico-01"
                                            id="code"
                                            name="code"
                                            placeholder="Ingrese el codigo del inmueble"
                                            value="{{ request('code') }}"
                                            autocomplete="off">
                                        <button type="submit" class="button pe-search-submit-btn">
                                            <i class="fa fa-search" aria-hidden="true"></i>
                                            <span>Buscar</span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    {{-- Banner publicitario activo: lightbox al cargar (markup oculto, sin ocupar espacio) --}}
                    @include('partials.home-ad-banner', ['activeBanner' => $activeBanner ?? null])
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('.main-search-form');
    if (!form) return;

    const filtersPanel = form.querySelector('.pe-search-panel--filters');
    const codePanel = form.querySelector('.pe-search-panel--code');
    const codeInput = form.querySelector('#code');
    const statusRadios = form.querySelectorAll('.search-type input[name="status"]');

    function toggleSearchPanels() {
        const status = form.querySelector('.search-type input[name="status"]:checked')?.value ?? 'rent';
        const isCode = status === 'codigo';

        if (filtersPanel) {
            filtersPanel.hidden = isCode;
        }

        if (codePanel) {
            codePanel.hidden = !isCode;
        }

        if (codeInput) {
            codeInput.disabled = !isCode;

            if (!isCode) {
                codeInput.value = '';
            }
        }

        form.querySelectorAll('.pe-search-panel--filters [name]').forEach(function (field) {
            if (field === codeInput) {
                return;
            }

            field.disabled = isCode;
        });
    }

    statusRadios.forEach(function (radio) {
        radio.addEventListener('change', toggleSearchPanels);
    });

    toggleSearchPanels();
});
</script>
@endpush
