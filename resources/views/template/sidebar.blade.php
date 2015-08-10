<div id="sidebar">
	<div>
		<h3>Status</h3>
		<p>The bot is <span id="bot-status" class="status-indeterminate">unknown...</span></p>
		<p>Updates in <span id="bot-updates-in">unknown...</span></p>
	</div>
	<div>
		<h3>Configurations</h3>
		<p><a href="{!! URL::to('basic-config') !!}"{!! Request::is('basic-config') ? ' class="active"' : '' !!}>Basic Config</a></p>
		<p><a href="{!! URL::to('sidebar') !!}"{!! Request::is('sidebar') ? ' class="active"' : '' !!}>Sidebar</a></p>
		<p><a href="{!! URL::to('stylesheet') !!}"{!! Request::is('stylesheet') ? ' class="active"' : '' !!}>Stylesheet</a></p>
		<p><a href="{!! URL::to('demonyms') !!}"{!! Request::is('demonyms') ? ' class="active"' : '' !!}>Demonyms</a></p>
		<p><a href="{!! URL::to('notices') !!}"{!! Request::is('notices') ? ' class="active"' : '' !!}>Notices</a></p>
	</div>
	<div>
		<h3>Quicklinks</h3>
		<p><a href="{!! URL::to('log/bot') !!}"{!! Request::is('log/bot') ? ' class="active"' : '' !!}>Bot Log</a></p>
		<p><a href="{!! URL::to('log/webpanel') !!}"{!! Request::is('log/webpanel') ? ' class="active"' : '' !!}>Webpanel Log</a></p>
		<p><a href="{!! URL::to('log/mod') !!}"{!! Request::is('log/mod') ? ' class="active"' : '' !!}>Mod Log</a></p>
		<p><a href="{!! URL::to('log/modmail') !!}"{!! Request::is('log/modmail') ? ' class="active"' : '' !!}>Modmail</a></p>
	</div>
	@if (Auth::user()->permissions == '%')
	<div>
		<h3>Admin</h3>
		<p><a href="{!! URL::to('admin/users') !!}">User Management</a></p>
		<p><a href="{!! URL::to('admin/register') !!}">User Creation</a></p>
	</div>
	@endif
</div>