<!-- Admin Badges Partial -->
<div class="row">
	<div class="col-md-6">
		{!! Form::label('Email', 'Email') !!}
		{!! Form::text('email', '', array('id' => 'form-Email', 'class' => 'required form-control')) !!}
	</div>
	<div class="col-md-6">
		{!! Form::label('Alias', 'Alias') !!}
		{!! Form::text('alias', '', array('id' => 'form-Alias', 'class' => 'required form-control')) !!}
	</div>	
</div>
<div class="row">
	<div class="col-md-6">
		{!! Form::label('FName', 'First Name') !!}
		{!! Form::text('first', '', array('id' => 'form-FName', 'class' => 'required form-control')) !!}
	</div>
	<div class="col-md-6">
		{!! Form::label('LName', 'Last Name') !!}
		{!! Form::text('last', '', array('id' => 'form-LName', 'class' => 'required form-control')) !!}
	</div>	
</div>
<div class="row">
	<div class="col-md-6">
	<h3>Roles</h3>
	<hr>
	<ul id="user-roles">
	</ul>
	</div>
	<div class="col-md-6">
	<h3>Permissions</h3>
	<hr>
	<ul id="user-permissions">
	</ul>
	</div>
</div>	


@section('script')
	@parent
	<script type="text/javascript">
		$('#form-modal').on('show.bs.modal', function (e) {
			var id = $('#form-ID').val();
			$('#user-roles').empty();
			$('#user-permissions').empty();
			console.log('fired');
		  	$.ajax({
				type: 'GET',
				url: '/admin/user/' + id + '/data',
				success: function(data) {	
					if (data.user) {
						// user data
						$('#form-Alias').val(data.user.alias);
						$('#form-FName').val(data.user.first);
						$('#form-LName').val(data.user.last);
						$('#form-Email').val(data.user.email);
					}

					// roles
					$.each(data.roles, function(k, v)  {		
						$('#user-roles').append(buildListItem('roles[]', v));
					});

					// permissions
					$.each(data.permissions, function(k, v)  {						
						$('#user-permissions').append(buildListItem('permissions[]', v));
					});
				}
			});
		});

		$('#form-modal').on('hidden.bs.modal', function (e) {
			$('#user-roles').empty();
			$('#user-permissions').empty();
			$('#form-ID').val(0);
			resetForm();
		});

		function buildListItem(chkGroup, value) {
			var listItem = '<li><div class="checkbox"><input type="checkbox" name="' + chkGroup + '" value="' + value.id + '"';
			if (value.set) {
				listItem += ' checked ';
			}
			listItem += ' />' + value.name + '</div>';
			return listItem;
		}
	</script>
@stop