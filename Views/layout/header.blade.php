@include('layout.header.logo')
@include('layout.header.left')
@include('layout.header.right')
@include('layout.subheader')

@section('style')

	<!-- Header CSS -->
	<style>
		.navbar-user {
			width: 25px;
			margin: -15px 0px -9px 0px;
			border: 2px solid #eeeeee;
			border-radius: 150px;
		}
		.navbar-user, .navbar-user .caret {

		}
		.navbar {
			border-radius: 0px;
			margin-bottom: 0px;
		}
		.yamm .yamm-content {
			padding: 15px 15px 0px 15px;
		}
	</style>
	@parent

@stop



@section('header')

	<!-- Layout Header -->
	<div class="header">
		<header class="navbar navbar-default yamm" id="top">
			<div class="{{ $headerContainer }}">
				<div class="navbar-header">
					<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-responsive-collapse">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
				</div>
				@yield('header-logo')
				<div class="navbar-collapse collapse navbar-responsive-collapse">
					<ul class="nav navbar-nav">
						{{-- Add your custom left side header menus and items here --}}
						@yield('header-left')

					</ul>
					<ul class="nav navbar-nav navbar-right">
						{{-- Add your custom right side header menus and items here --}}
						@yield('header-right')

					</ul>
				</div>
			</div>
		</header>

		@yield('subheader')
	</div>

@stop

