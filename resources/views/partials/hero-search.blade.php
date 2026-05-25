@php
    $parallaxImages = [
        asset('images/home-parallax.jpg'),
        asset('images/home-parallax-2.jpg'),
    ];

    $randomParallax = $parallaxImages[array_rand($parallaxImages)];
@endphp
<div class="parallax"
     data-background="{{ $randomParallax }}"
     data-color="#36383e"
     data-color-opacity="0.45"
     data-img-width="2500"
     data-img-height="1600">

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
                                Tu patrimonio merece expertos inmobiliarios y legales que caminen contigo.
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
                                <div class="search-type-arrow"></div>
                            </div>

                            <div class="main-search-box">
                                <!-- Main Search Input (Google Autocomplete) -->
                                <div class="main-search-input larger-input">
                                    <input type="text"
                                        class="ico-01"
                                        id="code"
                                        name="code"
                                        placeholder="Ingrese el codigo del inmueble"
                                        value="{{ request('code') }}">
                                    <button type="submit" class="button">Buscar</button>
                                </div>

                                <!-- Filtros Avanzados -->
                                <div class="row with-forms">
                                    <!-- Property Type -->
                                    <div class="col-md-4">
                                        <select name="type" class="custom-select" data-placeholder="Tipo Inmueble">
                                            <option value="">Tipo Inmueble</option>
                                            @foreach($types as $val =>$tipo)
                                                <option value="{{ $val }}" {{ request('type') == $val ? 'selected' : '' }}>
                                                    {{ ucfirst($tipo) }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Municipios -->
                                    <div class="col-md-4">
                                        <select name="ciudad" class="custom-select" id="ciudad-select" >
                                            <option value="">Ciudad</option>
                                            @foreach($ciudades as $id => $nombre)
                                                <option value="{{ $id }}" {{ request('ciudad') == $id ? 'selected' : '' }}>
                                                    {{ $nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <!-- Barrio (vacío inicial) -->
                                        <select name="barrio" id="barrio-select" class="custom-select">
                                            <option value="">Todos los barrios</option>
                                        </select>
                                    </div>
                                </div>

                                <!-- More Options (Toggle) -->
                                <a href="#" class="more-search-options-trigger" data-open-title="Mas opciones" data-close-title="Menos opciones"></a>
                                <div class="more-search-options">
                                    <div class="more-search-options-container">
                                        <!-- Row -->
										<div class="row with-forms">
                                            <!-- Min Price -->
                                            <div class="col-md-6">
                                                <div class="select-input">
                                                    <input type="text"
                                                        name="min_price"
                                                        placeholder="Precio minimo"
                                                        data-unit="COP"
                                                        value="{{ request('min_price') }}">
                                                </div>
                                            </div>

                                            <!-- Max Price -->
                                            <div class="col-md-6">
                                                <div class="select-input">
                                                    <input type="text"
                                                        name="max_price"
                                                        placeholder="Precio maximo"
                                                        data-unit="COP"
                                                        value="{{ request('max_price') }}">
                                                </div>
                                            </div>

											<!-- No Habitaciones -->
											<div class="col-md-6">
												<select name="rooms" data-placeholder="Habitaciones" class="custom-select" >
													<option value="">Habitaciones (Cualquiera)</option>
                                                    <option value="1">1</option>
													<option value="2">2</option>
													<option value="3">3</option>
													<option value="4">4</option>
													<option value="5">5+</option>
												</select>
											</div>

											<!-- No Baños -->
											<div class="col-md-6">
												<select name="bathrooms" data-placeholder="Baños" class="custom-select" >
													<option value="">Baños (Cualquiera)</option>
													<option value="1">1</option>
													<option value="2">2</option>
													<option value="3">3</option>
													<option value="4">4</option>
													<option value="5">5+</option>
												</select>
											</div>
										</div>


                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
