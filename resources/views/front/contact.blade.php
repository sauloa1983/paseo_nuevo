@extends('layouts.front')
@section('title', 'Centro de Ayuda - Paseo España Inmobiliaria')

@php
    $whatsappAsesor = whatsapp_url_legal();
    $whatsappPhoneDisplay = whatsapp_format_phone((string) config('services.whatsapp.legal.phone', config('services.whatsapp.default_phone')));
    $telefonoFijo = '(607) 697 8295';
    $telefonoFijoHref = 'tel:+576076978295';
    $celularesDisplay = '310 818 0746 | 310 258 6500';
    $emailGeneral = 'comercial@paseoespana.com';

    $oficinasUbicacion = [
        'bucaramanga' => [
            'address' => 'Cra 26 No. 34-53, Bucaramanga',
            'map_embed' => 'https://maps.google.com/maps?q=Carrera+26+%2334-53,+Bucaramanga,+Santander,+Colombia&hl=es&z=16&output=embed',
        ],
        'floridablanca' => [
            'address' => 'Calle 29 # 29-33, Floridablanca',
            'map_embed' => 'https://maps.google.com/maps?q=Calle+29+%2329-33,+Floridablanca,+Santander,+Colombia&hl=es&z=16&output=embed',
        ],
        'piedecuesta' => [
            'address' => 'Calle 9 # 10-96, Piedecuesta',
            'map_embed' => 'https://maps.google.com/maps?q=Calle+9+%2310-96,+Piedecuesta,+Santander,+Colombia&hl=es&z=16&output=embed',
        ],
        'giron' => [
            'address' => 'Calle 39 # 22-07, Girón',
            'map_embed' => 'https://maps.google.com/maps?q=Calle+39+%2322-07,+Gir%C3%B3n,+Santander,+Colombia&hl=es&z=16&output=embed',
        ],
    ];

    $primeraCiudad = $ciudadesConOficina->first();
    $ubicacionInicial = $primeraCiudad
        ? ($oficinasUbicacion[mb_strtolower($primeraCiudad->nombre)] ?? $oficinasUbicacion['bucaramanga'])
        : $oficinasUbicacion['bucaramanga'];
@endphp

@section('content')

@include('partials.banner-hero', [
    'headingId' => 'tenant-hero-heading',
    'title' => 'Centro de Ayuda',
    'accent' => 'Clientes',
    'text' => 'Resolvemos tus dudas sobre la consignación de tu inmueble, pagos de administración, coberturas de seguro de arrendamiento y todo el respaldo legal que te brindamos.',
    'image' => 'images/inmueble.jpg',
    'breadcrumbLabel' => '',
    'showActions' => false, {{-- Nuevo Prop --}}
])



@if ($ciudadesConOficina->isNotEmpty())
    <section
        class="pe-contact-offices"
        aria-labelledby="contact-offices-heading"
        x-data="{ activeTab: '{{ $primeraCiudad?->id }}' }"
    >
        <div class="container">
            <p class="titulo-rojo-secciones">Contacta a nuestras oficinas</p>
            <!--<h2 id="contact-offices-heading" class="pe-contact-offices__title">Contacta a nuestras oficinas</h2>    -->

            <div class="pe-contact-tabs" role="tablist" aria-label="Ciudades">
                @foreach ($ciudadesConOficina as $ciudad)
                    <button
                        type="button"
                        role="tab"
                        class="pe-contact-tabs__btn"
                        :class="{ 'is-active': activeTab === '{{ $ciudad->id }}' }"
                        :aria-selected="activeTab === '{{ $ciudad->id }}'"
                        @click="activeTab = '{{ $ciudad->id }}'; $dispatch('pe-contact-ciudad', { nombre: @js($ciudad->nombre) })"
                    >
                        {{ $ciudad->nombre }}
                    </button>
                @endforeach
            </div>

            @foreach ($ciudadesConOficina as $ciudad)
                <div
                    role="tabpanel"
                    class="pe-contact-offices__panel"
                    x-show="activeTab === '{{ $ciudad->id }}'"
                    x-cloak
                >
                    @if ($ciudad->contacts->isNotEmpty())
                        <div class="pe-contact-dept-grid">
                            @foreach ($ciudad->contacts as $contacto)
                                @include('partials.office-contact-card', ['contacto' => $contacto, 'ciudad' => $ciudad])
                            @endforeach
                        </div>
                    @else
                        <p class="pe-contact-offices__empty">No hay dependencias registradas para esta oficina.</p>
                    @endif
                </div>
            @endforeach
        </div>
    </section>
@endif

<section
    class="pe-contact-main"
    aria-labelledby="contact-find-heading"
    x-data="{
        activeTab: '{{ $primeraCiudad?->id ?? '' }}',
        ubicaciones: @js($oficinasUbicacion),
        mapUrl: @js($ubicacionInicial['map_embed']),
        address: @js($ubicacionInicial['address']),
        setCiudad(nombre) {
            const key = nombre.toLowerCase().normalize('NFD').replace(/[\u0300-\u036f]/g, '');
            const u = this.ubicaciones[key] || this.ubicaciones['bucaramanga'];
            this.mapUrl = u.map_embed;
            this.address = u.address;
        }
    }"
    x-on:pe-contact-ciudad.window="setCiudad($event.detail.nombre)"
