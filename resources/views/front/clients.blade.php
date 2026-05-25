@extends('layouts.front')
@section('title', 'Nuestros Clientes - Paseo España Inmobiliaria')

@section('content')
<!-- Titlebar
================================================== -->
<div id="titlebar">
	<div class="container">
		<div class="row">
			<div class="col-md-12">

				<h2>Nuestros Clientes</h2>

				<!-- Breadcrumbs -->
				<nav id="breadcrumbs">
					<ul>
						<li><a href="{{ route('home') }}">Inicio</a></li>
						<li>Nuestros Clientes</li>
					</ul>
				</nav>

			</div>
		</div>
	</div>
</div>

<!-- Container -->

@include('partials.client-opinion', ['testimonials' => $testimonials])

@endsection
