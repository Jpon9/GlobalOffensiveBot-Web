@extends('template.main')

@section('title', 'Sidebar')

@section('content')
	<div id="content">
		<h3>Sidebar</h3>
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
				{!! Form::label('template', 'Sidebar Section Order', array('class' => 'col-sm-2 control-label')) !!}
				<div class="col-sm-6">
					{!! Form::textarea('template', $sidebar['template'], array('class' => 'form-control')) !!}
				</div>
			</div>
			@foreach ($sidebar['chunks'] as $chunk)
				<div class="form-group">
					{!! Form::label($chunk['name'], $chunk['name'], array('class' => 'col-sm-2 control-label')) !!}
					<div class="col-sm-6">
						{!! Form::textarea($chunk['name'], $chunk['body'], array('class' => 'form-control')) !!}
					</div>
				</div>
			@endforeach
			<div class="form-group">
				<div class="col-sm-8 col-sm-offset-2">
					{!! Form::submit('Submit', array('class' => 'btn btn-primary')) !!}
				</div>
			</div>
		{!! Form::close() !!}
	</div>
@endsection