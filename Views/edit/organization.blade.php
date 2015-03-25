@section('organization')
{!! Form::open(array(
	'id' => 'organization-form',
	'class' => 'form-horizontal',
	'novalidate' => 'novalidate'
)) !!}


<div class="row">
	<div class="col-lg-12">
		<div class="page-header">
			<h1>Organization</h1>
		</div>
	</div>
</div>


<div class="row">
	<div class="form-group">
		{!! Form::label('title', 'Title', array(
			'class' => 'col-sm-3 control-label'
		)) !!}
		<div class="col-sm-6">
			{!! Form::text('title', $post->title, array(
				'class' => 'form-control',
				'onkeyup' => "slugify(this, 'slug')",
				'maxlength' => '100',
				'autocomplete' => 'off'
			)) !!}
		</div>
	</div>

	<div class="form-group">
		{!! Form::label('slug', 'URL Slug', array(
			'class' => 'col-sm-3 control-label'
		)) !!}
		<div class="col-sm-6">
			{!! Form::text('slug', $post->slug, array(
				'class' => 'form-control',
				'readonly' => 'readonly'
			)) !!}
		</div>
	</div>

	<div class="form-group">
		{!! Form::label('badges', 'Badges', array(
			'class' => 'col-sm-3 control-label'
		)) !!}
		<div class="col-sm-6">
			{!! Form::select('badges[]', $badges, $postBadges, array(
				'id' => 'badges',
				'class' => 'select2-tags required form-control',
				'data-placeholder' => 'Choose a Badge...',
				'multiple' => 'multiple',
			)) !!}
		</div>
	</div>

	<div class="form-group">
		{!! Form::label('tags', 'Tags', array(
			'class' => 'col-sm-3 control-label'
		)) !!}
		<div class="col-sm-6">
			{!! Form::select('tags[]', $tags, $postTags, array(
				'id' => 'tags',
				'class' => 'select2-tags required form-control',
				'data-placeholder' => 'Choose a Tag...',
				'multiple' => 'multiple',
			)) !!}
		</div>
	</div>

	<div class="form-group">
		{!! Form::label('new-tags', 'New Tags', array(
			'class' => 'col-sm-3 control-label'
		)) !!}
		<div class="col-sm-6">
			{!! Form::text('new-tags', '', array(
				'class' => 'form-control'
			)) !!}
			<span class="help-block">Comma separated, no spaces in tags</span>
		</div>
		
	</div>


	<div class="form-group">
		{!! Form::label('hashtag', 'Hashtag', array(
			'class' => 'col-sm-3 control-label'
		)) !!}
		<div class="col-sm-6">
			{!! Form::text('hashtag', $hashtag, array(
				'class' => 'form-control',
				'onkeyup' => "hashtagify(this, 'hashtag')",
				'maxlength' => '50',
				'autocomplete' => 'off'
			)) !!}
			<span class="help-block">Never change a hashtag once it is in use, they are constants like URLs</span>
		</div>
		
	</div>


	<div class="form-group">
		{!! Form::label('format', 'Format', array(
			'class' => 'col-sm-3 control-label'
		)) !!}
		<div class="col-sm-6">
			{!! Form::select('format', $formats, $post->format_id, array(
				'class' => 'form-control'
			)) !!}
		</div>
	</div>

	<div class="form-group">
		{!! Form::label('type', 'Type', array(
			'class' => 'col-sm-3 control-label'
		)) !!}
		<div class="col-sm-6">
			{!! Form::select('type', $types, $post->type_id, array(
				'class' => 'form-control'
			)) !!}
		</div>
	</div>

	@if ($post->type_id == Config::get('mrcore.app_type'))
		<div class="form-group" id="framework-group">
	@else
		<div class="form-group" id="framework-group" style='display: none'>
	@endif
		{!! Form::label('framework', 'Framework', array(
			'class' => 'col-sm-3 control-label'
		)) !!}
		<div class="col-sm-6">
			{!! Form::select('framework', $frameworks, $post->framework_id, array(
				'class' => 'form-control'
			)) !!}
		</div>
	</div>

	<div class="form-group">
		{!! Form::label('mode', 'View Mode', array(
			'class' => 'col-sm-3 control-label'
		)) !!}
		<div class="col-sm-6">
			{!! Form::select('mode', $modes, $post->mode_id, array(
				'class' => 'form-control'
			)) !!}
		</div>
	</div>

	<div class="form-group">
		{!! Form::label('hidden', 'Hidden', array(
			'class' => 'col-sm-3 control-label'
		)) !!}

		<div class="col-sm-6 checkbox">
		<label>
			{!! Form::checkbox('hidden', 'hidden', $post->hidden, array(
				'class' => '',
			)) !!}
			Hide Post from search
		</label>
			<span class="help-block">Only hides from browsing or searching, not a security feature</span>
		</div>
	</div>


	<div class=" form-actions">
		<div class="col-sm-9 col-sm-offset-3">
			{!! Html::decode(
				Form::button(
					'<i class="fa fa-save"></i> Save Organization Preferences',
					array(
						'name' => 'btnSaveOrg', 'id' => 'btnSaveOrg',
						'class' => 'btn btn-success',
						'title' => 'Save organization preferences only, does not save post'
					)
				)
			) !!}

			{!! Html::decode(
				Form::button(
					'<i class="fa fa-save"></i> Save & Publish',
					array(
						'name' => 'btnSaveOrgView', 'id' => 'btnSaveOrgView',
						'class' => 'btn btn-primary',
						'title' => 'Save organization peferences, publish and view post'
					)
				)
			) !!}
		</div>

	</div>
</div>

{!! Form::close() !!}
@stop