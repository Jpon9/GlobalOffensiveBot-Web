@extends('template.main')

@section('title', 'Modmail')
@section('css-title', 'modmail')

@section('styles')
	<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
@endsection

@section('content')
	<div id="content">
		{!! Form::open(['id' => 'modmail-search', 'class' => 'form-horizontal col-md-8', 'url' => 'log/modmail/listing', 'method' => 'POST']) !!}
			<h3>Timeframe</h3>
			<div class="form-group">
				{!! Form::label('startdate', 'Start Date', ['class' => 'col-md-2 control-label']) !!}
				<div class="col-md-4">
					{!! Form::text('startdate', date('m/d/Y', $oldestModmailTime), array('class' => 'form-control')) !!}
				</div>
			</div>
			<div class="form-group">
				{!! Form::label('enddate', 'End Date', ['class' => 'col-md-2 control-label']) !!}
				<div class="col-md-4">
					{!! Form::text('enddate', date('m/d/Y'), ['class' => 'form-control']) !!}
				</div>
			</div>
			<div class="form-group">
				{!! Form::label('author', 'Sender', ['class' => 'col-md-2 control-label', 'title' => 'Sender of the modmail message, if applicable']) !!}
				<div class="col-md-4">
					{!! Form::text('author', '', ['class' => 'form-control']) !!}
				</div>
			</div>
			<div class="form-group">
				{!! Form::label('subject', 'Subject', ['class' => 'col-md-2 control-label', 'title' => 'Subject of the modmail']) !!}
				<div class="col-md-4">
					{!! Form::text('subject', '', ['class' => 'form-control']) !!}
				</div>
			</div>
			<div class="form-group">
				{!! Form::label('body', 'Body', ['class' => 'col-md-2 control-label', 'title' => 'Body of the modmail']) !!}
				<div class="col-md-4">
					{!! Form::text('body', '', ['class' => 'form-control']) !!}
				</div>
			</div>
			<div class="form-group">
				{!! Form::label('order', 'Order By', ['class' => 'col-md-2 control-label']) !!}
				<div class="col-md-4">
					{!! Form::select('order', ['-1' => 'Newest To Oldest', '1' => 'Oldest To Newest'], '', ['class' => 'form-control']) !!}
				</div>
			</div>
			<div class="form-group">
				<div class="col-md-1">
					{!! Form::submit('Get Modmail', ['class' => 'btn btn-primary']) !!}
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
	</script>
@endsection