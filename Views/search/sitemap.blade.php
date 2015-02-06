@extends('search.layout')

@section('results')
	SITEMAP VIEW
	@foreach ($posts as $post)
		<div class="search-post">
			<a href="{{ Mrcore\Modules\Wiki\Models\Post::route($post->id) }}">{{ $post->title }}</a>
		</div>
	@endforeach

@stop
