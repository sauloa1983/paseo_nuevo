@props([
    'headingId',
    'title' => null,
    'accent' => null,
    'label' => null,
    'heading' => null,
    'text',
    'image' => 'images/home-parallax.jpg',
    'imageAlt' => null,
    'video' => null,
    'videoTitle' => 'Video',
    'videoId' => null,
    'breadcrumbLabel' => '',
    'showActions' => false,
])

<section class="pe-services-hero fullwidth-layout{{ ! empty($label) ? ' pe-services-hero--about' : '' }}{{ ! empty($video) ? ' pe-services-hero--video' : '' }}" aria-labelledby="{{ $headingId }}">
    <div class="pe-services-hero__layout">
        <div class="pe-services-hero__content">
            <div class="pe-services-hero__content-inner">
                @if (! empty($label))
                    <span class="pe-services-hero__label">{{ $label }}</span>
                    <h1 id="{{ $headingId }}" class="pe-services-hero__title">{!! $heading !!}</h1>
                @else
                    <h1 id="{{ $headingId }}" class="pe-services-hero__title">
                        {{ $title }}@if ($accent) <span class="pe-services-accent">{{ $accent }}</span>@endif
                    </h1>
                @endif
                <p class="pe-services-hero__text">{!! $text !!}</p>

                {{-- Bloque condicionado --}}
                @if ($showActions ?? false)
                <div class="pe-services-hero__actions">
                    <!--@if (whatsapp_url_legal())
                        <a href="{{ whatsapp_url_legal() }}" target="_blank" rel="noopener noreferrer" class="pe-services-btn pe-services-btn--primary">
                            <i class="fa fa-whatsapp" aria-hidden="true"></i>
                            Hablar con un asesor
                        </a>
                    @endif-->
                    <a href="{{ route('contact') }}" class="pe-services-btn pe-services-btn--outline">
                        <i class="fa fa-phone" aria-hidden="true"></i>
                        Centro de Ayuda
                    </a>
                </div>
                @endif
            </div>
        </div>

        <div class="pe-services-hero__media">
            @if (! empty($video))
                <div @if ($videoId) id="{{ $videoId }}" @endif class="pe-services-hero__video">
                    <div class="pe-services-hero__video-inner">
                        <iframe
                            src="{{ $video }}"
                            title="{{ $videoTitle }}"
                            loading="lazy"
                            frameborder="0"
                            referrerpolicy="strict-origin-when-cross-origin"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                            allowfullscreen></iframe>
                    </div>
                </div>
            @else
                <div
                    class="pe-services-hero__image"
                    style="background-image: url('{{ asset($image) }}');"
                    role="img"
                    aria-label="{{ $imageAlt ?? $breadcrumbLabel }}"
                ></div>
            @endif
        </div>
    </div>
</section>
