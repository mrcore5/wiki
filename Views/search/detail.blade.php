@extends('search.layout')

@section('results')
	DETAIL VIEW
	@foreach ($posts as $result)
		<div class="search-post">
			<a href="{{ Mrcore\Modules\Wiki\Models\Post::route($result->id) }}">{{ $result->title }}</a>
		</div>
	@endforeach

@stop
