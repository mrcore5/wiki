@extends('layout')
@include('layout._partials.search')

@section('title')
	Search Results
@stop

@section('css')
	<!--<link href="{{ asset('css/chosen.min.css') }}" rel="stylesheet">-->
	<link href="{{ asset('css/search.css') }}" rel="stylesheet">
@stop

@section('content')

	{!! Form::open(array('id' => 'form', 'method' => 'get')) !!}

	<div class="row search-content">

		<!-- fix later, this should be a view composer or something -->
		<!-- this is just to show the site/user globals -->
		<!--<form method="post">
		<div><div><div><div><div><div>{ $postContent }</div></div></div></div></div></div>
		</form>-->

		<div class="search-filter col-sm-3">
			<div class="search-filter-section">
				<h4>Badges</h4>
				@foreach ($badges as $badge)
					<div class="checkbox">
						<label>
							<input name="badge{{ $badge->id }}" type="checkbox" value="1" {{ Input::has('badge'.$badge->id) ? 'checked="checked"' : '' }}>
							<span class="lbl">
								<img src="{{ asset('uploads/'.$badge->image) }}" style="width: 16px">
								{{ $badge->name }}
							</span>
						</label>
					</div>
				@endforeach
			</div>

			<!--<div class="section">
				<div class="heading">Tags</div>
				{{ Form::select('tags[]', $tags, $selectedTags, array(
					'id' => 'tags',
					'class' => 'chosen-select',
					'data-placeholder' => 'Choose a Tag...',
					'multiple' => 'multiple',
				)) }}
			</div>
			-->

			<div class="search-filter-section">
				<h4>Types</h4>
				@foreach ($types as $type)
					<div class="checkbox">
						<label>
							<input name="type{{ $type->id }}" type="checkbox" value="1" {{ Input::has('type'.$type->id) ? 'checked="checked"' : '' }}>
							<span class="lbl">
								{{ $type->name }}
							</span>
						</label>
					</div>
				@endforeach
			</div>

			<div class="search-filter-section">
				<h4>Formats</h4>
				@foreach ($formats as $format)
					<div class="checkbox">
						<label>
							<input name="format{{ $format->id }}" type="checkbox" value="1" {{ Input::has('format'.$format->id) ? 'checked="checked"' : '' }}>
							<span class="lbl">
								{{ $format->name }}
							</span>
						</label>
					</div>
				@endforeach
			</div>

			<div class="search-filter-section">
				<h4>Show Only</h4>
				<!--<div class="checkbox">
					<label>
						<input name="unread" type="checkbox" value="1" {{ Input::has('unread') ? 'checked="checked"' : '' }}>
						<span class="lbl">
							Unread
						</span>
					</label>
				</div>-->
				<div class="checkbox">
					<label>
						<input name="hidden" type="checkbox" value="1" {{ Input::has('hidden') ? 'checked="checked"' : '' }}>
						<span class="lbl">
							Hidden
						</span>
					</label>
				</div>
				<div class="checkbox">
					<label>
						<input name="deleted" type="checkbox" value="1" {{ Input::has('deleted') ? 'checked="deleted"' : '' }}>
						<span class="lbl">
							Deleted
						</span>
					</label>
				</div>
			</div>

			<!--<div class="search-filter-section">
				<div class="heading">Tags</div>
				@foreach ($tags as $tag)
					<div class="checkbox">
						<label>
							<input name="tag{{ $tag->id }}" type="checkbox" value="1" {{ Input::has('tag'.$tag->id) ? 'checked="checked"' : '' }}>
							<span class="lbl">
								{{ $tag->name }}
							</span>
						</label>
					</div>
				@endforeach
			</div>-->
		</div>



		<div class="col-sm-9">

			<div class="controls">
				<div class="row">
					<div style="float:right;padding-bottom:5px;">
						<div style="display:table-cell;padding-right:10px;">
						{!! Form::label('sort', 'Sort By ', array(
							'class' => 'control-label'
						)) !!}
						</div>
						<div style="display:table-cell;padding-right:10px;">
						{!! Form::select('sort', $sortOptions, Input::get('sort'), array(
							'id' => 'sort',
							'class' => 'form-control',
						)) !!}
						</div>
						<!--
						<div style="display:table-cell;">
							<button class="btn btn-primary btn-sm"><i class="fa fa-list"></i> List</button>
							<button class="btn btn-default btn-sm"><i class="fa fa-th-list"></i> Detail</button>
						</div>					
						-->
					</div>
					<!--<div class="sort form-horizontal">
						<div class="form-group">
							{!! Form::label('sort', 'Sort', array(
								'class' => 'col-sm-1 control-label'
							)) !!}
							<div class="col-sm-3">
							{!! Form::select('sort', $sortOptions, Input::get('sort'), array(
								'id' => 'sort',
								'class' => 'form-control'
							)) !!}
							</div>

							<!--{!! Form::label('view', 'View', array(
								'class' => 'col-sm-1 control-label'
							)) !!}
							<div class="col-sm-5">
								{!! Html::decode(
									Form::button(
										'<i class="fa fa-list"></i> List',
										array(
											'name' => 'list', 'id' => 'list',
											'class' => 'btn btn-success',
										)
									)
								) !!}
								{!! Html::decode(
									Form::button(
										'<i class="fa fa-th-large"></i> Detail',
										array(
											'name' => 'detail', 'id' => 'detail',
											'class' => 'btn btn-danger',
										)
									)
								) !!}

								{!! Html::decode(
									Form::button( 
										'<i class="fa fa-sitemap"></i> SiteMap',
										array(
											'name' => 'sitemap', 'id' => 'sitemap',
											'class' => 'btn btn-primary',
										)
									)
								) !!}
							</div>-->
					<!--	</div>
					</div>
					-->

					<div class="view">
						<!--{{ HTML::decode(
							Form::button(
								'<i class="fa fa-list"></i> List',
								array(
									'name' => 'list', 'id' => 'list',
									'class' => 'btn btn-success',
								)
							)
						) }}

						{{ HTML::decode(
							Form::button(
								'<i class="fa fa-th-large"></i> Detail',
								array(
									'name' => 'detail', 'id' => 'detail',
									'class' => 'btn btn-danger',
								)
							)
						) }} -->

						<!--{{ HTML::decode(
							Form::button(
								'<i class="fa fa-sitemap"></i> SiteMap',
								array(
									'name' => 'sitemap', 'id' => 'sitemap',
									'class' => 'btn btn-primary',
								)
							)
						) }}-->
						<input type="hidden" name="view" id="view">

					</div>
				</div>

			</div>

			@yield('results')

			<div class="results-pagination">
				@if (!is_array($posts))
					<?
					$get = Input::get();	unset($get['page']);
					$currentPage = $posts->currentPage();
					$count = $posts->count();
					$total = $posts->total();
					?>
					{!! $posts->appends($get)->render() !!}
				@else
					<?
					$currentPage = 1;
					$count = count($posts);
					$total = $count;
					?>
				@endif	
				<div class="results-pagination-info">
					Showing {{ $currentPage }} to {{ $count }} of {{ $total }} results
				</div>
			</div>
			
		</div>

	</div>

	{!! Form::close() !!}

@stop

@section('script')
<!--<script src="{{ asset('js/jquery.chosen.min.js') }}"></script>-->
<script>
var onSearch = true;
$(function() {
	// Start chosen (before validator)
	//$(".chosen-select").chosen({ width: '100%' });

	$('#sort').change(function() {
		submitForm();
	});
	
	$('#list').click(function() {
		$('#view').val("list");
		submitForm();
	});
	$('#detail').click(function() {
		$('#view').val("detail");
		submitForm();
	});
	$('#sitemap').click(function() {
		$('#view').val("sitemap");
		submitForm();
	});

	$(':checkbox').click(function() {
		console.log('fire');
		submitForm();
	});

	function submitForm() {
		// Don't send view= if view is blank, mucks up url
		if (!$('#view').val()) $('#view').removeAttr('name');

		// Dont send sort=relevance, assume default
		if ($('#sort option:selected').val() == 'relevance') $('#sort').removeAttr('name');
		
		// Submit Form
		this.form.submit();
	}
});

$(document).bind('keyup', '/', function() {
	$('#search').focus();
});

</script>
@stop