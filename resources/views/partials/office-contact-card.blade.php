@props(['contacto', 'ciudad'])

@php
    $phoneClean = trim((string) $contacto->phones);
    $hasMultipleNumbers = str_contains($phoneClean, ',') || str_contains($phoneClean, '/') || str_contains($phoneClean, '-');
    $digitsOnly = preg_replace('/\D/', '', $phoneClean);
    $isCellPhone = strlen($digitsOnly) === 10 && str_starts_with($digitsOnly, '3');
    $showWhatsApp = $phoneClean !== '' && ! $hasMultipleNumbers && $isCellPhone;
    $whatsappMessage = 'Hola, me comunico con el departamento de ' . $contacto->department . ' de Paseo España en la sede de ' . $ciudad->nombre . '.';
    $whatsappHref = $showWhatsApp ? 'https://api.whatsapp.com/send?phone=57' . $digitsOnly . '&text=' . urlencode($whatsappMessage) : null;

    $deptLower = mb_strtolower($contacto->department);
    $icon = match (true) {
        str_contains($deptLower, 'comercial') => 'fa-briefcase',
        str_contains($deptLower, 'contrato') || str_contains($deptLower, 'arriendo') => 'fa-file-text-o',
        str_contains($deptLower, 'reparacion') || str_contains($deptLower, 'arreglo') => 'fa-wrench',
        str_contains($deptLower, 'caja') || str_contains($deptLower, 'cartera') => 'fa-money',
        str_contains($deptLower, 'administracion') || str_contains($deptLower, 'admon') => 'fa-building-o',
        str_contains($deptLower, 'gerencia') => 'fa-user',
        default => 'fa-envelope-o',
    };
@endphp

<article class="pe-contact-dept-card">
    <header class="pe-contact-dept-card__head">
        <span class="pe-contact-dept-card__icon" aria-hidden="true">
            <i class="fa {{ $icon }}"></i>
        </span>
        <h3 class="pe-contact-dept-card__title">{{ $contacto->department }}</h3>
    </header>

    <ul class="pe-contact-dept-card__meta">
        <li>
            <i class="fa fa-envelope-o" aria-hidden="true"></i>
            <a href="mailto:{{ trim($contacto->email) }}">{{ trim($contacto->email) }}</a>
        </li>
        @if ($phoneClean !== '')
            <li>
                <i class="fa fa-phone" aria-hidden="true"></i>
                <span>{{ $contacto->phones }}</span>
            </li>
        @endif
        @if ($contacto->manager_name)
            <li>
                <i class="fa fa-user-o" aria-hidden="true"></i>
                <span>{{ $contacto->manager_name }}</span>
            </li>
        @endif
    </ul>

    @if ($whatsappHref)
        <a
            href="{{ $whatsappHref }}"
            target="_blank"
            rel="noopener noreferrer"
            class="pe-contact-dept-card__wa"
        >
            <i class="fa fa-whatsapp" aria-hidden="true"></i>
            WhatsApp
        </a>
    @endif
</article>
