@props(['sede', 'ciudad'])

<section class="pe-contact-sede-block">
    <header class="pe-contact-sede-block__head pe-contact-sede-block__head--solo">
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
</section>
