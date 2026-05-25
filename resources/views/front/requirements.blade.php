@extends('layouts.front')
@section('title', 'Requisitos - Paseo España Inmobiliaria')

@section('content')
<!-- Titlebar
================================================== -->
<div id="titlebar">
	<div class="container">
		<div class="row">
			<div class="col-md-12">

				<h2>Requisitos</h2>

				<!-- Breadcrumbs -->
				<nav id="breadcrumbs">
					<ul>
						<li><a href="{{ route('home') }}">Inicio</a></li>
						<li>Requisitos</li>
					</ul>
				</nav>

			</div>
		</div>
	</div>
</div>

<!-- Content
================================================== -->
<div class="container">

    <!-- Requisitos Persona Natural
	================================================== -->
	<div class="row">
		<div class="col-md-12">
            <!-- Headline -->
            <h4 class="headline with-border margin-bottom-25">Requisitos Persona Natural</h4>

            <h4><mark>Arrendatario</mark></h4>
                <ul class="list-4 color">
                    <li>Dos fotocopias de cedula (ampliada 150, con firma y huella)</li>
                    <li>EMPLEADOS: Certificado Laboral (Ingresos mínimo el doble del valor del canon), anexar tres desprendibles de pago</li>
                    <li>INDEPENDIENTES: Extractos Bancarios (Tres últimos meses) o soportes demostrables de ingresos</li>
                    <li>Adquirir y diligenciar completamente el formulario, firma, huella y realizar la consignación del estudio</li>
                </ul>

            <h4 class="margin-top-35"><mark>Deudor Solidario</mark></h4>
                <ul class="list-4 color">
                    <li>Dos fotocopias de cedula (ampliada 150, con firma y huella)</li>
                    <li>EMPLEADOS: Certificado Laboral (Ingresos mínimo el doble del valor del canon), anexar tres desprendibles de pago</li>
                    <li>INDEPENDIENTES: Extractos Bancarios (Tres últimos meses) o soportes demostrables de ingresos</li>
                    <li>Adquirir y diligenciar completamente el formulario, firma y huella</li>
                </ul>

            <h4 class="margin-top-35"><mark>Deudor Solidario - Finca Raíz</mark></h4>
                <ul class="list-4 color">
                    <li>Dos fotocopias de cedula (ampliada 150, con firma y huella)</li>
                    <li>EMPLEADOS: Certificado Laboral (Ingresos mínimo el doble del valor del canon), anexar tres desprendibles de pago</li>
                    <li>INDEPENDIENTES: Extractos Bancarios (Tres últimos meses) o soportes demostrables de ingresos</li>
                    <li>Folio de matrícula inmobiliaria de la finca raíz (No mayor a 30 días, libre de limitaciones)</li>
                    <li>Adquirir y diligenciar completamente el formulario, firma y huella</li>
                </ul>

            <p>IMPORTANTE: Ninguno puede estar reportado en Data Crédito, Cifín o cualquier otra entidad.</p>

            <a href="#" class="button medium"><i class="fa fa-download"></i>  Formulario El Libertador</a>
			<a href="#" class="button border"><i class="fa fa-download"></i>  Formulario Zurich</a>

            <h4 class="headline with-border margin-bottom-25 margin-top-35">Requisitos Persona Jurídica</h4>

            <h4><mark>Arrendatario</mark></h4>
                <ul class="list-4 color">
                    <li>Fotocopia documento de identidad representante legal</li>
                    <li>Estados financieros último año</li>
                    <li>Certificado de cámara y comercio</li>
                    <li>Declaración de renta de los últimos 2 años</li>
                    <li>Extractos bancarios últimos tres meses</li>
                    <li>Adquirir y diligenciar completamente el formulario, firma, huella y realizar la consignación del estudio</li>
                </ul>

            <a href="#" class="button medium"><i class="fa fa-download"></i>  Formulario El Libertador</a>
			<a href="#" class="button border"><i class="fa fa-download"></i>  Formulario Zurich</a>
		</div>
	</div>
</div>

@endsection
