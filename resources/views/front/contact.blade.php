@extends('layouts.front')
@section('title', 'Contacto - Paseo España Inmobiliaria')

@section('content')
<!-- Titlebar
================================================== -->
<div id="titlebar">
	<div class="container">
		<div class="row">
			<div class="col-md-12">

				<h2>Contacto</h2>

				<!-- Breadcrumbs -->
				<nav id="breadcrumbs">
					<ul>
						<li><a href="{{ route('home') }}">Inicio</a></li>
						<li>Contacto</li>
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
			<h4 class="headline margin-bottom-30">Contacto oficinas</h4>

			<div class="style-1">

				<!-- Tabs Navigation -->
				<ul class="tabs-nav">
					<li class="active"><a href="#tab1">Bucaramanga</a></li>
					<li><a href="#tab2">Floridablanca</a></li>
					<li><a href="#tab3">Piedecuesta</a></li>
                    <li><a href="#tab4">Girón</a></li>
				</ul>

				<!-- Tabs Content -->
				<div class="tabs-container">
					<div class="tab-content" id="tab1">
                        <table class="basic-table">
                            <tr>
                                <th>Dependencia</th>
                                <th>E-mail</th>
                                <th>Teléfonos</th>
                                <th>Encargado</th>
                            </tr>
                            <tr>
                                <td>Comercial (arriendos-ventas)</td>
                                <td><a href="maito:comercial@paseoespana.com ">comercial@paseoespana.com </a></td>
                                <td>3108180746 - 3102586500</td>
                                <td>GLADYS CARVAJAL</td>
                            </tr>
                            <tr>
                                <td>Contratos Vigentes</td>
                                <td><a href="maito:arriendos@paseoespana.com ">arriendos@paseoespana.com </a></td>
                                <td>3108081973 </td>
                                <td>CLAUDIA CAMARGO</td>
                            </tr>
                            <tr>
                                <td>Reparaciones</td>
                                <td><a href="maito:arreglos@paseoespana.com ">arreglos@paseoespana.com </a></td>
                                <td>3125811186 </td>
                                <td>YESENIA ESPARZA</td>
                            </tr>
                            <tr>
                                <td>Caja-Cartera </td>
                                <td><a href="maito:cartera@paseoespana.com ">cartera@paseoespana.com </a></td>
                                <td>3208128962</td>
                                <td>MILDRED PRADA</td>
                            </tr>
                            <tr>
                                <td>Administraciones P.H</td>
                                <td><a href="maito:admon@paseoespana.com ">admon@paseoespana.com </a></td>
                                <td>3204226287</td>
                                <td>GLORIA LUNA</td>
                            </tr>

                            <tr>
                                <td>Gerencia</td>
                                <td><a href="maito:gerencia@paseoespana.com ">gerencia@paseoespana.com </a></td>
                                <td>3108081970</td>
                                <td>ZAYRA GONZALEZ</td>
                            </tr>
                        </table>
                    </div>

					<div class="tab-content" id="tab2">
                        <table class="basic-table">
                            <tr>
                                <th>Dependencia</th>
                                <th>E-mail</th>
                                <th>Teléfonos</th>
                                <th>Encargado</th>
                            </tr>
                            <tr>
                                <td>Comercial (arriendos-ventas) </td>
                                <td><a href="maito:comercialflorida@paseoespana.com ">comercialflorida@paseoespana.com</a></td>
                                <td>3138050296 </td>
                                <td>VALENTINA RUEDA</td>
                            </tr>
                            <tr>
                                <td>Contratos Vigentes</td>
                                <td><a href="maito:arriendosflorida@paseoespana.com ">arriendosflorida@paseoespana.com </a></td>
                                <td>3102881951</td>
                                <td>ANDREA RODRIGUEZ</td>
                            </tr>
                            <tr>
                                <td>Reparaciones</td>
                                <td><a href="maito:arreglosflorida@paseoespana.com ">arreglosflorida@paseoespana.com </a></td>
                                <td>3134526697</td>
                                <td>EDNA TAVERA</td>
                            </tr>
                            <tr>
                                <td>Caja-Cartera</td>
                                <td><a href="maito:carteraflorida@paseoespana.com ">carteraflorida@paseoespana.com</a></td>
                                <td>3214628445</td>
                                <td>MARCELA GUARÍN</td>
                            </tr>
                            <tr>
                                <td>Administraciones P.H</td>
                                <td><a href="maito:admonflorida@paseoespana.com ">admonflorida@paseoespana.com</a></td>
                                <td>3183441402</td>
                                <td>DEICE TARAZONA</td>
                            </tr>

                            <tr>
                                <td>Gerencia</td>
                                <td><a href="maito:gerenciaflorida@paseoespana.com ">gerenciaflorida@paseoespana.com</a></td>
                                <td>3102517792</td>
                                <td>WILSON HERNANDEZ</td>
                            </tr>
                        </table>
                    </div>

					<div class="tab-content" id="tab3">
                        <table class="basic-table">
                            <tr>
                                <th>Dependencia</th>
                                <th>E-mail</th>
                                <th>Teléfonos</th>
                                <th>Encargado</th>
                            </tr>
                            <tr>
                                <td>Comercial (arriendos-ventas)</td>
                                <td><a href="maito:comercialp.ta@paseoespana.com ">comercialp.ta@paseoespana.com</a></td>
                                <td>3213439492 </td>
                                <td>VIVIAN SALAZAR</td>
                            </tr>
                            <tr>
                                <td>Contratos Vigentes</td>
                                <td><a href="maito:arriendosp.ta@paseoespana.com ">arriendosp.ta@paseoespana.com </a></td>
                                <td>3112239605</td>
                                <td>ELIANA DIAZ ESTEVEZ</td>
                            </tr>
                            <tr>
                                    <td>Reparaciones</td>
                                    <td><a href="maito:arreglosp.ta@paseoespana.com ">arreglosp.ta@paseoespana.com </a></td>
                                    <td>3213838416</td>
                                    <td>DAJHANIRA URREGO</td>
                            </tr>
                            <tr>
                                <td>Caja-Cartera</td>
                                <td><a href="maito:carterap.ta@paseoespana.com ">carterap.ta@paseoespana.com</a></td>
                                <td>3117596068</td>
                                <td>VIVIANA SANDOVAL</td>
                            </tr>
                            <tr>
                                <td>Administraciones P.H</td>
                                <td><a href="maito:admonp.ta@paseoespana.com ">admonp.ta@paseoespana.com</a></td>
                                <td>3176468450     </td>
                                <td>SILVIA MONTAÑEZ</td>
                            </tr>

                            <tr>
                                <td>Gerencia</td>
                                <td><a href="maito:gerenciap.ta@paseoespana.com ">gerenciap.ta@paseoespana.com</a></td>
                                <td>3102513198 </td>
                                <td>DIANIS ACUÑA</td>
                            </tr>
                        </table>
                    </div>

                    <div class="tab-content" id="tab4">
                        <table class="basic-table">
                            <tr>
                                <th>Dependencia</th>
                                <th>E-mail</th>
                                <th>Teléfonos</th>
                                <th>Encargado</th>
                            </tr>
                            <tr>
                                <td>Comercial</td>
                                <td><a href="maito:comercialgiron@paseoespana.com ">comercialgiron@paseoespana.com</a></td>
                                <td>3118976591</td>
                                <td>SILVIA CHACON</td>
                            </tr>
                            <tr>
                                <td>Arriendos (Contratos Vigentes)</td>
                                <td><a href="maito:arriendosgiron@paseoespana.com ">arriendosgiron@paseoespana.com</a></td>
                                <td>607 6071047</td>
                                <td>LICETH RODRIGUEZ</td>
                            </tr>
                            <tr>
                                <td>Reparaciones</td>
                                <td><a href="maito:arreglosgiron@paseoespana.com ">arreglosgiron@paseoespana.com</a></td>
                                <td></td>
                                <td></td>
                            </tr>

                            <tr>
                                <td>Caja-Cartera</td>
                                <td><a href="maito:carteragiron@paseoespana.com ">carteragiron@paseoespana.com</a></td>
                                <td>3106739929</td>
                                <td>LEIDY JAIMES</td>
                            </tr>
                            <tr>
                                <td>Administraciones P.H</td>
                                <td><a href="maito:admongiron@paseoespana.com ">admongiron@paseoespana.com</a></td>
                                <td></td>
                                <td></td>
                            </tr>

                            <tr>
                                <td>Gerencia</td>
                                <td><a href="maito:gerenciagiron@paseoespana.com ">gerenciagiron@paseoespana.com</a></td>
                                <td>3106867441 </td>
                                <td>ERIKA HERRERA</td>
                            </tr>
                        </table>
                    </div>
				</div>

			</div>

		</div>

	</div>

    <div class="row margin-top-70">

		<!-- Contact Details -->
		<div class="col-md-4">

			<h4 class="headline margin-bottom-30">Encuentranos Aquí</h4>

			<!-- Contact Details -->
			<div class="sidebar-textbox">
				<ul class="contact-details">
					<li><i class="im im-icon-Phone-2"></i> <strong>Fijo:</strong> <span>(607) 697 8295 </span></li>
					<li><i class="im im-icon-Phone-SMS"></i> <strong>Celulares:</strong> <span> 310 818 0746 | 310 258 6500 </span></li>
					<li><i class="im im-icon-Clock-2"></i> <strong>Horario:</strong> <span>
                        Lunes - Viernes <br>08:00 a 12:00 / 1:00 a 5:00 <br>
                        Sábado <br> 08:00 a 12:00
                    </span></li>
					<li><i class="im im-icon-Envelope"></i> <strong>E-Mail:</strong> <span><a href="#">comercial@paseoespana.com</a></span></li>
				</ul>
			</div>

		</div>

		<!-- Contact Form -->
		<div class="col-md-8">

			<section id="contact">
				<h4 class="headline margin-bottom-35">Escríbenos</h4>

                    <meta name="csrf-token" content="{{ csrf_token() }}">
                    <div id="form-message" style="display:none; margin-bottom:15px;"></div>

                    <form id="contact-form" method="POST" action="{{ route('contact.send') }}">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" name="name" placeholder="Nombre" required>
                            </div>
                            <div class="col-md-6">
                                <input type="email" name="email" placeholder="Email" required>
                            </div>
                        </div>
                        <div>
                            <input type="text" name="subject" placeholder="Asunto" required>
                        </div>
                        <div>
                            <textarea name="comments" placeholder="Mensaje" required></textarea>
                        </div>

                        <button type="submit" id="submitBtn" class="button fullwidth margin-top-5">
                            Enviar Mensaje
                        </button>
                    </form>
			</section>
		</div>
		<!-- Contact Form / End -->

	</div>


