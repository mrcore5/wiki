@include('layout._partials.show-help')
@include('layout.templates.app-css')

@section('css')
	@parent
	<style>
		/*Override app-css as needed*/
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
					<div class="panel panel-default">
						<div class="panel-heading">
							<h4 id="page-title" class="text-primary">
								{{ $page->title }}
							</h4>
						</div>
						@if ($__env->yieldContent('page-subtitle') || $__env->yieldContent('page-actions'))
							<div class="panel-subheading theme-bg-color-4 theme-border-color-3">
								<div id="page-subtitle">
									@yield('page-subtitle')
								</div>
								<div id="page-actions" class="pull-right">
									@yield('page-actions')
								</div>
							</div>
						@endif
						<div class="panel-body">
							<!-- Content -->
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
