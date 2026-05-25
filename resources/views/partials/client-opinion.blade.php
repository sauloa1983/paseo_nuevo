<section class="margin-bottom-0 padding-bottom-80" data-background-color="#ffffff">

	<div class="container">
		<div class="row">
            <div class="col-md-12">
                <div class="testimonials-subtitle">
                    Opiniones sinceras de clientes que ya eligieron sus inmuebles con nosotros.!
                </div>
            </div>
            @if(isset($testimonials) && $testimonials->count())
                @foreach ($testimonials->chunk(3) as $chunk)
                    <div class="row">
                        @foreach ($chunk as $testimonial)
                            <div class="col-md-4">
                                <div class="testimonial-box">
                                    <div class="testimonial">
                                        {{ $testimonial->mensaje }}
                                    </div>
                                    <div class="testimonial-author">
                                        <img src="{{ $testimonial->photo ? asset('storage/' . $testimonial->photo) : asset('images/happy-client-01.jpg') }}" alt="">
                                        <h4>
                                            {{ $testimonial->nombre }}
                                            <span>{{ $testimonial->descripcion }}</span>
                                        </h4>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endforeach
            @endif
        </div>

        <div class="clearfix"></div>
		<div class="margin-top-85"></div>

        <!-- Add Comment -->
        <h4 class="headline">Déjanos tu comentario</h4>
        <div class="submit-section">

        <!-- Add Comment Form -->
        <form id="add-comment" class="add-comment" action="{{ route('testimonials.store') }}" method="POST">
            @csrf

            <fieldset>
                <div>
                    <label>Nombre:</label>
                    <input type="text" name="nombre" value="{{ old('nombre') }}">
                    @error('nombre') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div>
                    <label>Descripción: <span>*</span></label>
                    <input type="text" name="descripcion" value="{{ old('descripcion') }}" placeholder="Apartamento en arriendo">
                    @error('descripcion') <small class="text-danger">{{ $message }}</small> @enderror
                </div>

                <div>
                    <label>Comentario: <span>*</span></label>
                    <textarea cols="20" rows="2" name="mensaje">{{ old('mensaje') }}</textarea>
                    @error('mensaje') <small class="text-danger">{{ $message }}</small> @enderror
                </div>
            </fieldset>

            <button type="submit" class="button">Agregar Comentario</button>
            <div class="clearfix"></div>
            <div class="margin-bottom-20"></div>
        </form>
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        </div>
	</div>



</section>
