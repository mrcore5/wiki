@include('layout._partials.search')

@section('css')
	@parent
	<style>
		#search-menu {
			padding:0px !important;
		}

		#search-menu .yamm-content {
			padding:0px;
		}

		#search-menu .wb-subheader-label {
			padding-left:15px;
			padding-right:15px;
		}
	</style>
@stop

@section('content')
	<div style="padding:15px;">
		{!! $postContent !!}
	</div>
@stop