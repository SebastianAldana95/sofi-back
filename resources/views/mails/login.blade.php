<!DOCTYPE html>

<html lang="en">

<head>

	<meta charset="utf-8">

	<meta name="viewport" content="width-device-width, initial-scale-1.0">

	<meta http-equiv="X-UA-Compatible" content="ie-edge">

	<title>Inicio de sesion</title>

</head>

<body>

	<h1>Inicio de sesión</h1>

	<p>Hemos observado que alguien ha ingresado a tu cuenta, si fuiste tu omite el mensaje de lo contrario <a href="{{ env('SECURITY_CLOSE') . $idUser }}" target="_blank">PULSA AQUI</a> para prevenir el acceso</p>

</body>

</html>
