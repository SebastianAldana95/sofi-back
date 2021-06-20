<!DOCTYPE html>

<html lang="en">

	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width-device-width, initial-scale-1.0">
		<meta http-equiv="X-UA-Compatible" content="ie-edge">
		<title>{{  __('messages.activate_account_mail_title') }}</title>
		<style type="text/css">
			p {
				color: red;
			}

		</style>

	</head>

	<body>

		<h3>{{ __('messages.activate_account_header_title') }}</h3>
		<p>contenido del correo de bienvenida</p>
		<br/>
		<hr/>
		<br/>
		<label><a href="{{ $url }}">{{ __('messages.activate_account_button') }}</a></label>

	</body>

</html>
