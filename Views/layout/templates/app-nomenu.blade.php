@include('layout._partials.show-help')

@section('css')
	@parent
	<style>
	 #page-title {
		border-bottom-width:1px;
		border-bottom-style: solid;
		margin-bottom:0px;
		margin-top:0px;
		font-weight:bold;
	}

	#page-subtitle {
		margin:0px;
		padding:10px;
		border-bottom-width:1px;
		border-bottom-style: solid;
		margin-bottom:10px;
	}

	#page-title-text {
		display:inline;
		float:right;
	}

	#page-help {
		display:inline;
		float:right;
		font-weight:bold;
		width:25px;
	}

	#page-content {
		border-top-width:1px;
		border-top-style: solid;
		padding-top:10px;
	}

	.action-bar {
		text-align:right;
	}

	.action-bar-items {
		list-style: none;
	}

	.action-item {
		display:inline-block;
	}

	.action-item a, .action-item div {
		display:inline-block;
		margin-left:5px;
	}

	.section-bar {
		margin:0px;
		border-left:4px solid #336699;
		background-color:#fbfbfb;
		padding:7px;
		border-bottom:1px solid #dddddd;
		margin-bottom:15px;
		color:#555555;
	}
	</style>
@stop

@section('template')
	@if (!isset($useContainer) || $useContainer)
		<div class="container">
	@endif
		<div class="row">
			<div id="app-layout-content" class="col-md-12">
				<!-- Page Content -->
				<div id="main-content">
					<h4 id="page-title" class="text-primary theme-border-color-1">
						{{ $page->title }} <div id="page-title-text">{{ $page->displayText or '' }}</div>
					</h4>
					@if (isset($page->subtitle))
						<div id="page-subtitle" class="theme-bg-color-4 theme-border-color-3">{{ $page->subtitle }}</div>
					@endif
					<!-- Content -->
					@yield('wb-content')
				</div>
			</div>
		</div>
		@if (!isset($useContainer) || $useContainer)
		</div>
		@endif

	@yield('show-help-modal')
@stop
