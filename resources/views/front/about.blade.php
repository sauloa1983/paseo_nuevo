@extends('layouts.front')
@section('title', 'Nosotros - Paseo España Inmobiliaria')

@section('content')

@include('partials.banner-hero', [
    'headingId' => 'about-hero-heading',
    'label' => 'Somos Paseo España',
    'heading' => 'Más de <span class="pe-services-accent">30 años</span> construyendo <span class="pe-services-accent">confianza</span> en el mercado inmobiliario de Santander',
    'text' => 'Trabajamos cada día para <strong>proteger</strong> y potenciar el patrimonio de nuestros clientes, administrando sus inmuebles con responsabilidad, transparencia y respaldo legal.',
    'image' => 'images/nosotros.jpg',
    'imageAlt' => 'Asesor inmobiliario con llaves y maqueta de vivienda',
    'breadcrumbLabel' => '',
    'showActions' => false,
])


<div class="container">
	<div class="row pe-mv-row">
		<div class="col-sm-6 col-xs-12">
			<article class="pe-mv-card">
				<div class="pe-mv-card__icon" aria-hidden="true">
					<i class="fa fa-bullseye"></i>
				</div>
				<div class="pe-mv-card__body">
					<h4 class="pe-mv-card__title">Nuestra Misión</h4>
					<p>Trabajamos para proteger y potenciar el patrimonio inmobiliario de nuestros clientes, administrando sus inmuebles con responsabilidad, transparencia y respaldo legal, generando relaciones de confianza y resultados seguros en arrendamiento y venta.</p>
				</div>
			</article>
		</div>
		<div class="col-sm-6 col-xs-12">
			<article class="pe-mv-card">
				<div class="pe-mv-card__icon" aria-hidden="true">
					<i class="fa fa-eye"></i>
				</div>
				<div class="pe-mv-card__body">
					<h4 class="pe-mv-card__title">Nuestra Visión</h4>
					<p>Consolidarnos como la empresa inmobiliaria referente por su transparencia, compromiso humano y excelencia en el servicio, creando relaciones de confianza que contribuyan al bienestar de las familias y al crecimiento de nuestros clientes.</p>
				</div>
			</article>
		</div>
	</div>
</div>

<section class="pe-about-history" aria-labelledby="about-history-heading">
    <div class="container">
        <div class="pe-about-history__grid">
            <div class="pe-about-history__content">
                <span class="pe-about-history__label">Nuestra historia</span>
                <h2 id="about-history-heading" class="pe-about-history__title">Conoce nuestros 30 años de historia</h2>
                <p class="pe-about-history__text">
                    Tres décadas construyendo confianza, acompañando familias y cuidando lo que más valoras.
                </p>
                <!--<a
                    href="https://www.youtube.com/watch?v=OfEhFdjfMp4"
                    class="pe-about-history__btn"
                    target="_blank"
                    rel="noopener noreferrer"
                >
                    <i class="fa fa-play-circle" aria-hidden="true"></i>
                    Ver video en YouTube
                </a>-->
            </div>
            <div id="about-video" class="pe-about-history__video">
                <iframe
                    src="https://www.youtube-nocookie.com/embed/OfEhFdjfMp4?rel=0&amp;modestbranding=1&amp;playsinline=1"
                    title="Video corporativo - Paseo España Inmobiliaria"
                    loading="lazy"
                    frameborder="0"
                    referrerpolicy="strict-origin-when-cross-origin"
                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                    allowfullscreen></iframe>
            </div>
        </div>
    </div>
</section>

