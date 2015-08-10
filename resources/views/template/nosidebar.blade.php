<html>
	<head>
		<title>@yield('title') - GlobalOffensiveBot</title>
		<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans" type="text/css">
		<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto+Slab" type="text/css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
		{!! HTML::style('css/app.css') !!}
		@yield('styles')
	</head>
	<body class="@yield('css-title') no-sidebar">
		@include('template.header')
		@yield('content')
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
		@yield('scripts')
	</body>
</html>