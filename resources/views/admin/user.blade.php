@extends('template.main')

@section('title', 'Edit User')

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
		@elseif (isset($success_message))
			<div class="alert alert-success">
				<p>{{ $success_message }}</p>
			</div>
		@endif
		{!! Form::open(array('class' => 'form-horizontal')) !!}
		<div class="form-group">
			{!! Form::label('username', 'Username', array('class' => 'col-sm-2 control-label')) !!}
			<div class="col-sm-2">
				{!! Form::text('username', $user->username, array('class' => 'form-control')) !!}
			</div>
		</div>
		<div class="form-group">
			{!! Form::label('permissions', 'Permissions', array('class' => 'col-sm-2 control-label')) !!}
			<div class="col-sm-5">
				{!! Form::text('permissions', $user->permissions, array('class' => 'form-control')) !!}
			</div>
		</div>
		<div class="form-group">
			{!! Form::label('delete', 'Delete User', array('class' => 'col-sm-2 control-label')) !!}
			<div class="col-sm-5">
				{!! Form::checkbox('delete') !!}
			</div>
		</div>
		<div class="form-group">
			<div class="col-sm-8 col-sm-offset-2">
				{!! Form::submit('Submit', array('class' => 'btn btn-primary')) !!}
			</div>
		</div>
		{!! Form::close() !!}
	</div>
@endsection