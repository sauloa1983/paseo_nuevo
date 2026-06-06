@php
    $configKey = $configKey ?? 'tenant_guias';
    $headingId = $headingId ?? 'guias-heading';
    $sectionClass = $sectionClass ?? 'pe-guias';
    $gridClass = $gridClass ?? 'pe-services-offer__grid--two';
    $cardLayout = $cardLayout ?? 'default';
    $showSubtitle = $showSubtitle ?? false;
    $guias = config($configKey, []);
    $cards = $guias['cards'] ?? [];
    $alert = $guias['alert'] ?? [];
@endphp

@if (count($cards) > 0)
    <section class="{{ $sectionClass }}" aria-labelledby="{{ $headingId }}">
        <div class="container">
            <p id="{{ $headingId }}" class="titulo-negro-secciones">Instructivos y manuales</p>
            <span class="pe-why-choose__line" aria-hidden="true"></span>

            @if ($showSubtitle && ! empty($guias['subtitle']))
                <p class="pe-services-offer__title pe-guias__subtitle">{{ $guias['subtitle'] }}</p>
            @endif

            <div @class([
                'pe-services-offer__grid',
                'pe-guias__grid',
                $gridClass,
                'pe-guias__grid--single' => count($cards) === 1 && $cardLayout !== 'split',
            ])>
                @foreach ($cards as $card)
                    @php
                        $href = tenant_instructivo_url($card);
                    @endphp

                    @if ($cardLayout === 'split')
                        <article class="pe-card pe-guias-card pe-guias-card--split">
                            <div class="pe-guias-card__aside">
                                <span class="pe-guias-card__icon pe-guias-card__icon--lg" aria-hidden="true">
                                    <i class="fa fa-file-pdf-o"></i>
                                </span>
                                <h3 class="pe-card_red__title pe-guias-card__title">{{ $card['title'] }}</h3>
                                <p class="pe-card__subtitle pe-guias-card__description">{{ $card['description'] }}</p>
                            </div>

                            <div class="pe-guias-card__content">
                                @if (! empty($card['items']))
                                    <ul class="pe-card__list pe-guias-card__list">
                                        @foreach ($card['items'] as $item)
                                            <li>
                                                <span class="pe-card__check pe-guias-card__check" aria-hidden="true">
                                                    <i class="fa fa-check"></i>
                                                </span>
                                                {{ $item }}
                                            </li>
                                        @endforeach
                                    </ul>
                                @endif

                                @if ($href)
                                    <a href="{{ $href }}" class="banner-rojo__btn pe-guias-card__btn" download>
                                        <i class="fa fa-download" aria-hidden="true"></i>
                                        {{ $card['button'] ?? 'Descargar Instructivo Completo' }}
                                    </a>
                                @endif
                            </div>
                        </article>
                    @else
                        <article class="pe-card pe-guias-card">
                            <header class="pe-guias-card__head">
                                <span class="pe-guias-card__icon" aria-hidden="true">
                                    <i class="fa fa-file-pdf-o"></i>
                                </span>
                                <div class="pe-guias-card__intro">
                                    <h3 class="pe-card_red__title pe-guias-card__title">{{ $card['title'] }}</h3>
                                    <p class="pe-card__subtitle pe-guias-card__description">{{ $card['description'] }}</p>
                                </div>
                            </header>

                            @if (! empty($card['items']))
                                <ul class="pe-card__list pe-guias-card__list">
                                    @foreach ($card['items'] as $item)
                                        <li>
                                            <span class="pe-card__check pe-guias-card__check" aria-hidden="true">
                                                <i class="fa fa-check"></i>
                                            </span>
                                            {{ $item }}
                                        </li>
                                    @endforeach
                                </ul>
                            @endif

                            @if ($href)
                                <a href="{{ $href }}" class="banner-rojo__btn pe-guias-card__btn" download>
                                    <i class="fa fa-download" aria-hidden="true"></i>
                                    {{ $card['button'] ?? 'Descargar Instructivo Completo' }}
                                </a>
                            @endif
                        </article>
                    @endif
                @endforeach
            </div>

            @if (! empty($alert))
                <aside class="pe-guias-alert" aria-label="Información sobre los manuales">
                    <span class="pe-guias-alert__icon" aria-hidden="true">
                        <i class="fa fa-shield"></i>
                    </span>
                    <div class="pe-guias-alert__body">
                        <p class="pe-guias-alert__title">{{ $alert['title'] ?? '' }}</p>
                        @if (! empty($alert['text']))
                            <p class="pe-guias-alert__text">{{ $alert['text'] }}</p>
                        @endif
                    </div>
                </aside>
            @endif
        </div>
    </section>
@endif
