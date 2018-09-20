@include('layout.menu.search')
@include('layout.menu.main')
@include('layout.menu.user')

@section('header-right')
    @yield('search-menu')
    @yield('main-menu')
    @yield('user-menu')
@stop
