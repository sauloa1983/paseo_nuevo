<section class="pe-testimonials" data-background-color="#ffffff">
    <div class="container">
        @if (session('success'))
            <div class="pe-toast" role="status" aria-live="polite" data-pe-toast>
                <div class="pe-toast__inner">
                    <span class="pe-toast__icon" aria-hidden="true">
                        <i class="fa fa-check"></i>
                    </span>
                    <div class="pe-toast__content">
                        <div class="pe-toast__title">Enviado</div>
                        <div class="pe-toast__text">{{ session('success') }}</div>
                    </div>
                    <button type="button" class="pe-toast__close" aria-label="Cerrar" data-pe-toast-close>
                        <i class="fa fa-times" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
        @endif

        <p class="titulo-negro-secciones">Opiniones reales de nuestros clientes</p>
        <span class="pe-why-choose__line" aria-hidden="true"></span>

        @if (isset($testimonials) && $testimonials->count())
            <div class="pe-services-offer__grid pe-testimonials__grid">
                @foreach ($testimonials->take(6) as $testimonial)
                    @php
                        $rating = (int) ($testimonial->calificacion ?? 5);
                        $rating = max(1, min(5, $rating));
                    @endphp
                    <article class="pe-card pe-testimonials-card">
                        <div class="pe-testimonials-card__top">
                            <div class="pe-testimonials-card__stars" aria-label="{{ $rating }} de 5">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="fa {{ $i <= $rating ? 'fa-star' : 'fa-star-o' }}" aria-hidden="true"></i>
                                @endfor
                            </div>
                            <i class="fa fa-quote-right pe-testimonials-card__quote" aria-hidden="true"></i>
                        </div>

                        <p class="pe-testimonials-card__message">
                            {{ $testimonial->mensaje }}
                        </p>

                        <div class="pe-testimonials-card__author">
                            <img
                                class="pe-testimonials-card__avatar"
                                src="{{ $testimonial->photo ? asset('storage/' . $testimonial->photo) : asset('images/happy-client-01.jpg') }}"
                                alt="Foto de {{ $testimonial->nombre }}"
                                loading="lazy"
                                decoding="async"
                            >
                            <div class="pe-testimonials-card__author-meta">
                                <div class="pe-testimonials-card__name">{{ $testimonial->nombre }}</div>
                                <div class="pe-testimonials-card__desc">{{ $testimonial->descripcion }}</div>
                            </div>
                        </div>
                    </article>
                @endforeach
            </div>
        @endif

        <div class="pe-testimonials-form">
            <div class="pe-testimonials-form__left">
                <h3 class="pe-testimonials-form__title">Déjanos tu experiencia</h3>
                <p class="pe-testimonials-form__subtitle">
                    Tu opinión nos ayuda a mejorar y a seguir brindando un servicio cercano y confiable.
                </p>

                <form action="{{ route('testimonials.store') }}" method="POST" class="pe-testimonials-form__form">
                    @csrf

                    <div class="pe-testimonials-form__row">
                        <div class="pe-testimonials-form__field">
                            <label class="pe-testimonials-form__label">Nombre completo</label>
                            <input
                                type="text"
                                name="nombre"
                                value="{{ old('nombre') }}"
                                class="pe-testimonials-form__input"
                            >
                            @error('nombre') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>

                        <div class="pe-testimonials-form__field">
                            <label class="pe-testimonials-form__label">Tipo de servicio</label>
                            <select name="descripcion" class="pe-testimonials-form__input">
                                <option value="" @selected(old('descripcion') === '')>Selecciona una opción</option>
                                <option value="Arriendo" @selected(old('descripcion') === 'Arriendo')>Arriendo</option>
                                <option value="Venta" @selected(old('descripcion') === 'Venta')>Venta</option>
                                <option value="Administración" @selected(old('descripcion') === 'Administración')>Administración</option>
                                <option value="Avalúo" @selected(old('descripcion') === 'Avalúo')>Avalúo</option>
                                <option value="Otro" @selected(old('descripcion') === 'Otro')>Otro</option>
                            </select>
                            @error('descripcion') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    </div>

                    <div class="pe-testimonials-form__rating">
                        <span class="pe-testimonials-form__label-inline">Calificación</span>
                        <div class="pe-rating" role="radiogroup" aria-label="Calificación">
                            @php $selectedRating = (int) old('calificacion', 5); @endphp
                            @for ($i = 5; $i >= 1; $i--)
                                <input
                                    class="pe-rating__input"
                                    type="radio"
                                    id="rating-{{ $i }}"
                                    name="calificacion"
                                    value="{{ $i }}"
                                    @checked($selectedRating === $i)
                                >
                                <label class="pe-rating__star" for="rating-{{ $i }}" aria-label="{{ $i }} estrellas">
                                    <i class="fa fa-star" aria-hidden="true"></i>
                                </label>
                            @endfor
                        </div>
                    </div>
                    @error('calificacion') <small class="text-danger">{{ $message }}</small> @enderror

                    <div class="pe-testimonials-form__field">
                        <label class="pe-testimonials-form__label">Cuéntanos tu experiencia…</label>
                        <textarea name="mensaje" rows="4" class="pe-testimonials-form__input">{{ old('mensaje') }}</textarea>
                        @error('mensaje') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <button type="submit" class="pe-testimonials-form__submit">
                        Enviar opinión
                        <i class="fa fa-paper-plane" aria-hidden="true"></i>
                    </button>
                </form>
            </div>

            <div class="pe-testimonials-form__right">
                <div class="pe-testimonials-form__right-inner">
                    <div class="pe-testimonials-form__icon" aria-hidden="true">
                        <i class="fa fa-heart"></i>
                    </div>
                    <h3 class="pe-testimonials-form__right-title">Tu opinión nos inspira</h3>
                    <p class="pe-testimonials-form__right-text">
                        Cada comentario nos permite mejorar nuestros procesos y acompañarte mejor en tu experiencia inmobiliaria.
                    </p>
                    <p class="pe-testimonials-form__right-note">
                        Gracias por confiar en Paseo España <i class="fa fa-heart-o" aria-hidden="true"></i>
                    </p>
                </div>
            </div>
        </div>
    </div>

    @if (session('success'))
        @push('scripts')
            <script>
                (function () {
                    var toast = document.querySelector('[data-pe-toast]');
                    if (!toast) return;

                    var closeBtn = toast.querySelector('[data-pe-toast-close]');
                    var hide = function () {
                        toast.classList.add('pe-toast--hide');
                        window.setTimeout(function () {
                            if (toast && toast.parentNode) toast.parentNode.removeChild(toast);
                        }, 250);
                    };

                    if (closeBtn) closeBtn.addEventListener('click', hide);
                    window.setTimeout(hide, 4200);
                })();
            </script>
        @endpush
    @endif
</section>
