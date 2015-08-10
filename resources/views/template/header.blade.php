<div id="header">
	<h3><span>@yield('title') &mdash; </span><a href="{{ URL::to('/') }}">/r/GlobalOffensive Bot Webpanel</a></h3>
	@if (Auth::check())
		<span class="userbar">{{ Auth::user()->username }} &mdash; <a href="{{ URL::to('logout') }}">Logout</a></span>
	@endif
</div>