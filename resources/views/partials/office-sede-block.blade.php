@props(['sede', 'ciudad'])

<section class="pe-contact-sede-block">
    <header class="pe-contact-sede-block__head">
        <h3 class="pe-contact-sede-block__title">{{ $sede['nombre'] }}</h3>
        <ul class="pe-contact-sede-block__meta">
            @if (filled($sede['direccion'] ?? null))
                <li>
                    <i class="fa fa-map-marker" aria-hidden="true"></i>
                    <span>{{ $sede['direccion'] }}</span>
                </li>
            @endif
            @if (filled($sede['telefono'] ?? null))
                <li>
                    <i class="fa fa-phone" aria-hidden="true"></i>
                    <span>{{ $sede['telefono'] }}</span>
                </li>
            @endif
            @if (filled($sede['email'] ?? null))
                <li>
                    <i class="fa fa-envelope-o" aria-hidden="true"></i>
                    <a href="mailto:{{ $sede['email'] }}">{{ $sede['email'] }}</a>
                </li>
            @endif
        </ul>
    </header>

    @if (! empty($sede['dependencias']))
        <div class="pe-contact-dept-grid pe-contact-sede-block__deps">
            @foreach ($sede['dependencias'] as $dependencia)
                @php
                    $phoneClean = trim((string) ($dependencia['telefono'] ?? ''));
                    $hasMultipleNumbers = str_contains($phoneClean, ',') || str_contains($phoneClean, '/') || str_contains($phoneClean, '-');
                    $digitsOnly = preg_replace('/\D/', '', $phoneClean);
                    $isCellPhone = strlen($digitsOnly) === 10 && str_starts_with($digitsOnly, '3');
                    $showWhatsApp = $phoneClean !== '' && ! $hasMultipleNumbers && $isCellPhone;
                    $whatsappMessage = 'Hola, me comunico con ' . $dependencia['nombre'] . ' de Paseo España en la sede ' . $sede['nombre'] . ' (' . $ciudad->nombre . ').';
                    $whatsappHref = $showWhatsApp ? 'https://api.whatsapp.com/send?phone=57' . $digitsOnly . '&text=' . urlencode($whatsappMessage) : null;

                    $deptLower = mb_strtolower($dependencia['nombre']);
                    $icon = match (true) {
                        str_contains($deptLower, 'comercial') || str_contains($deptLower, 'venta') => 'fa-briefcase',
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
                        <h4 class="pe-contact-dept-card__title">{{ $dependencia['nombre'] }}</h4>
                    </header>

                    <ul class="pe-contact-dept-card__meta">
                        @if (filled($dependencia['email'] ?? null))
                            <li>
                                <i class="fa fa-envelope-o" aria-hidden="true"></i>
                                <a href="mailto:{{ $dependencia['email'] }}">{{ $dependencia['email'] }}</a>
                            </li>
                        @endif
                        @if ($phoneClean !== '')
                            <li>
                                <i class="fa fa-phone" aria-hidden="true"></i>
                                <span>{{ $dependencia['telefono'] }}</span>
                            </li>
                        @endif
                        @if (filled($dependencia['contacto'] ?? null))
                            <li>
                                <i class="fa fa-user-o" aria-hidden="true"></i>
                                <span>{{ $dependencia['contacto'] }}</span>
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
            @endforeach
        </div>
    @endif
</section>
