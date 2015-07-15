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
							<input name="badge" type="checkbox" value="{{ $badge->name }}" id="chk-badge-{{ strtolower($badge->name) }}">
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
							<input name="type" type="checkbox" value="{{ $type->name }}" id="chk-type-{{ strtolower($type->name) }}">
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
							<input name="format" type="checkbox" value="{{ $format->name }}" id="chk-format-{{ strtolower($format->name) }}">
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
						<input name="hidden" type="checkbox" value="1" id="chk-hidden-true">
						<span class="lbl">
							Hidden
						</span>
					</label>
				</div>
				<div class="checkbox">
					<label>
						<input name="deleted" type="checkbox" value="1" id="chk-deleted-true">
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
					$perPage = $posts->perPage();
					$starting = 1 + ($perPage * ($currentPage - 1));
					$ending = ($perPage * $currentPage);
					$count = $posts->count();
					$total = $posts->total();

					if ($ending > $total) $ending = $total;
					?>
					{!! $posts->appends($get)->render() !!}
				@else
					<?
					$currentPage = 1;
					$perPage = 10;
					$count = count($posts);
					$total = $count;
					$starting = 1 + ($perPage * ($currentPage - 1));
					$ending = ($perPage * $currentPage);
					?>
				@endif	
				<div class="results-pagination-info">					
					Showing {{ $starting }} to {{ $ending }} of {{ $total }} results
				</div>
			</div>
			
		</div>

	</div>

	{!! Form::close() !!}

@stop

@section('script')
<script>
var onSearch = true;
$(function() {

	$('#sort').change(function() {
		buildSearchQuery();
	});
	
	$('#list').click(function() {
		$('#view').val("list");
		buildSearchQuery();
	});
	$('#detail').click(function() {
		$('#view').val("detail");
		buildSearchQuery();
	});
	$('#sitemap').click(function() {
		$('#view').val("sitemap");
		buildSearchQuery();
	});

	$(':checkbox').click(function() {
		if ($(this).attr('name') == 'badge') {
			$("input[name='badge']").prop('checked', false);
			$(this).prop('checked', true);			
		}

		if ($(this).attr('name') == 'hidden') {
			$("input[name='deleted']").prop('checked', false);
		} else if ($(this).attr('name') == 'deleted') {
			$("input[name='hidden']").prop('checked', false);
		}

		buildSearchQuery();
	});

	function buildSearchQuery() {
		var params = [];
		var badges = buildQueryItems('badge'); 
		if (badges.length > 0) {
			params.push(buildQueryItems('badge'));	
		}
		var types = buildQueryItems('type'); 
		if (types.length > 0) {
			params.push(buildQueryItems('type'));	
		}
		var formats = buildQueryItems('format'); 
		if (formats.length > 0) {
			params.push(buildQueryItems('format'));	
		}
		if ($("input[name='hidden']").is(':checked')) {
			params.push('hidden=true');
		}
		if ($("input[name='deleted']").is(':checked')) {
			params.push('deleted=true');
		}
		if ('{{ Input::get('key') }}' != '') {
			params.push('key={{ Input::get('key')}}');
		}

		if ($('#sort option:selected').val() != 'relevance') {
			params.push('sort=' + $('#sort option:selected').val());
		}

		var query = params.join('&');
		window.location = "{{ URL::to('/search')}}" + "?" + query;		
	}

	function buildQueryItems(key) {
		var items = [];
		var query = '';
		$("input[name='" + key + "']:checked").each(function() {
			items.push($(this).val());			
		});
		if (items.length > 0) {
			query = key +'='+items.join(',');
		}
		return query;		
	}

	function setCheckboxes() {
		selectCheckboxes('{{ Input::get("badge") }}', 'badge');
		selectCheckboxes('{{ Input::get("type") }}', 'type');
		selectCheckboxes('{{ Input::get("format") }}', 'format');
		selectCheckboxes('{{ Input::get("hidden") }}', 'hidden');
		selectCheckboxes('{{ Input::get("deleted") }}', 'deleted');
	}

	function selectCheckboxes(collection, key) {
		var items = collection.split(',');
		for(i = 0; i < items.length; i++) {			
			$('#chk-'+key+'-'+items[i].toLowerCase()).attr('checked', 'checked');
		}
	}

	setCheckboxes();
});

$(document).bind('keyup', '/', function() {
	$('#search').focus();
});

</script>
@stop