<div class="container">
    <section class="pe-why-choose" aria-labelledby="why-choose-heading">
        <h2 id="why-choose-heading" class="titulo-negro-secciones">¿Por qué elegir Paseo España?</h2>
        <span class="pe-why-choose__line" aria-hidden="true"></span>

        <ul class="pe-why-choose__grid">
            <li class="pe-why-choose__item">
                <span class="pe-why-choose__icon-wrap" aria-hidden="true">
                    <i class="fa fa-shield"></i>
                </span>
                <p class="sub-texto-iconos">Más de 30 años<br>de experiencia</p>
            </li>
            <li class="pe-why-choose__item">
                <span class="pe-why-choose__icon-wrap" aria-hidden="true">
                    <i class="fa fa-building"></i>
                </span>
                <p class="sub-texto-iconos">Administración<br>de inmuebles</p>
            </li>
            <li class="pe-why-choose__item">
                <span class="pe-why-choose__icon-wrap" aria-hidden="true">
                    <i class="fa fa-balance-scale"></i>
                </span>
                <p class="sub-texto-iconos">Asesoría jurídica<br>especializada</p>
            </li>
            <li class="pe-why-choose__item">
                <span class="pe-why-choose__icon-wrap" aria-hidden="true">
                    <i class="fa fa-map-o"></i>
                </span>
                <p class="sub-texto-iconos">Cobertura<br>en Santander</p>
            </li>
            <li class="pe-why-choose__item">
                <span class="pe-why-choose__icon-wrap" aria-hidden="true">
                    <svg class="pe-why-choose__svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <path d="M11 12h2a2 2 0 1 0 0-4h-3c-.6 0-1.1.2-1.4.6L3 14"></path>
                        <path d="m7 18 1.6-1.4c.3-.4.8-.6 1.4-.6h4c1.1 0 2.1-.4 2.8-1.2l4.6-4.4a2 2 0 0 0-2.75-2.91l-4.2 3.9"></path>
                        <path d="m2 14 2 2"></path>
                        <path d="m20 14-2 2"></path>
                    </svg>
                </span>
                <p class="sub-texto-iconos">Procesos<br>transparentes</p>
            </li>
            <li class="pe-why-choose__item">
                <span class="pe-why-choose__icon-wrap" aria-hidden="true">
                    <i class="fa fa-user"></i>
                </span>
                <p class="sub-texto-iconos">Acompañamiento<br>personalizado</p>
            </li>
        </ul>
    </section>

    <section
        class="pe-about-policies"
        aria-label="Políticas institucionales"
        x-data="{ openPanel: null }"
    >
        <article class="pe-policy-card" :class="{ 'is-open': openPanel === 'quality' }">
            <button
                type="button"
                class="pe-policy-card__trigger"
                @click="openPanel = openPanel === 'quality' ? null : 'quality'"
                :aria-expanded="openPanel === 'quality'"
                aria-controls="policy-quality"
            >
                <span class="pe-policy-card__icon" aria-hidden="true"><i class="fa fa-building"></i></span>
                <span class="pe-policy-card__head">
                    <span class="titulo-negro-acordeon">Política de Calidad</span>
                    <span class="pe-policy-card__summary">Conoce nuestro compromiso con la excelencia en el servicio y la mejora continua.</span>
                </span>
                <span class="pe-policy-card__chevron" aria-hidden="true"><i class="fa fa-angle-down"></i></span>
            </button>
            <div
                id="policy-quality"
                class="pe-policy-card__panel"
                x-show="openPanel === 'quality'"
                x-cloak
            >
                <div class="pe-policy-card__body text-justify">
                    <p>PASEO ESPAÑA INMOBILIARIA ABOGADOS E.U., como empresa al servicio del sector inmobiliario, ha propendido por la prestación de un servicio integral que satisfaga la totalidad de la demanda en el mercado dentro y fuera del país, contando para ello con un equipo humano profesional, competente e idóneo y con una capacidad financiera suficiente para atender las necesidades y demandas de los propietarios, actualizando e implementando sistemas operativos funcionales que nos permitan brindar soluciones ágiles y efectivas que garanticen una permanencia indefinida y privilegiada en el mercado inmobiliario.</p>
                </div>
            </div>
        </article>

        <article class="pe-policy-card" :class="{ 'is-open': openPanel === 'data' }">
            <button
                type="button"
                class="pe-policy-card__trigger"
                @click="openPanel = openPanel === 'data' ? null : 'data'"
                :aria-expanded="openPanel === 'data'"
                aria-controls="policy-data"
            >
                <span class="pe-policy-card__icon" aria-hidden="true"><i class="fa fa-lock"></i></span>
                <span class="pe-policy-card__head">
                    <span class="titulo-negro-acordeon">Política de Protección de Datos</span>
                    <span class="pe-policy-card__summary">Conoce cómo protegemos tu información personal y garantizamos tu privacidad.</span>
                </span>
                <span class="pe-policy-card__chevron" aria-hidden="true"><i class="fa fa-angle-down"></i></span>
            </button>
            <div
                id="policy-data"
                class="pe-policy-card__panel"
                x-show="openPanel === 'data'"
                x-cloak
            >
                <div class="pe-policy-card__body text-justify">
                    <p><b>CONSIGNANTE</b><br>
                    TRATAMIENTO DE DATOS PERSONALES Y AUTORIZACIÓN. En cumplimiento de lo dispuesto en la Ley 1581 de 2012, le informamos que los datos de carácter personal que Usted suministre en virtud del presente contrato de arrendamiento, serán objeto de tratamiento por parte del ADMINISTRADOR, con la finalidad de desarrollar el objeto del presente contrato, durante todas las etapas del mismo y especialmente para: a) El desarrollo de la relación contractual entre el CONSIGNANTE y el ADMINISTRADOR. b) La actualización y consulta de datos personales. c) El reporte y la consulta de obligaciones ante las centrales de riesgos. d) La realización de ofertas de asesoría y servicios. e) La realización de campañas comerciales y de mercado sobre servicios afines al arrendamiento. f) La medición de niveles de satisfacción. g) La realización de investigaciones de mercadeo. h) La confirmación de referencias personales y comerciales de conformidad con la información por Usted suministrada. i) El envío de mensajes en torno al contrato de administración por medio físico o electrónico tales como: emails, SMS, whatsapp, celular o a cualquier otro medio. j) Para efectos de citación y notificación las partes convienen que estas se realizarán a las direcciones registradas en el contrato. PARÁGRAFO No.1. Es responsabilidad del CONSIGNANTES, actualizar la información relacionada con su base de datos periódicamente. PARAGRAFO No.2. PASEO ESPAÑA INMOBILLIARIA ABOGADOS E.U., se compromete a implementar todas las medidas necesarias para garantizar un tratamiento idóneo de los datos personales y a cumplir con las disposiciones vigentes en la materia. PARAGRAFO No.3. Los derechos que le asisten como Titular son los establecidos en la Ley 1581 de 2012 (Ley General de Protección de Datos) y la Ley 1266 de 2008 (Ley Especial para el hábeas data financiero y crediticio), los cuales se resumen en actualización, rectificación, cancelación y oposición de información de conformidad con lo señalado en la normatividad vigente en la materia que le sea aplicable. PARÁGRAFO No.4. En consecuencia, al suscribir este contrato el CONSIGNANTE confirma su plena voluntad de AUTORIZAR EXPRESAMENTE al ADMINISTRADOR para realizar el Tratamiento de los datos personales que han suministrado a éste, bien directamente o bien a través de alguna empresa evaluadora de riesgos. PARÁGRAFO No.5. Igualmente se aceptan que conocen sus derechos en materia de protección de datos los cuales podrán ejercitar mediante correo físico dirigido a la Carrera 26 No.34-53 Bucaramanga, Calle 29 # 29-33 P.2 L-7 Floridablanca, Calle 9 # 10-96 Piedecuesta o Calle 39 No. 22-07 Girón.</p>
                    <br>
                    <p><b>ARRENDATARIOS-DEUDORES SOLIDARIOS</b><br>
                    TRATAMIENTO DE DATOS PERSONALES Y AUTORIZACIÓN. En cumplimiento de lo dispuesto en la Ley 1581 de 2012, le informamos que los datos de carácter personal que Usted suministre en virtud del presente contrato de arrendamiento, serán objeto de tratamiento por parte de la Arrendadora, con la finalidad de desarrollar el contrato durante todas las etapas del mismo y especialmente para: a) El desarrollo de la relación contractual entre el arrendador y el arrendatario. b) La actualización y consulta de datos personales. c) El reporte y la consulta de obligaciones ante las centrales de riesgos. d) La realización de ofertas de asesoría y servicios. e) La realización de campañas comerciales y de mercado sobre servicios afines al arrendamiento. f) La medición de niveles de satisfacción. g) La realización de investigaciones de mercadeo. h) La confirmación de referencias personales y comerciales de conformidad con la información por Usted suministrada. i) El envío de mensajes en torno al contrato de arrendamiento por medio físico o electrónico tales como: emails, SMS, WhatsApp, celular o a cualquier otro medio. j) Para efectos de citación y notificación las partes convienen que estas se realizarán a las direcciones registradas en el contrato. PARÁGRAFO No.1. Es responsabilidad de ARRENDATARIO y DEUDORES SOLIDATARIOS, actualizar la información relacionada con su base de datos periódicamente. PARAGRAFO No.2. PASEO ESPAÑA INMOBILLIARIA ABOGADOS E.U., se compromete a implementar todas las medidas necesarias para garantizar un tratamiento idóneo de los datos personales y a cumplir con las disposiciones vigentes en la materia. PARAGRAFO No.3. Los derechos que le asisten como Titular son los establecidos en la Ley 1581 de 2012 (Ley General de Protección de Datos) y la Ley 1266 de 2008 (Ley Especial para el hábeas data financiero y crediticio), los cuales se resumen en actualización, rectificación, cancelación y oposición de información de conformidad con lo señalado en la normatividad vigente en la materia que le sea aplicable. PARÁGRAFO No.4. En consecuencia, al suscribir este contrato el ARRENDATARIO y los DEUDORES SOLIDARIOS confirman su plena voluntad de AUTORIZAR EXPRESAMENTE al ARRENDADOR para realizar el Tratamiento de los datos personales que han suministrado a éste, bien directamente o bien a través de alguna empresa evaluadora de riesgos. PARAGRAFO No.5. Igualmente aceptan que conocen sus derechos en materia de protección de datos los cuales podrán ejercitar mediante correo físico dirigido a la Carrera 26 No.34-53 y a través del correo electrónico del dpto. que corresponda. VIGESIMA CUARTA: El arrendatario y deudores solidarios declaran haber recibido cada uno copia del presente contrato con sus respectivas firmas originales en papel común y estar de acuerdo con su contenido.</p>
                </div>
            </div>
        </article>

        <article class="pe-policy-card" :class="{ 'is-open': openPanel === 'terms' }">
            <button
                type="button"
                class="pe-policy-card__trigger"
                @click="openPanel = openPanel === 'terms' ? null : 'terms'"
                :aria-expanded="openPanel === 'terms'"
                aria-controls="policy-terms"
            >
                <span class="pe-policy-card__icon" aria-hidden="true"><i class="fa fa-file-text-o"></i></span>
                <span class="pe-policy-card__head">
                    <span class="titulo-negro-acordeon">Términos y Condiciones</span>
                    <span class="pe-policy-card__summary">Consulta los términos y condiciones que rigen nuestros servicios.</span>
                </span>
                <span class="pe-policy-card__chevron" aria-hidden="true"><i class="fa fa-angle-down"></i></span>
            </button>
            <div
                id="policy-terms"
                class="pe-policy-card__panel"
                x-show="openPanel === 'terms'"
                x-cloak
            >
                <div class="pe-policy-card__body text-justify">
                    <p>El acceso y uso de los servicios de Paseo España Inmobiliaria implica la aceptación de estas condiciones. La información publicada sobre inmuebles tiene carácter referencial y puede actualizarse sin previo aviso. Los contratos de arrendamiento, administración o compraventa se formalizan mediante documentos propios de la empresa y la normatividad colombiana vigente.</p>
                    <p>El usuario se compromete a suministrar información veraz y a utilizar los canales oficiales de la inmobiliaria para consultas, visitas y trámites. Paseo España Inmobiliaria no se hace responsable por el uso indebido de la información suministrada por terceros ajenos a sus plataformas autorizadas.</p>
                </div>
            </div>
        </article>
    </section>

</div>

<section
    class="banner-rojo"
    style="--banner-rojo-bg: url('{{ asset('images/inmueble_2.jpg') }}');"
    aria-labelledby="about-cta-heading"
>
    <div class="container">
        <div class="banner-rojo__inner">
            <div class="banner-rojo__copy">
                <h2 id="about-cta-heading" class="banner-rojo__title">¿Buscas una inmobiliaria de confianza?</h2>
                <p class="banner-rojo__subtitle">Estamos listos para ayudarte a encontrar el inmueble ideal.</p>
            </div>
            <div class="banner-rojo__actions">
                <a href="{{ route('inmuebles.search') }}" class="banner-rojo__btn">
                    <i class="fa fa-home" aria-hidden="true"></i>
                    Ver inmuebles disponibles
                </a>
            </div>
        </div>
    </div>
</section>
@endsection
