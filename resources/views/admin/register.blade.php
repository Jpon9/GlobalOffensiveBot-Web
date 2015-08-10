@extends('template.nosidebar')

@section('title', 'User Creation')
@section('css-title', 'user-creation')

@section('content')
	<div id="content">
		@if (count($errors) > 0)
		    <div class="alert alert-danger">
		        <ul>
		            @foreach ($errors->all() as $error)
		                <li>{{ $error }}</li>
		            @endforeach
		        </ul>
		    </div>
		@endif
		{!! Form::open(array('id' => 'register-form', 'class' => 'form-horizontal')) !!}
			<div class="form-group">
				{!! Form::label('username', 'Username', array('class' => 'col-sm-4 control-label')) !!}
				<div class="col-sm-8">
					{!! Form::text('username', '', array('class' => 'form-control')) !!}
				</div>
			</div>
			<div class="form-group">
				{!! Form::label('password', 'Password', array('class' => 'col-sm-4 control-label')) !!}
				<div class="col-sm-8">
					{!! Form::password('password', array('class' => 'form-control')) !!}
				</div>
			</div>
			<div class="form-group">
				{!! Form::label('password_confirmation', 'Confirm Password', array('class' => 'col-sm-4 control-label')) !!}
				<div class="col-sm-8">
					{!! Form::password('password_confirmation', array('class' => 'form-control')) !!}
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-10">
					{!! Form::submit('Create User', array('class' => 'btn btn-primary')) !!}
				</div>
			</div>
		{!! Form::close() !!}
	</div>
@endsection