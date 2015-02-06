@extends('layout')

@include('post.header')

@section('title')
	{{ Layout::title() }}
@stop

@section('css')
	<style>
		.body {
			padding-top: 15px;
		}		
	</style>
@stop

@section('titlebar-title')
	<div id="titlebar-badges">
		@if (count($post->badges) > 1)
			@foreach ($post->badges as $badge)
				<a href="{{ URL::route('search').'?badge'.$badge->id.'=1' }}">
					<img class="post-badges" src="{{ asset('uploads/'.$badge->image) }}" border="0" alt="{{ $badge->name }}">
				</a>
			@endforeach
		@else
			<a href="{{ URL::route('search').'?badge'.$post->badges[0]->id.'=1' }}">
				<img class="post-badge" src="{{ asset('uploads/'.$post->badges[0]->image) }}" border="0" alt="{{ $post->badges[0]->name }}">
			</a>
		@endif
	</div>

	@if ($post->type->constant == 'doc')
		<div id="titlebar-post-title" style="margin-top: -4px">
	@else
		<div id="titlebar-post-title">
	@endif
	<div id="titlebar-post-title">
		@if ($post->deleted)
			<span style="color: red">{{ $post->title }} (DELETED)</span>
		@else
			{{ $post->title }}
			@if ($post->type->constant == 'doc')
				<div id="titlebar-post-info">
					<i class="icon-info-sign smaller-90 grey"></i>
					Post <a href="{{ route('permalink', $post->id) }}">#{{ $post->id }}</a> by {{ $post->creator->alias }} {{ date("M jS Y", strtotime($post->created_at)) }} updated by {{ $post->updater->alias }} {{ date("M jS Y", strtotime($post->updated_at)) }} ({{ $post->clicks }} views)
				</div>
				<div class="post-tags">
					<i class="icon-tags smaller-90 grey"></i>
					@foreach ($post->tags as $tag)
						<span class="post-tag">
							<a href="{{ URL::route('search').'?tag'.$tag->id.'=1' }}">{{ $tag->name }}</a>
						</span>
					@endforeach
				</div>
			@endif
		@endif
	</div>
@stop


@section('content')
	@if (Layout::modeIs('raw'))
		{!! $post->content !!}
	@else
		<form method="post">

			@if ($post->type->constant == 'doc')
				@yield('post-header')
			@endif

			<div><div><div><div><div><div>{!! $post->content !!}</div></div></div></div></div></div>			

		</form>
	@endif
@stop


@section('script')
	<script>
		@if (isset($post))
			@if ($post->hasPermission('write', $post->perms))
				// Hotkey Ctrl+Enter edits post
				$(document).bind('keydown', 'ctrl+return', function() {
					window.location = '{{ URL::route("edit", array("id" => $post->id)) }}';
				});
			@endif
		@endif
	</script>
@stop
