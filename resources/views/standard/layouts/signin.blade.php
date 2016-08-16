<!DOCTYPE html>
<html class="no-js css-menubar" lang="{{ $language }}">
<head>
	
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
	
	<meta name="description" content="">
	<meta name="author" content="">

	<title>{{ $appTitle }} - {{ $pageTitle }}</title>
	
	<link rel="apple-touch-icon" href="{{ elixir('images/apple-touch-icon.png') }}">
	<link rel="shortcut icon" href="{{ elixir('images/favicon.ico') }}">
	
	<!-- Stylesheets -->
	<link rel="stylesheet" type="text/css" href="{{ elixir('css/all.css') }}">

	@stack('css')

	<!-- Fonts -->
	<link rel="stylesheet" href="/fonts/web-icons/web-icons.min.css">
	<link rel='stylesheet' href='http://fonts.googleapis.com/css?family=Roboto:300,400,500,300italic'>
	
	<!--[if lt IE 9]>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
	<![endif]-->
	
	<!--[if lt IE 10]>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/enquire.js/2.1.2/enquire.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->
</head>
<body class="page-login-v3 layout-full">
	<!--[if lt IE 8]>
		<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
	<![endif]-->

	@yield('content')

	<?php /* ?><script src="{{ elixir('js/all.js') }}"></script><?php */ ?>

	@stack('script')
</body>
</html>