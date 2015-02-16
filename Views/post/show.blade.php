@extends('layout')

@include('post.header')

@section('title')
	{{ Layout::title() }}
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
