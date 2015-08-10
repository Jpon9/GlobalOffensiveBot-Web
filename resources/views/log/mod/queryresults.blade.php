@extends('template.main')

@section('title', 'Mod Log')
@section('css-title', 'modlog-query')

@section('content')
	<div id="content">
		<p class="timeframe bg-primary"><span>Showing </span>{{ $filters['start'] }} - {{ $filters['end'] < $filters['total'] ? $filters['end'] : $filters['total'] }}<span> of </span>{{ $filters['total'] }}<span> results from </span>{{ $filters['from'] }}<span> to </span>{{ $filters['to'] }}<span> of items created by </span>{{ $filters['author'] }}<span> matching a detail filter of </span>{{ $filters['detail'] }}</p>
		<p>{!! $results->render() !!}</p>
		<table class="table table-bordered table-striped table-condensed table-hover">
			<thead>
				<tr>
					<th>Action</th>
					<th>Mod</th>
					<th>Author</th>
					<th>Type</th>
					<th>Details</th>
					<th>Time</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($results as $entry)
				<tr>
					<td title="{{ $labels[$entry['action']] }}"><span class="modactions {{ $entry['action'] }}"></span></td>
					<td>{{ $entry['mod'] }}</td>
					<td>{{ $entry['target_author'] }}</td>
					<td><a href="{{ 'https://reddit.com' . $entry['target_permalink'] }}">{{ substr($entry['target_fullname'], 0, 2) == 't1' ? 'Comment' : 'Thread' }}</a></td>
					<td>{{ $entry['details'] }}</td>
					<td>{{ strftime("%d %B %Y at %H:%M:%S", $entry['created_at']) }}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
@endsection