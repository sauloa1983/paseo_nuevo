@php
    use App\Models\Ciudad;

    $ciudadesWidget = Ciudad::query()
        ->orderBy('nombre')
        ->get()
        ->map(fn (Ciudad $ciudad) => [
            'id' => $ciudad->id,
            'nombre' => $ciudad->nombre,
            'arriendo' => whatsapp_url_for_area('arriendo', $ciudad),
            'venta' => whatsapp_url_for_area('venta', $ciudad),
        ])
        ->values();

    $legalWhatsappUrl = whatsapp_url_legal();
@endphp

<div
    class="pe-wa-widget"
    x-data="{
        open: false,
        step: 'menu',
        area: null,
        areaLabel: '',
        ciudades: @js($ciudadesWidget),
        legalUrl: @js($legalWhatsappUrl),
        toggle() {
            this.open = !this.open;
            if (!this.open) this.reset();
        },
        close() {
            this.open = false;
            this.reset();
        },
        reset() {
            this.step = 'menu';
            this.area = null;
            this.areaLabel = '';
        },
        selectArea(area, label) {
            if (area === 'legal') {
                if (this.legalUrl) window.open(this.legalUrl, '_blank', 'noopener,noreferrer');
                this.close();
                return;
            }
            this.area = area;
            this.areaLabel = label;
            this.step = 'cities';
        },
        back() {
            this.step = 'menu';
            this.area = null;
            this.areaLabel = '';
        },
        cityUrl(ciudad) {
            return this.area === 'arriendo' ? ciudad.arriendo : ciudad.venta;
        }
    }"
    x-cloak
    @keydown.escape.window="close()"
>
    <div
        id="pe-wa-widget-panel"
        class="pe-wa-widget__panel"
        x-show="open"
        x-transition:enter="pe-wa-widget__transition-enter"
        x-transition:enter-start="pe-wa-widget__transition-enter-start"
        x-transition:enter-end="pe-wa-widget__transition-enter-end"
        x-transition:leave="pe-wa-widget__transition-leave"
        x-transition:leave-start="pe-wa-widget__transition-leave-start"
        x-transition:leave-end="pe-wa-widget__transition-leave-end"
        @click.outside="close()"
        role="dialog"
        aria-modal="true"
        aria-labelledby="pe-wa-widget-title"
        :aria-hidden="(!open).toString()"
        style="display: none;"
    >
        <header class="pe-wa-widget__header">
            <div class="pe-wa-widget__brand">
                <span class="pe-wa-widget__avatar">
                    <img src="{{ asset('images/logo.png') }}" alt="Paseo España">
                </span>
                <span class="pe-wa-widget__brand-text">
                    <strong id="pe-wa-widget-title">Paseo España - Contacto Directo</strong>
                    <small>Soporte y Ventas 24/7</small>
                </span>
            </div>
            <button type="button" class="pe-wa-widget__close" @click="close()" aria-label="Cerrar">&times;</button>
        </header>

        <div class="pe-wa-widget__body">
            {{-- Paso 1: Área --}}
            <div x-show="step === 'menu'">
                <p class="pe-wa-widget__hint">¿En qué podemos ayudarte?</p>

                <button type="button" class="pe-wa-widget__action" @click="selectArea('arriendo', 'Arriendos')">
                    <span class="pe-wa-widget__action-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                            <path d="M3 21h18M6 21V8l6-5 6 5v13M10 21v-5h4v5"/>
                        </svg>
                    </span>
                    <span class="pe-wa-widget__action-label">Arriendos</span>
                </button>

                <button type="button" class="pe-wa-widget__action" @click="selectArea('venta', 'Ventas')">
                    <span class="pe-wa-widget__action-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                            <path d="M12 3v18M5 10l7-7 7 7"/>
                        </svg>
                    </span>
                    <span class="pe-wa-widget__action-label">Ventas</span>
                </button>

                <button type="button" class="pe-wa-widget__action" @click="selectArea('legal', 'Legal')" @if(!$legalWhatsappUrl) disabled @endif>
                    <span class="pe-wa-widget__action-icon" aria-hidden="true">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                            <path d="M14.5 5.5L18 9l-6 6-2-2-4 4"/>
                            <path d="M8 21h8M10 21v-4h4v4"/>
                            <path d="M5 9.5V6a2 2 0 012-2h2.5"/>
                        </svg>
                    </span>
                    <span class="pe-wa-widget__action-label">Legal</span>
                </button>
            </div>

            {{-- Paso 2: Ciudad --}}
            <div x-show="step === 'cities'" x-cloak>
                <button type="button" class="pe-wa-widget__back" @click="back()">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M15 18l-6-6 6-6"/></svg>
                    Volver
                </button>

                <p class="pe-wa-widget__hint">
                    <span x-text="areaLabel"></span> — elige tu ciudad
                </p>

                <template x-if="ciudades.length === 0">
                    <p class="pe-wa-widget__empty">No hay ciudades configuradas.</p>
                </template>

                <template x-for="ciudad in ciudades" :key="ciudad.id">
                    <a
                        :href="cityUrl(ciudad)"
                        class="pe-wa-widget__action pe-wa-widget__action--city"
                        target="_blank"
                        rel="noopener noreferrer"
                        x-show="cityUrl(ciudad)"
                        @click="close()"
                    >
                        <span class="pe-wa-widget__action-icon" aria-hidden="true">
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.75">
                                <path d="M12 21s-6-4.35-6-10a6 6 0 1112 0c0 5.65-6 10-6 10z"/>
                                <circle cx="12" cy="11" r="2.5"/>
                            </svg>
                        </span>
                        <span class="pe-wa-widget__action-label" x-text="ciudad.nombre"></span>
                    </a>
                </template>
            </div>
        </div>
    </div>

    <button
        type="button"
        class="float-btn whatsapp pe-wa-widget__fab"
        @click="toggle()"
        :aria-expanded="open.toString()"
        aria-controls="pe-wa-widget-panel"
        title="WhatsApp — Paseo España"
    >
        <span class="pe-wa-widget__fab-dot" aria-hidden="true"></span>
        <svg class="pe-wa-widget__fab-icon" width="32" height="32" viewBox="0 0 24 24" fill="#fff" aria-hidden="true">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
        </svg>
    </button>
</div>
