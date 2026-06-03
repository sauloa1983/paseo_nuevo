@php
    $headingId = $headingId ?? 'requirements-downloads-heading';
    $wrapperClass = $wrapperClass ?? 'pe-form-downloads';
    $formKey = $formKey ?? 'juridica';
    $libertadorUrl = tenant_form_url('libertador', $formKey);
    $zurichUrl = tenant_form_url('zurich', $formKey);
@endphp

<div class="{{ $wrapperClass }}" aria-labelledby="{{ $headingId }}">
    <span class="pe-card__icon pe-form-downloads__icon" aria-hidden="true">
        <i class="fa fa-file-text-o"></i>
    </span>
    <h3 id="{{ $headingId }}" class="pe-form-downloads__title">Descarga los formularios</h3>
    <p class="pe-form-downloads__text">
        Descargue únicamente el de la aseguradora con la que realizará su estudio.
    </p>
    <div class="pe-form-downloads__actions">
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
