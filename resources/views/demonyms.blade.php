@extends('template.main')

@section('title', 'Demonyms')

@section('content')
	<div id="content">
		<h3>Demonyms</h3>
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
				<div class="col-sm-4">
					{!! Form::button('Add Demonym', array('class' => 'btn btn-success', 'id' => 'plus-dn')) !!} {!! Form::button('Remove Demonym', array('class' => 'btn btn-danger', 'id' => 'minus-dn')) !!}
				</div>
			</div>
			<div class="form-group col-sm-6" id="demonym-container">
					<div class="col-sm-6">
						Subscribers
					</div>
					<div class="col-sm-6">
						Active Users
					</div>
				@for ($i = 0; $i < count($demonyms); ++$i)
					<div class="form-group">
						<div class="col-sm-6">
							{!! Form::text('demonym' . $i . '[]', $demonyms[$i]['subscribers'], array('class' => 'form-control')) !!}
						</div>
						<div class="col-sm-6">
							{!! Form::text('demonym' . $i . '[]', $demonyms[$i]['online'], array('class' => 'form-control')) !!}
						</div>
					</div>
				@endfor
			</div>
			<div class="form-group">
				<div class="col-sm-12">
					{!! Form::submit('Submit', array('class' => 'btn btn-primary')) !!}
				</div>
			</div>
		{!! Form::close() !!}
	</div>
@endsection

@section('scripts')
	<script type="text/javascript">
		target = document.getElementById('demonym-container');

		// Bind the buttons
		$("#plus-dn").on("click", function(ev) {
			addDemonym();
		});
		$("#minus-dn").on("click", function(ev) {
			subtractDemonym();
		});

		var i = $(target).children().length - 2;

		function addDemonym() {
			var div = document.createElement("DIV");
			div.className = "form-group";
			var subWrapper = document.createElement("DIV");
			subWrapper.className = "col-sm-6";
			var subs = document.createElement("INPUT");
			subs.className = "form-control";
			subs.type = "text";
			subs.name = "demonym" + i + "[]";
			subWrapper.appendChild(subs);
			div.appendChild(subWrapper);
			var usersWrapper = document.createElement("DIV");
			usersWrapper.className = "col-sm-6";
			var users = document.createElement("INPUT");
			users.className = "form-control";
			users.type = "text";
			users.name = "demonym" + i + "[]";
			usersWrapper.appendChild(users);
			div.appendChild(usersWrapper);
			target.appendChild(div);
			i += 1;
			return {"subs": subs, "users": users};
		}

		function subtractDemonym() {

			while (!(target.lastChild instanceof HTMLElement)) {
				target.removeChild(target.lastChild);
			}
			target.removeChild(target.lastChild);
			i -= 1;
		}
	</script>
@endsection