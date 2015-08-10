@extends('template.main')

@section('title', 'Stylesheet')

@section('content')
	<div id="content">
		<h3>Stylesheet</h3>
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
		{!! Form::open(array('class' => 'form')) !!}
			<div class="form-group">
				<div class="col-sm-12">
					{!! Form::textarea('stylesheet', $stylesheet, array('class' => 'form-control')) !!}
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-2">
					{!! Form::submit('Submit', array('class' => 'btn btn-primary')) !!}
				</div>
			</div>
		{!! Form::close() !!}
	</div>
@endsection