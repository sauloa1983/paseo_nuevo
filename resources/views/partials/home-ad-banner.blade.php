@if ($activeBanner?->imageUrl())
    <div
        id="pe-home-ad-banner"
        class="pe-home-ad-banner mfp-hide"
        role="dialog"
        aria-modal="true"
        aria-label="{{ $activeBanner->title ?? 'Banner publicitario' }}"
    >
        <div class="pe-home-ad-banner__dialog">
            @if (filled($activeBanner->link_url))
                <a
                    href="{{ $activeBanner->link_url }}"
                    target="_blank"
                    rel="noopener noreferrer"
                    class="pe-home-ad-banner__image-link"
                >
                    <img
                        src="{{ $activeBanner->imageUrl() }}"
                        alt="{{ $activeBanner->title ?? 'Banner publicitario' }}"
                        class="pe-home-ad-banner__image"
                        decoding="async"
                    >
                </a>
                <div class="pe-home-ad-banner__actions">
                    <a
                        href="{{ $activeBanner->link_url }}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="button pe-home-ad-banner__cta"
                    >
                        Ver promoción
                    </a>
                </div>
            @else
                <img
                    src="{{ $activeBanner->imageUrl() }}"
                    alt="{{ $activeBanner->title ?? 'Banner publicitario' }}"
                    class="pe-home-ad-banner__image"
                    decoding="async"
                >
            @endif
        </div>
    </div>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        var $ = window.jQuery;

        if (! $ || ! $.fn.magnificPopup) {
            return;
        }

        var popupEl = document.getElementById('pe-home-ad-banner');

        if (! popupEl) {
            return;
        }

        window.setTimeout(function () {
            try {
                if ($.magnificPopup.instance) {
                    $.magnificPopup.close();
                }

                $.magnificPopup.open({
                    items: { src: '#pe-home-ad-banner' },
                    type: 'inline',
                    fixedContentPos: true,
                    fixedBgPos: true,
                    overflowY: 'auto',
                    closeBtnInside: false,
                    closeMarkup: '<button title="Cerrar (Esc)" type="button" class="mfp-close pe-mfp-close pe-home-ad-banner__close" aria-label="Cerrar"><i class="fa fa-times" aria-hidden="true"></i></button>',
                    preloader: false,
                    midClick: true,
                    removalDelay: 300,
                    mainClass: 'mfp-fade pe-home-ad-banner-mfp',
                });
            } catch (e) {
                console.warn('No se pudo abrir el banner publicitario.', e);
            }
        }, 600);
    });
    </script>
    @endpush
@endif
