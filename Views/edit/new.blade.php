@extends('layout')

@section('title')
	New Post
@stop

@section('titlebar-title')
	<i class="icon-plus"></i>
	New Post
@stop

@section('css')
<link href="{{ asset('css/chosen.min.css') }}" rel="stylesheet" />
<style>
	.chosen-container-multi .chosen-choices li.search-field input[type="text"] {
		height: 26px
	}
</style>

@stop


@section('content')

	{!! Form::open(array(
		'route' => array('createPost'),
		'id' => 'validation-form',
		'class' => 'form-horizontal'
	)) !!}

	<div class="form-group">
		{!! Form::label('title', 'Title', array(
			'class' => 'col-sm-3 control-label'
		)) !!}
		<div class="col-sm-6">
			{!! Form::text('title', '', array(
				'class' => 'form-control required',
				'onkeyup' => "slugify(this, 'slug')",
				'maxlength' => '100',
				'autocomplete' => 'off')) !!}
		</div>
	</div>

	<div class="form-group">
		{!! Form::label('slug', 'URL Slug', array(
			'class' => 'col-sm-3 control-label'
		)) !!}
		<div class="col-sm-6">
			{!! Form::text('slug', '', array(
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
			{!! Form::select('badges[]', $badges, null, array(
				'class' => 'chosen-select required',
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
		
			{!! Form::select('tags[]', $tags, null, array(
				'class' => 'chosen-select',
				'data-placeholder' => 'Choose a Tag...',
				'multiple' => 'multiple',
			)) !!}
		</div>
	</div>

	<hr />

	<div class="form-group">
		{!! Form::label('format', 'Format', array(
			'class' => 'col-sm-3 control-label'
		)) !!}
		<div class="col-sm-6">
			{!! Form::select('format', $formats, null, array(
				'class' => 'form-control'
			)) !!}
		</div>
	</div>

	<div class="form-group">
		{!! Form::label('type', 'Type', array(
			'class' => 'col-sm-3 control-label'
		)) !!}
		<div class="col-sm-6">
			{!! Form::select('type', $types, null, array(
				'class' => 'form-control'
			)) !!}
		</div>
	</div>

	<div class="form-group" id="framework-group" style='display: none'>
		{!! Form::label('framework', 'Framework', array(
			'class' => 'col-sm-3 control-label'
		)) !!}
		<div class="col-sm-6">
			{!! Form::select('framework', $frameworks, null, array(
				'class' => 'form-control'
			)) !!}
		</div>
	</div>

	<div class="clearfix">
		<div class="col-sm-offset-3 col-sm-4">
			{!! Html::decode(
				Form::button(
					'<i class="fa fa-times"></i>
					Cancel',
					array(
						'name' => 'btnCancel',
						'id' => 'btnCancel',
						'class' => 'btn btn-danger'
					)
				)
			) !!}

			{!! Html::decode(
				Form::button(
					'<i class="fa fa-check"></i>
					Create Post',
					array(
						'name' => 'btnCreate',
						'id' => 'btnCreate',
						'class' => 'btn btn-primary'
					)
				)
			) !!}

		</div>
	</div>

	{!! Form::close() !!}

@stop



@section('script')
<script src="{{ asset('js/jquery.validate.min.js') }}"></script>
<script src="{{ asset('js/jquery.chosen.min.js') }}"></script>
<script>
$(function() {

	// Type DropDown Changed (if app show framework dropdown)
	$('#type').change(function()
	{
		var type = $('#type option:selected').val();
		$('#framework-group').hide();
		if (type == {{ Config::get('mrcore.app_type') }}) {
			$('#framework-group').show();
		}
	});

	// Cancel button event
	$('#btnCancel').click(function() {
		window.history.back();
	})

	// Create button event
	$('#btnCreate').click(function() {
		if ($("#validation-form").valid()) {
			this.form.submit();
		}
	});

	// Start chosen (before validator)
	$(".chosen-select").chosen({ width: '250px' });

	// Input Validation
	var validator = $('#validation-form').validate({
		errorElement: 'div',
		errorClass: 'help-inline',
		focusInvalid: true,
		ignore: ':hidden:not(.chzn-done)',

		rules: {
			title: {
				required: true
			}
		},
		messages: {
			title: {
				required: 'Title is required'
			}
		},

		highlight: function (e) {
			$(e).closest('.form-group').addClass('has-error');
		},

		success: function (e) {
			$(e).closest('.form-group').removeClass('has-error');
			$(e).remove();
		}
	});
	$('#validation-form').find('select.chosen-select').each(function(){
		console.log(this);
		$(this).chosen().change(function(){
			$(this).valid();
		});
		$(this).rules('add', {
			required: true,
		});
	});


	/*
	$('#validation-form').validate({
		errorElement: 'div',
		errorClass: 'help-block',
		focusInvalid: true,
		rules: {
			title: {
				required: true
			}
		},

		messages: {
			title: {
				required: 'Title is required'
			}
		},

		highlight: function (e) {
			$(e).closest('.form-group').addClass('has-error');
		},

		success: function (e) {
			$(e).closest('.form-group').removeClass('has-error');
			$(e).remove();
		}
	});
	*/

})
</script>
@stop
