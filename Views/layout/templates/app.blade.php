@include('layout._partials.navigation')
@include('layout._partials.show-help')

@section('css')
	@parent
	<style>
	#page-title {
		margin-bottom:0px;
		margin-top:0px;
		font-weight:bold;
	}

	.panel-subheading {
		margin:0px;
		padding:10px;
		padding-left:15px;
		border-bottom-width:1px;
		border-bottom-style: solid;
		font-weight:bold;
		font-size:90%;
	}

	#page-subtitle {
		display:inline;
	}

	#page-actions {
		margin-top:-5px;
	}

	#page-help {
		display:inline;
		float:right;
		font-weight:bold;
		width:25px;
	}

	.panel-body-inner {
		margin-left:-15px;
		margin-right:-15px;
	}

	.popover {
		min-width:250px;
	}

	#page-content {
		padding-top:10px;
	}

	.action-bar {
		display:inline;
		text-align:right;
	}

	.action-bar-items {
		list-style: none;
		margin:0px;
	}

	.action-item {
		display:inline-block;
	}

	.action-item .dropdown-menu li {
		font-size:12px;
		margin:4px;
	}

	.action-item a, .action-item div {
		display:inline-block;
		margin-left:5px;
	}

	.section-bar {
		margin-left:-15px;
		margin-right:-15px;
		border-top:1px solid;
		border-bottom:1px solid;
		border-radius: 0px;
		margin-bottom:10px;
	}

	.action-item .dropdown-menu li a:hover {
		background-color:transparent;
		cursor:pointer;
	}

	</style>
@stop

@section('template')
	@if (!isset($useContainer) || $useContainer)
		<div class="container">
	@endif
	<div class="row" style="position: relative">
		<div id="app-layout-navigation" class="col-md-2">
			<!-- Navigation -->
			@yield('navigation')
		</div>
		<div id="app-layout-content" class="col-md-10">
			<!-- Page Content -->
			<div id="main-content">
				<div class="panel panel-default">
					<div class="panel-heading">
						<h4 id="page-title" class="text-primary">
							<div id="app-navigation-collapsed" class="text-primary"><i class="fa fa-bars"></i></div>
							{{ $page->title }}
						</h4>
					</div>
					<div class="panel-subheading theme-bg-color-4 theme-border-color-3">
						@if (isset($page->subtitle))
							<div id="page-subtitle">
								{{ $page->subtitle }}
							</div>
						@endif
							<div id="page-actions" class="pull-right">
								@yield('page-actions')
							</div>
					</div>
					<div class="panel-body">
						@yield('wb-content')
					</div>
				</div>
			</div>
		</div>
	</div>
	@if (!isset($useContainer) || $useContainer)
	</div>
	@endif

	@yield('show-help-modal')
@stop
