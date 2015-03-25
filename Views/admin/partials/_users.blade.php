<!-- Admin Badges Partial -->
<div class="row">
	<div class="col-md-4">
		{!! Form::label('Email', 'Email') !!}
		{!! Form::text('email', null, array('id' => 'form-Email', 'class' => 'required form-control')) !!}
	</div>
	<div class="col-md-4">
		{!! Form::label('Alias', 'Alias') !!}
		{!! Form::text('alias', null, array('id' => 'form-Alias', 'class' => 'required form-control')) !!}
	</div>	
	<div class="col-md-4">
		{!! Form::label('Password', 'Password') !!}
		{!! Form::password('password', array('id' => 'form-Password', 'class' => 'form-control')) !!}
	</div>	
</div>
<div class="row">	
	<div class="col-md-3">
		{!! Form::label('FName', 'First Name') !!}
		{!! Form::text('first', null, array('id' => 'form-FName', 'class' => 'required form-control')) !!}
	</div>
	<div class="col-md-3">
		{!! Form::label('LName', 'Last Name') !!}
		{!! Form::text('last', null, array('id' => 'form-LName', 'class' => 'required form-control')) !!}
	</div>	
	<div class="col-md-2">
		{!! Form::label('GlobalPostID', 'Global Post ID') !!}
		{!! Form::text('global_post_id', null, array('id' => 'form-GlobalPostID', 'class' => 'form-control', 'style' => 'width:100px;')) !!}
	</div>
	<div class="col-md-2">
		{!! Form::label('HomePostID', 'Home Post ID') !!}
		{!! Form::text('home_post_id', null, array('id' => 'form-HomePostID', 'class' => 'form-control', 'style' => 'width:100px;')) !!}
	</div>	
	<div class="col-md-2">	
		{!! Form::label('Disabled', 'User Disabled') !!}
		{!! Form::checkbox('disabled', 1, 0, array('id' => 'form-Disabled')) !!}		
	</div>
</div>
<div class="row">	
	<div class="col-md-6">
	<h3>Avatar</h3>
	<hr>
		{!! Form::label('Avatar', 'Avatar') !!}
		{!! Form::file('avatar', array('id' => 'form-LName', 'class' => 'form-control')) !!}
	</div>
</div>
<div class="row">	
	<div class="col-md-4">
		<h3>Roles</h3>
		<hr>
		<ul id="user-roles">
		</ul>
	</div>
	<div class="col-md-8">
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
						$('#form-GlobalPostID').val(data.user.global_post_id);
						$('#form-HomePostID').val(data.user.home_post_id);
						if (data.user.disabled == 1) {
							$('#form-Disabled').prop('checked', 'checked');
						}
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
			var listItem = '<li><input type="checkbox" name="' + chkGroup + '" value="' + value.id + '"';
			if (value.set) {
				listItem += ' checked ';
			}
			listItem += ' />' + value.name;
			return listItem;
		}
	</script>
@stop