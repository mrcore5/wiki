@include('search.searchbox')
@include('layout.subheader')

@section('css')
	<!--<link href="{{ asset('css/chosen.min.css') }}" rel="stylesheet">-->
	<link href="{{ asset('css/search.css') }}" rel="stylesheet">
@stop
@yield('css')
@yield('subheader')	
@yield('content')	
@yield('script')