>
    <div class="container">
        <div class="pe-contact-main__grid">
            <div class="pe-contact-main__info">
                <h2 id="contact-find-heading" class="pe-contact-main__title">Encuéntranos</h2>

                <ul class="pe-contact-info-list">
                    <li>
                        <span class="pe-contact-info-list__icon" aria-hidden="true"><i class="fa fa-map-marker"></i></span>
                        <div>
                            <strong>Dirección</strong>
                            <p x-text="address">{{ $ubicacionInicial['address'] }}</p>
                        </div>
                    </li>
                    <li>
                        <span class="pe-contact-info-list__icon" aria-hidden="true"><i class="fa fa-clock-o"></i></span>
                        <div>
                            <strong>Horarios de atención</strong>
                            <p>
                                Lunes a viernes: 8:00 a 12:00 / 1:00 a 5:00<br>
                                Sábado: 8:00 a 12:00
                            </p>
                        </div>
                    </li>
                    <li>
                        <span class="pe-contact-info-list__icon" aria-hidden="true"><i class="fa fa-phone"></i></span>
                        <div>
                            <strong>Teléfonos</strong>
                            <p>
                                Fijo: <a href="{{ $telefonoFijoHref }}">{{ $telefonoFijo }}</a><br>
                                Celulares: {{ $celularesDisplay }}
                            </p>
                        </div>
                    </li>
                    <li>
                        <span class="pe-contact-info-list__icon" aria-hidden="true"><i class="fa fa-envelope-o"></i></span>
                        <div>
                            <strong>Correo electrónico</strong>
                            <p><a href="mailto:{{ $emailGeneral }}">{{ $emailGeneral }}</a></p>
                        </div>
                    </li>
                </ul>

                <div class="pe-contact-map">
                    <iframe
                        :src="mapUrl"
                        src="{{ $ubicacionInicial['map_embed'] }}"
                        title="Ubicación de Paseo España Inmobiliaria"
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        allowfullscreen
                    ></iframe>
                </div>
            </div>

            <div class="pe-contact-main__form" id="pe-contact-form">
                <h2 class="pe-contact-main__title">Escríbenos</h2>
                <p class="pe-contact-main__subtitle">Te responderemos lo antes posible.</p>

                <div id="form-message" class="pe-contact-form-message" style="display:none;" role="alert"></div>

                <form id="contact-form" class="pe-contact-form" method="POST" action="{{ route('contact.send') }}">
                    @csrf
                    <div class="pe-contact-form__row">
                        <label class="pe-contact-form__field">
                            <span class="sr-only">Nombre completo</span>
                            <input type="text" name="name" placeholder="Nombre completo" required>
                        </label>
                        <label class="pe-contact-form__field">
                            <span class="sr-only">Correo electrónico</span>
                            <input type="email" name="email" placeholder="Correo electrónico" required>
                        </label>
                    </div>
                    <label class="pe-contact-form__field">
                        <span class="sr-only">Asunto</span>
                        <input type="text" name="subject" placeholder="Asunto" required>
                    </label>
                    <label class="pe-contact-form__field">
                        <span class="sr-only">Mensaje</span>
                        <textarea name="comments" placeholder="Mensaje" rows="6" required></textarea>
                    </label>
                    <button type="submit" id="submitBtn" class="pe-services-btn pe-services-btn--primary pe-contact-form__submit">
                        <i class="fa fa-paper-plane" aria-hidden="true"></i>
                        Enviar mensaje
                    </button>
                </form>
            </div>
        </div>
    </div>
</section>
<div class="margin-top-35"></div>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('contact-form');
    const messageBox = document.getElementById('form-message');

    if (!form || !messageBox) {
        return;
    }

    const submitBtn = form.querySelector('button[type="submit"]');
    const defaultBtnText = submitBtn ? submitBtn.innerHTML : '';

    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        messageBox.style.display = 'none';
        messageBox.className = 'pe-contact-form-message';
        messageBox.innerHTML = '';

        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin" aria-hidden="true"></i> Enviando...';
        }

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: new FormData(form),
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                },
            });

            const data = await response.json();

            if (response.ok) {
                messageBox.classList.add('is-success');
                messageBox.style.display = 'block';
                messageBox.innerHTML = data.message;
                form.reset();
            } else {
                let errors = data.message || 'Ocurrió un error al enviar.';

                if (data.errors) {
                    errors = Object.values(data.errors).flat().join('<br>');
                }

                messageBox.classList.add('is-error');
                messageBox.style.display = 'block';
                messageBox.innerHTML = errors;
            }
        } catch {
            messageBox.classList.add('is-error');
            messageBox.style.display = 'block';
            messageBox.innerHTML = 'Error de conexión. Intenta de nuevo.';
        } finally {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = defaultBtnText;
            }
        }
    });

});
</script>
@endpush
