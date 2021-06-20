<!DOCTYPE html>

<html lang="en">

<head>

	<meta charset="utf-8">

	<meta name="viewport" content="width-device-width, initial-scale-1.0">

	<meta http-equiv="X-UA-Compatible" content="ie-edge">

	<title>Código de confirmación</title>



	<style type="text/css">

		h2 {

	        display: block;

	        text-align: center;

	        font-size: 5em !important;

	    }



	    p {

	        display: block;

	        text-align: justify;

	        font-size: 3em !important;

	        padding: 5vh 7vh;

	    }



	    label {

	        display: block;

	        text-align: center;

	        font-size: 4.5em !important;

	        padding: 6vh 7vh;

	    }



	    .btn-acept{

	        display: block;

	        text-align: center;

	    }



	    .button {

	        background-color: red;

	        padding: 2vh 2vh;

	        border-radius: 10px !important;

	        border: 2px solid black;

	    }

	</style>

</head>

<body>

	<h3>Código de confirmación</h3>



	<p>Ingrese el siguiente código para reestablecer la contraseña.</p>



	<label>{{ $confirmationCode }}</label>



	<p>Este código tiene una vigencia de 10 minutos. Pasado este tiempo deberá solicitar un nuevo código para restablecer su contraseña.</p>



</body>

</html>
