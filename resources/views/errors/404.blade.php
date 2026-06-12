@extends('layouts.front')

@section('title', 'Página no encontrada - Paseo España Inmobiliaria')

@section('content')
<section class="pe-error-404" aria-labelledby="error-404-heading">
    <div class="container">
        <div class="pe-error-404__inner">
            <p class="pe-error-404__code" aria-hidden="true">404</p>
            <h1 id="error-404-heading" class="pe-error-404__title">
                Página no <span class="pe-services-accent">encontrada</span>
            </h1>
            <p class="pe-error-404__text">
                Lo sentimos, la dirección que buscas no existe o ya no está disponible.
                Puedes volver al inicio o explorar nuestros inmuebles disponibles.
            </p>
            <div class="pe-error-404__actions">
                <a href="{{ route('home') }}" class="pe-services-btn pe-services-btn--primary">
                    Ir al inicio
                </a>
                <a href="{{ route('inmuebles.search') }}" class="pe-services-btn pe-services-btn--outline">
                    Ver inmuebles
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
