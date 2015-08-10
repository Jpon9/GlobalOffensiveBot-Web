@extends('template.main')

@section('title', 'User Management')
@section('css-title', 'user-management')

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
		<table class="table table-bordered table-striped">
			<thead>
				<tr>
					<th>ID</th>
					<th>Username</th>
					<th>Permissions</th>
					<th>Created</th>
					<th>Modified</th>
				</tr>
			</thead>
			<tbody>
				@foreach ($users as $user)
				<tr>
					<th>{{ $user->id }}</th>
					<td><a href="{{ URL::to('admin/users/' . $user->id) }}">{{ $user->username }}</a></td>
					<td>{{ $user->permissions }}</td>
					<td>{{ $user->created_at }}</td>
					<td>{{ $user->updated_at }}</td>
				</tr>
				@endforeach
			</tbody>
		</table>
	</div>
@endsection