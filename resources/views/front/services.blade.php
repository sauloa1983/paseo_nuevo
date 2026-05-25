@extends('layouts.front')
@section('title', 'Nuestros Servicios - Paseo España Inmobiliaria')

@section('content')
<!-- Titlebar
================================================== -->
<div id="titlebar">
	<div class="container">
		<div class="row">
			<div class="col-md-12">

				<h2>Nuestros Servicios</h2>

				<!-- Breadcrumbs -->
				<nav id="breadcrumbs">
					<ul>
						<li><a href="{{ route('home') }}">Inicio</a></li>
						<li>Nuestros Servicios</li>
					</ul>
				</nav>

			</div>
		</div>
	</div>
</div>

<!-- Container -->
<div class="container">

	<div class="row">
        <div class="col-md-12">
            <div class="style-2">

				<div class="toggle-wrap">
					<span class="trigger"><a href="#">Arriendos<i class="sl sl-icon-plus"></i></a></span>
					<div class="toggle-container">
						<p>Nuestro departamento de arriendos le ofrece importantes ventajas que le permitirán a nuestro clientes disfrutar de una completa seguridad y tranquilidad.</p>
                        <ul class="margin-top-25">
                            <li>Visitamos su inmueble y lo asesoramos con profesionalismo para fijar el cánon de arrendamiento de acuerdo a las condiciones del inmueble y del mercado.</li>
                            <li>Sus inmuebles son arrendados con la cobertura de un seguro de arrendamiento, seguro de administracion y poliza integral que cubre los pagos dejados de cancelar por el arrendatario hasta la fecha de ocupación.</li>
                            <li>Promocionamos el arriendo de su inmueble con diferentes estrategias publicitarias como: principales periodicos y revistas de la Ciudad, página Web y atención personalizada para arrendarlo rapidamente, ademas del registro fotografico de su inmueble.</li>
                            <li>Anticipamos el canon de arrendamiento de ser requerido por el propietario siempre y cuando esté el inmueble arrendado.</li>
                            <li>Cuidamos que a sus inmuebles se les hagan las reparaciones locativas necesarias.</li>
                            <li>Cotizamos con precios comodos para el mantenimiento de su inmueble previa su aprobación, ofreciendo además financiamiento del pago en caso de necesitarlo.</li>
                            <li>Cancelamos sus impuestos prediales, administracion y servicios públicos mientras el inmueble se halle desocupado previa autorizacion.</li>
                            <li>Consigne su inmueble sin costo alguno.</li>
                        </ul>
                    </div>
				</div>

				<div class="toggle-wrap">
					<span class="trigger"><a href="#">Ventas<i class="sl sl-icon-plus"></i></a></span>
					<div class="toggle-container">
						<p>Tenemos un equipo altamente calificado para el proceso de compraventa de finca raíz, con una atencion ágil, eficiente y perzonalizada. Lo asesoramos en el precio real de venta, para hacer rápida y efectiva la venta de su inmueble.</p>
                        <ul class="margin-top-25">
                            <li>La promocion de su inmueble se hará en los principales periodicos y revistas de la ciudad, en la pagina web y de manera personal en nuestras oficinas resaltando las fortalezas del inmueble para un cierre de negocio oportuno.</li>
                            <li>Nuestros asesores comerciales cuenta con disponibilidad para mostrar su inmueble a los clientes interesados las veces que sea necesario con responsabilidad y compromiso.</li>
                            <li>Nuestros asesores mantendrá contacto permanente con inversionistas nacionales y extranjeros, logrando para cada propiedad el mejor de los negocios.</li>
                            <li>Realizamos las diligencias necesarias para efectuar la venta, pago de impuestos, paz y salvos y demás.</li>
                            <li>Elaboramos la promesa de compraventa y acompañamos a nuestros clientes en todos los trámites, hasta la fima de la escritura pública dejando una grata satisfacion.</li>
                        </ul>
					</div>
				</div>

                <div class="toggle-wrap">
					<span class="trigger"><a href="#">Avaluos<i class="sl sl-icon-plus"></i></a></span>
					<div class="toggle-container">
						<h4>¿ Desea conocer el valor real de su Inmueble?</h4>
                        <p class="margin-top-15">Para Vender un inmueble, primeramente necesita saber su valor comercial, profesionales con amplia experiencia en avalúos urbanos y rurales, quiénes se mantienen actualizando sus conocimientos en el mercado inmobiliario lo asesoran y valoran el precio comercial de su inmueble.</p>
                        <p class="margin-top-15">Laventa puede demorarse por desconocimiento de los precios reales y en otras ocasiones su patrimonio se puede ver afectado.</p>
                        <p class="margin-top-15"><b>QUEREMOS AYUDARLE!</b> Trabajamos el avaluo de su propiedad con tecnica y profesionalismo.</p>
					</div>
				</div>

                <div class="toggle-wrap">
					<span class="trigger"><a href="#">Seguros<i class="sl sl-icon-plus"></i></a></span>
					<div class="toggle-container">
						<p>
                            <div class="row">
                                <div class="col-md-12">
                                <!-- Headline -->
                                <h5 class="headline with-border margin-top-0">SEGURO DE ARRENDAMIENTO</h5>
                                <p>Cubre el valor del canon de arrendamiento dejado de cancelar por el arrendatario hasta cuando permanezca ocupado.</p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                <!-- Headline -->
                                <h5 class="headline with-border margin-top-35">SEGURO DE ADMINISTRACIÓN</h5>
                                <p>Cubre el valor de la administracion del Edificio o Conjunto dejada de cancelar por el arrendatario hasta cuando permanezaca ocupado.</p>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                <!-- Headline -->
                                <h5 class="headline with-border margin-top-35">AMPARO INTEGRAL</h5>
                                <p>Cubre los servicios públicos dejados de pagar y los daños-faltantes causados por el arrendatario en el inmueble hasta el monto tomado. <mark>(RECOMENDADO)</mark></p>
                                </div>
                            </div>
                        </p>
					</div>
				</div>

                <div class="toggle-wrap">
					<span class="trigger"><a href="#">Asesorías Jurídicas<i class="sl sl-icon-plus"></i></a></span>
					<div class="toggle-container">
						<p>En PASEO ESPAÑA NMOBILIARIA ABOGADOS E.U. contará con el respaldo de un excelente Equipo Profesional de Asesores y Consultores Jurídicos especializados en derecho Inmobiliario, Comercial, Penal, entre otros, que le garantizan una correcta interpretación de las normas legales y le prestarán asesoría indispensable para la toma de decisiones.</p>
					</div>
				</div>

			</div>
        </div>
    </div>
</div>
@endsection
