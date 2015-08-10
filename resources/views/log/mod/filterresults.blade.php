@extends('template.main')

@section('title', 'Mod Log')
@section('css-title', 'modlog')

@section('content')
	<div id="content">
		<p class="timeframe bg-primary"><span>Showing results from </span>{{ $filters['from'] }}<span> to </span>{{ $filters['to'] }}</p>
		<table class="table table-bordered table-striped table-condensed table-hover">
			<thead>
				<tr>
					<th>Mod</th>
					@foreach ($actionsList as $action => $description)
					<th title="{{ $description }}"><span class="modactions {{ $action }}"></span></th>
					@endforeach
					<th>Total</th>
					<th>%</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($mods as $mod => $actions)
				<tr>
					<td>{{ $mod }}</td>
					@foreach ($actionsList as $action => $description)
					<td title="{{ $description }}">{{ isset($actions[$action]) ? number_format($actions[$action]) : 0 }}</td>
					@endforeach
					<td title="Total actions">{{ number_format($actions['total']) }}</td>
					<td title="Percent of total actions">{{ number_format($actions['percent'], 2) }}</td>
				</tr>
				@endforeach
				<tr>
					<td>Totals</td>
					@foreach ($totals as $total => $value)
					<td title="{{ $labels[$total] }}">{{ number_format($value) }}</td>
					@endforeach
				</tr>
			</tbody>
		</table>
	</div>
@endsection

