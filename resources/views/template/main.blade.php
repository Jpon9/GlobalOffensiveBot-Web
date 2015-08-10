<html>
	<head>
		<title>@yield('title') - GlobalOffensiveBot</title>
		<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans" type="text/css">
		<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto+Slab" type="text/css">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css">
		{!! HTML::style('css/app.css') !!}
		@yield('styles')
	</head>
	<body class="@yield('css-title')">
		@include('template.header')
		@include('template.sidebar')
		@yield('content')
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
		<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
		{!! HTML::script('js/counter.js') !!}
		<script type="text/javascript">
			var botStatus = "";

			// Get bot status
			function updateBotStatus() {
				var status = "indeterminate";
				$.ajaxSetup({async:false});
				$.getJSON("{!! URL::to('/bot/status') !!}", function(data) {
					status = data.status;
				});
				$.ajaxSetup({async:true});
				var target = document.getElementById("bot-status");
				target.innerHTML = status;
				target.className = "status-" + status;
				botStatus = status;
			}
			updateBotStatus();

			// Get bot metadata
			function updateMetadata() {
				$.getJSON("{!! URL::to('/bot/metadata') !!}", function(metadata) {
					var target = document.getElementById("bot-updates-in");

					updateBotStatus();

					console.log(metadata.last_webpanel_restart > metadata.last_update_completed);
					
					if (botStatus === "offline") {
						killTimers();
						target.innerHTML = "...";
					} else if (metadata.last_webpanel_restart > metadata.last_update_completed) {
						killTimers();
						target.innerHTML = "restarting...";
					} else if (botStatus === "online") {
						timer(target, metadata.last_update_completed);
					} else {
						killTimers();
						target.innerHTML = "...";
					}
				});
			}
			updateMetadata();

			setInterval(function() { updateMetadata(); }, 10000);
		</script>
		@yield('scripts')
	</body>
</html>