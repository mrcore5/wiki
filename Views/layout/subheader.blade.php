@section('style')
	@parent 
	<style>

	#headerBar {
		height:2px;
	}

	.wb-subheader-row {
		height:45px;
	}

	.wb-subheader-label div {
		font-weight:bold;
		font-size:18px;
		height:45px;
		padding-top:10px;
	}
</style>
@stop

@section('subheader')
@if (isset($__env->getSections()['subheader-title']))
<div class="wb-subheader navbar-default">
	<div class="container">
		<div class="row wb-subheader-row">
			<div class="col-xs-12 col-sm-12 col-md-3 wb-subheader-label bg-primary">
				<div class="text-default">
					@yield('subheader-title')
				</div>
			</div>
			<div class="col-xs-12 col-sm-12 col-md-9 wb-subheader-content">
				@yield('subheader-content')						
			</div>				
		</div>
	</div>
	<div id="headerBarBottom" class="nav-tabs"></div>
</div>
@endif
@stop