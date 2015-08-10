@extends('template.main')

@section('title', 'Webpanel Log')
@section('css-title', 'log')

@section('content')
	<div id="content">
		<table class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>#</th>
					<th>Page</th>
					<th>User</th>
					<th>Note</th>
					<th>Time</th>
				</tr>
			</thead>
			<tbody>
				@foreach (DB::table('log')->orderBy('id', 'desc')->get() as $log)
				<tr>
					<th>{{ $log->id }}</th>
					<td><a href="{{ URL::to(strtolower(str_replace(' ', '-', $log->page))) }}">{{ $log->page }}</a></td>
					<td>{{ $log->user }}</td>
					<td>{{ $log->note }}</td>
					<td>{{ $log->created_at }}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
@endsection