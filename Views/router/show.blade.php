@extends('layout')


@section('title')
	Router
@stop

@section('titlebar-title')
	<i class="icon-link"></i>
	Router
@stop

@section('css')
<style>
	.results {
		border-bottom: 1px solid #ddd;
	}
	.pagination {
		margin-bottom: 0px;
	}
	.pagination-info {
		margin-top: 0px;
		font-size: 11px;
		color: #666;
	}
	.permalink {

	}
	.static {
		font-weight: bold;
	}
	.nowrap {
		white-space: nowrap;
	}
	.small {
		font-size: 10px;
	}
</style>
@stop

@section('content')

	<div class="results">
		<table border="0" id="routes" class="table table-striped table-bordered table-hover dataTable">
			<thead>
				<tr>
					<th width="5">PostID</th>
					<th>URL</th>
					<th width="5">Redirect</th>
					<th width="5">Clicks</th>
					<th width="5">Default</th>
					<th width="5">Static</th>
					<th width="5">Redirect</th>
					<th width="5">Password</th>
					<th width="5">Expiration</th>
					<th width="5">Creator</th>
					<th width="5">Created</th>
					<th width="5">Disabled</th>
				</tr>
			</thead>
			<tbody>
			@foreach ($router as $route)
				<tr>
					<td>{{ $route->post_id }}</td>
					@if ($route->static)
						{{-- Static Named Route like /home --}}
						<td class="static">
							<a href="{{ URL::to($route->slug) }}" target="_blank">
								{{{ $route->slug }}}
							</a>
						</td>
					@else
						@if ($route->post_id)
							{{-- Permalink standard /42/my-page route --}}
							<td class="permalink">
								<a href="{{ Mrcore\Modules\Wiki\Models\Post::route($route->post_id) }}" target="_blank">
									{{{ $route->post_id }}}/{{{ $route->slug }}}
								</a>
							</td>

						@else
							{{-- External URL --}}
							<td>
								<a href="{{{ URL::to($route->slug) }}}" target="_blank">
									{{{ $route->slug }}}
								</a>
							</td>
						@endif
					@endif

					{{-- Route To --}}
					@if ($route->post_id)
						<td></td>
					@else
						{{-- External URL --}}
						<td>{{{ $route->url }}}</td>
					@endif

					<td>{{{ $route->clicks }}}</td>

					<td>{{{ $route->default ? 'yes' : 'no' }}}</td>

					<td>{{{ $route->static ? 'yes' : 'no' }}}</td>

					<td>{{{ $route->redirect ? 'yes' : 'no' }}}</td>

					<td>{{{ $route->password ? 'yes' : 'no' }}}</td>

					<td class="small nowrap">{{{ $route->expiration }}}</td>

					<td class="small">{{{ $route->creator->alias }}}</td>

					<td class="small nowrap">{{{ $route->created_at }}}</td>

					<td>{{{ $route->disabled ? 'yes' : 'no' }}}</td>
				</tr>
			@endforeach
			</tbody>
			<tfoot>
				<td><input type="text" name="search_0" class="search_init"></td>
				<td><input type="text" name="search_1" class="search_init"></td>
				<td><input type="text" name="search_2" class="search_init"></td>
				<td><input type="text" name="search_3" class="search_init"></td>
				<td><input type="text" name="search_4" class="search_init"></td>
				<td><input type="text" name="search_5" class="search_init"></td>
				<td><input type="text" name="search_6" class="search_init"></td>
				<td><input type="text" name="search_7" class="search_init"></td>
				<td><input type="text" name="search_8" class="search_init"></td>
				<td><input type="text" name="search_9" class="search_init"></td>
				<td><input type="text" name="search_10" class="search_init"></td>
				<td><input type="text" name="search_11" class="search_init"></td>
			</tfoot>
		</table>
	</div>

@stop


@section('script')
<script>
$(document).bind('keyup', '/', function() {
	$('#search').focus();
});
</script>
@stop