@php
    $headingId = $headingId ?? 'doc-downloads-heading';
    $title = $title ?? 'Instructivos y manuales';
    $items = $items ?? [];
    $layout = $layout ?? 'two-col';
    $sectionClass = $sectionClass ?? 'pe-doc-downloads';
@endphp

@if (count($items) > 0)
    <section class="{{ $sectionClass }}" aria-labelledby="{{ $headingId }}">
        <div class="container">
            <p id="{{ $headingId }}" class="titulo-negro-secciones">{{ $title }}</p>
            <span class="pe-why-choose__line" aria-hidden="true"></span>

            <div @class([
                'pe-doc-downloads__grid',
                'pe-doc-downloads__grid--two' => $layout === 'two-col',
                'pe-doc-downloads__grid--single' => $layout === 'single',
            ])>
                @foreach ($items as $item)
                    @php
                        $href = tenant_instructivo_url($item);
                        $itemTitle = $item['title'] ?? 'Documento';
                        $itemDescription = $item['description'] ?? 'Descargue el documento en formato PDF.';
                    @endphp

                    @if ($href)
                        <a
                            href="{{ $href }}"
                            class="pe-doc-download-card"
                            @if (! empty($item['url'])) target="_blank" rel="noopener noreferrer" @else download @endif
                        >
                            <span class="pe-doc-download-card__icon" aria-hidden="true">
                                <i class="fa fa-file-pdf-o"></i>
                            </span>
                            <span class="pe-doc-download-card__body">
                                <span class="pe-doc-download-card__title">{{ $itemTitle }}</span>
                                <span class="pe-doc-download-card__text">{{ $itemDescription }}</span>
                                <span class="pe-doc-download-card__action">
                                    <i class="fa fa-download" aria-hidden="true"></i>
                                    Descargar PDF
                                </span>
                            </span>
                        </a>
                    @endif
                @endforeach
            </div>
        </div>
    </section>
@endif