</div>
@endsection

<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('contact-form');
    const messageBox = document.getElementById('form-message');

    if (!form || !messageBox) {
        console.error('Falta form o form-message');
        return;
    }

    const submitBtn = form.querySelector('button[type="submit"]');

    if (!submitBtn) {
        console.error('No se encontró el botón submit dentro del formulario');
        return;
    }

    form.addEventListener('submit', async function (e) {
        e.preventDefault();

        messageBox.style.display = 'none';
        messageBox.innerHTML = '';

        submitBtn.disabled = true;
        submitBtn.innerText = 'Enviando...';

        const formData = new FormData(form);

        try {
            const response = await fetch(form.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (response.ok) {
                messageBox.style.display = 'block';
                messageBox.style.color = 'green';
                messageBox.innerHTML = data.message;
                form.reset();

                setTimeout(() => {
                    messageBox.style.display = 'none';
                    messageBox.innerHTML = '';
                }, 4000);
            } else {
                let errors = data.message || 'Ocurrió un error al enviar.';

                if (data.errors) {
                    errors = Object.values(data.errors).flat().join('<br>');
                }

                messageBox.style.display = 'block';
                messageBox.style.color = 'white';
                messageBox.style.background = 'red';
                messageBox.style.padding = '10px';
                messageBox.innerHTML = errors;
            }
        } catch (error) {
            messageBox.style.display = 'block';
            messageBox.style.color = 'white';
            messageBox.style.background = 'red';
            messageBox.style.padding = '10px';
            messageBox.innerHTML = 'Error de conexión.';
        } finally {
            submitBtn.disabled = false;
            submitBtn.innerText = 'Enviar Mensaje';
        }
    });
});
</script>
