@extends('template.main')

@section('title', 'Mod Log')
@section('css-title', 'modlog')

@section('styles')
	<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
@endsection

@section('content')
	<div id="content">
		<div>
			<button id="modlog-filter-btn" class="btn btn-info">Filter</button> <button id="modlog-query-btn" class="btn btn-default">Query</button>
		</div>
		{!! Form::open(['id' => 'modlog-filter', 'class' => 'form-horizontal col-md-8', 'url' => 'log/mod/listing', 'method' => 'POST']) !!}
			<h3>Timeframe</h3>
			<div class="form-group">
				{!! Form::label('startdate', 'Start Date', ['class' => 'col-md-2 control-label']) !!}
				<div class="col-md-4">
					{!! Form::text('startdate', date('m/d/Y', time() - 60 * 60 * 24 * 7), array('class' => 'form-control')) !!}
				</div>
			</div>
			<div class="form-group">
				{!! Form::label('enddate', 'End Date', ['class' => 'col-md-2 control-label']) !!}
				<div class="col-md-4">
					{!! Form::text('enddate', date('m/d/Y'), ['class' => 'form-control']) !!}
				</div>
			</div>
			<h3>Filter Moderators</h3>
			<div class="form-group">
				<table class="table table-bordered filter-results col-md-12">
					<tr>
					@for ($i = 0; $i < count($mods); ++$i)
					@if ($i % 3 == 0)
					</tr>
					<tr data-num="{{ $i }}">
					@endif
					<td>{!! Form::checkbox('mods[]', $mods[$i], true) !!}{!! Form::label($mods[$i], $mods[$i])!!}</td>
					@endfor
					</tr>
				</table>
			</div>
			<h3>Filter Columns</h3>
			<div class="form-group">
				<table class="table table-bordered filter-results col-md-12">
					<tr>
					<?php $numActions2 = 0; ?>
					@foreach ($actions as $action => $description)
					@if ($numActions2 % 3 == 0)
					</tr>
					<tr>
					@endif
					<td>{!! Form::checkbox('actions[]', $action, array_key_exists($action, $defaultActions)) !!}{!! Form::label($action, $description) !!}</td>
						<?php $numActions2++; ?>
					@endforeach
					</tr>
				</table>
			</div>
			<div class="form-group">
				<div class="col-md-1">
					{!! Form::submit('Get Log', ['class' => 'btn btn-primary']) !!}
				</div>
			</div>
		{!! Form::close() !!}
		{!! Form::open(['id' => 'modlog-query', 'class' => 'form-horizontal col-md-8', 'url' => 'log/mod/search', 'method' => 'POST']) !!}
			<h3>Timeframe</h3>
			<div class="form-group">
				{!! Form::label('startdate', 'Start Date', ['class' => 'col-md-2 control-label']) !!}
				<div class="col-md-4">
					{!! Form::text('startdate', date('m/d/Y', time() - 60 * 60 * 24 * 7), array('class' => 'form-control')) !!}
				</div>
			</div>
			<div class="form-group">
				{!! Form::label('enddate', 'End Date', ['class' => 'col-md-2 control-label']) !!}
				<div class="col-md-4">
					{!! Form::text('enddate', date('m/d/Y'), ['class' => 'form-control']) !!}
				</div>
			</div>
			<h3>Mod Action Details</h3>
			<div class="form-group">
				{!! Form::label('author', 'Author', ['class' => 'col-md-2 control-label', 'title' => 'Author of the target item, if applicable']) !!}
				<div class="col-md-4">
					{!! Form::text('author', '', ['class' => 'form-control']) !!}
				</div>
			</div>
			<div class="form-group">
				{!! Form::label('detail', 'Action Note', ['class' => 'col-md-2 control-label', 'title' => 'Description of mod action, if applicable']) !!}
				<div class="col-md-4">
					{!! Form::text('detail', '', ['class' => 'form-control']) !!}
				</div>
			</div>
			<div class="form-group">
				{!! Form::label('order', 'Order By', ['class' => 'col-md-2 control-label']) !!}
				<div class="col-md-4">
					{!! Form::select('order', ['-1' => 'Newest To Oldest', '1' => 'Oldest To Newest'], '', ['class' => 'form-control']) !!}
				</div>
			</div>
			<h3>Filter Moderators</h3>
			<div class="form-group">
				<table class="table table-bordered filter-results col-md-12">
					<tr>
					@for ($i = 0; $i < count($mods); ++$i)
					@if ($i % 3 == 0)
					</tr>
					<tr>
					@endif
					<td>{!! Form::checkbox('mods[]', $mods[$i], true) !!}{!! Form::label($mods[$i], $mods[$i])!!}</td>
					@endfor
					</tr>
				</table>
			</div>
			<h3>Filter Actions</h3>
			<div class="form-group">
				<table class="table table-bordered filter-results col-md-12">
					<tr>
					<?php $numActions2 = 0; ?>
					@foreach ($actions as $action => $description)
					@if ($numActions2 % 3 == 0)
					</tr>
					<tr>
					@endif
					<td>{!! Form::checkbox('actions[]', $action, array_key_exists($action, $defaultActions)) !!}{!! Form::label($action, $description) !!}</td>
						<?php $numActions2++; ?>
					@endforeach
					</tr>
				</table>
			</div>
			<div class="form-group">
				<div class="col-md-1">
					{!! Form::submit('Get Results', ['class' => 'btn btn-primary']) !!}
				</div>
			</div>
		{!! Form::close() !!}
	</div>
@endsection

@section('scripts')
	<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
	<script type="text/javascript">
		$("#startdate").datepicker();
		$("#enddate").datepicker();
		$("#modlog-query-btn").click(function() {
			$("#modlog-filter").hide();
			$("#modlog-filter-btn").removeClass('btn-info');
			$("#modlog-filter-btn").addClass('btn-default');
			$("#modlog-query").show();
			$("#modlog-query-btn").removeClass('btn-default');
			$("#modlog-query-btn").addClass('btn-info');
		});
		$("#modlog-filter-btn").click(function() {
			$("#modlog-query").hide();
			$("#modlog-query-btn").removeClass('btn-info');
			$("#modlog-query-btn").addClass('btn-default');
			$("#modlog-filter").show();
			$("#modlog-filter-btn").removeClass('btn-default');
			$("#modlog-filter-btn").addClass('btn-info');
		});
	</script>
@endsection