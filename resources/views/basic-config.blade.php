@extends('template.main')

@section('title', 'Basic Config')

@section('content')
	<div id="content">
		<h3>Basic Config</h3>
		@if (count($errors) > 0)
		    <div class="alert alert-danger">
		        <ul>
		            @foreach ($errors->all() as $error)
		                <li>{{ $error }}</li>
		            @endforeach
		        </ul>
		    </div>
		@elseif (isset($success_message))
			<div class="alert alert-success">
				<p>{{ $success_message }}</p>
			</div>
		@endif
		{!! Form::open(array('class' => 'form-horizontal')) !!}
			<div class="form-group">
				{!! Form::label('subreddit', 'Subreddit', array('class' => 'col-sm-3 control-label')) !!}
				<div class="col-sm-2">
					{!! Form::text('subreddit', $subreddit, array('class' => 'form-control')) !!}
				</div>
			</div>
			<div class="form-group">
				{!! Form::label('update_interval_mins', 'Update Timeout (minutes)', array('class' => 'col-sm-3 control-label')) !!}
				<div class="col-sm-1">
					{!! Form::text('update_interval_mins', $update_interval_mins, array('class' => 'form-control')) !!}
				</div>
			</div>
			<div class="form-group">
				{!! Form::label('max_streams_shown', 'Maximum Streams', array('class' => 'col-sm-3 control-label')) !!}
				<div class="col-sm-1">
					{!! Form::text('max_streams_shown', $max_streams_shown, array('class' => 'form-control')) !!}
				</div>
			</div>
			<div class="form-group">
				{!! Form::label('max_games_shown', 'Maximum Games', array('class' => 'col-sm-3 control-label')) !!}
				<div class="col-sm-1">
					{!! Form::text('max_games_shown', $max_games_shown, array('class' => 'form-control')) !!}
				</div>
			</div>
			<div class="form-group">
				{!! Form::label('stream_thumbnail_css_name', 'Stream Thumbnail CSS Name', array('class' => 'col-sm-3 control-label')) !!}
				<div class="col-sm-2">
					{!! Form::text('stream_thumbnail_css_name', $stream_thumbnail_css_name, array('class' => 'form-control')) !!}
				</div>
			</div>
			<div class="form-group">
				{!! Form::label('spotlight_rotation_timeout', 'Spotlight Rotation (minutes)', array('class' => 'col-sm-3 control-label')) !!}
				<div class="col-sm-1">
					{!! Form::text('spotlight_rotation_timeout', $spotlight_rotation_timeout, array('class' => 'form-control')) !!}
				</div>
			</div>
			<div class="form-group">
				{!! Form::label('num_of_headers', 'Number of Headers', array('class' => 'col-sm-3 control-label')) !!}
				<div class="col-sm-1">
					{!! Form::text('num_of_headers', $num_of_headers, array('class' => 'form-control')) !!}
				</div>
			</div>
			<div class="form-group">
				{!! Form::label('minify_stylesheet', 'Minify Stylesheet', array('class' => 'col-sm-3 control-label')) !!}
				<div class="col-sm-2">
					{!! Form::checkbox('minify_stylesheet', '', $minify_stylesheet) !!}
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-8 col-sm-offset-3">
					{!! Form::submit('Submit', array('class' => 'btn btn-primary')) !!}
				</div>
			</div>
		{!! Form::close() !!}
	</div>
@endsection