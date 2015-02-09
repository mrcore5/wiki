@extends('layout')

@section('content')
	<div class="row">

		<div class="admin-menu col-sm-4">
			<h1>Administration</h1>
			<ul>
				<li><a href="#">Users</a></li>
				<li><a href="{{ route('adminBadge') }}">Roles</a></li>
				<li><a href="{{ route('router') }}">Router</a></li>
				<li><a href="{{ route('adminBadge') }}">Hashtags</a></li>
				<li><a href="{{ route('adminBadge') }}">Badges</a></li>
				<li><a href="{{ route('adminBadge') }}">Tags</a></li>
				<li><a href="{{ route('adminBadge') }}">Frameworks</a></li>
				<li><a href="{{ route('adminBadge') }}">Indexer</a></li>
			</ul>
			
		
		</div>

		<div class="admin-content col-sm-8">
			@yield('admin-content')
		</div>

	</div>

@stop
