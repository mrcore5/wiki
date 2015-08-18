@extends('layout')

@section('title')
	{{ Layout::title() }}
@stop

@section('css')
	<!-- File Manager CSS -->
	<style>
		.fm-table {
			white-space: nowrap;
		}
	</style>
@stop


@section('layout-title')
	File Manager
@stop

@section('content')
	<div class="fm" data-path="{{ $url->getPath() }}">
		<i class="icon-spinner icon-spin blue"></i>
		Loading File Manager x...
	</div>
@stop