@php
    $formKey = $formKey ?? 'natural';
    $libertadorUrl = tenant_form_url('libertador', $formKey);
    $zurichUrl = tenant_form_url('zurich', $formKey);
@endphp

<div class="pe-card__downloads">
    <p class="pe-card__downloads-label">Formularios de estudio (formatos distintos por aseguradora)</p>
    <div class="pe-card__downloads-actions">
        @if ($zurichUrl)
            <a href="{{ $zurichUrl }}" class="pe-requirements__btn" download>
                <i class="fa fa-download" aria-hidden="true"></i>
                Formulario Zurich
            </a>
        @endif
        @if ($libertadorUrl)
            <a href="{{ $libertadorUrl }}" class="pe-requirements__btn" download>
                <i class="fa fa-download" aria-hidden="true"></i>
                Formulario El Libertador
            </a>
        @endif
    </div>
</div>
