@extends('layouts.front')
@section('title', 'FAQ - Paseo España Inmobiliaria')

@section('content')
<!-- Titlebar
================================================== -->
<div id="titlebar">
	<div class="container">
		<div class="row">
			<div class="col-md-12">

				<h2>Preguntas Frecuentes</h2>

				<!-- Breadcrumbs -->
				<nav id="breadcrumbs">
					<ul>
						<li><a href="{{ route('home') }}">Inicio</a></li>
						<li>Preguntas Frecuentes</li>
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
					<span class="trigger"><a href="#">¿Cuál y cuanto es el incremento del canon para la vivienda?<i class="sl sl-icon-plus"></i></a></span>
					<div class="toggle-container">
						<p>El incremento corresponde al IPC. Para el año 2026 es del 13.12%.</p>
                    </div>
				</div>

				<div class="toggle-wrap">
					<span class="trigger"><a href="#">Pregunta 2<i class="sl sl-icon-plus"></i></a></span>
					<div class="toggle-container">
						<p>xxxxxxx</p>
					</div>
				</div>

                <div class="toggle-wrap">
					<span class="trigger"><a href="#">Pregunta 3<i class="sl sl-icon-plus"></i></a></span>
					<div class="toggle-container">
						<p>xxxxxxx</p>
					</div>
				</div>

                <div class="toggle-wrap">
					<span class="trigger"><a href="#">Pregunta 4<i class="sl sl-icon-plus"></i></a></span>
					<div class="toggle-container">
						<p>xxxxxxx</p>
					</div>
				</div>

                <div class="toggle-wrap">
					<span class="trigger"><a href="#">Pregunta 5<i class="sl sl-icon-plus"></i></a></span>
					<div class="toggle-container">
						<p>xxxxxxx</p>
					</div>
				</div>

			</div>
        </div>
    </div>
</div>
@endsection
