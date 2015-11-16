@section('permissions')
{!! Form::open(array(
	'id' => 'permissions-form',
	'class' => 'form-horizontal',
	'novalidate' => 'novalidate'
)) !!}

<div class="row">
	<div class="col-lg-12">
		<div class="page-header">
			<h3>Permissions</h3>
		</div>
	</div>
</div>


<div class="row">
	<div class="col-sm-4">
	<table class="table table-bordered table-striped table-hover">
		<thead>
		<tr>
			<th>&nbsp</th>
			@foreach ($perms as $perm)
				<th>{{ $perm->constant }}</th>
			@endforeach
		</tr>
		</thead>
		<tbody>
		<?
			foreach ($roles as $role) {
				echo "<tr><td>$role->name</td>";

				// Check if user has access to this role
				if (!Auth::admin()) {
					$hasRole = false;
					foreach ($userRoles as $userRole) {
						if ($userRole->role_id == $role->id) {
							$hasRole = true;
							break;
						}
					}
				} else {
					$hasRole = true;
				}

				// Print all roles permissions, even if no access...but disable checkbox if need
				foreach ($perms as $perm) {
					$found = false;
					foreach ($postPerms as $postPerm) {
						if ($postPerm->permission_id == $perm->id && $postPerm->role_id == $role->id) {
							$found = true;
							break;
						}
					}
					$checked = ''; $disabled = '';
					if ($found) $checked = 'checked';
					if (!$hasRole) $disabled = 'disabled';
					echo "<td><input type='checkbox' name='perm' value='role_".$role->id."_perm_".$perm->id."' $checked $disabled></td>";
				}

			}
		?>
		</tbody>
	</table>
	</div>
</div>



<div class="row">
	<div class="col-lg-12">
		<div class="page-header">
			<h3>Public URL</h3>
		</div>
	</div>
</div>

<div class="row">
	<div class="col-lg-12">
		<div class="help-block">
			<p>
			This link will allow anyone to READ this document regardless of the topics permissions. This allow the sharing of private topics to the public. This method is slightly more secure than simply making the topic public and is the best option for giving out sensitive topics to a few public individuals. This URL will NOT require the user to login and therefore may be a security risk. Use caution when giving this link to the public.
			</p>
			<p>
			Any file attached to this topic can also be appended with <b>?uuid={{ $post->uuid }}</b> to give public access.
			</p>


			<p>
				Public URL: <a href="{{ $post->route($post->id) }}?uuid={{ $post->uuid }}">{{ $post->route($post->id) }}?uuid={{ $post->uuid }}</a>
			</p>
		</div>

		<p>
		<div class="checkbox">
			<label>
				{!! Form::checkbox('shared', 'shared', $post->shared, array(
					'id' => 'shared',
					'class' => '',
				)) !!}
				Enable Public Sharing (never disable this once it is in use)
			</label>
		</div>
		</p>
	</div>
</div>


<div class="row">
	<div class="form-actions">
		<div class="col-sm-9 col-sm-offset-3">
			{!! Html::decode(
				Form::button(
					'<i class="fa fa-save"></i> Save Permission Preferences',
					array(
						'name' => 'btnSavePerms', 'id' => 'btnSavePerms',
						'class' => 'btn btn-success',
						'title' => 'Save permission preferences only, does not save post'
					)
				)
			) !!}

			{!! Html::decode(
				Form::button(
					'<i class="fa fa-save"></i> Save & Publish',
					array(
						'name' => 'btnSavePermsView', 'id' => 'btnSavePermsView',
						'class' => 'btn btn-primary',
						'title' => 'Save permission peferences, publish and view post'
					)
				)
			) !!}


		</div>

	</div>
</div>

{!! Form::close() !!}
@stop
