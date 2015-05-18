@section('user-menu')

	@if (Auth::check())
		<li class="dropdown">
			<a href="#" class="dropdown-toggle" data-toggle="dropdown">
				<img class="navbar-user" src="{{ asset('uploads/'.Mrcore::user()->avatar()) }}" alt="avatar" />
				<b class="caret"></b>
			</a>

			<ul class="dropdown-menu">
				@if (Mrcore::user()->hasPermission('create'))
					<li>
						<a href="{{ URL::route('new') }}">
							<!--<i class="fa fa-plus"></i>-->
							New Post
						</a>
					</li>
					<li class="divider"></li>
				@endif

				@if (Auth::admin())
					<li class="dropdown-header">Administrator</li>
					<li>
						<a href="{{ URL::route('admin.badge.index') }}">
							<!--<i class="fa fa-lock"></i>-->
							Admin
						</a>
					</li>
					<li>
						<a href="{{ URL::route('router') }}">
							<!--<i class="fa fa-link"></i>-->
							Router
						</a>
					</li>
					<li class="divider"></li>
				@endif
				<li>
					<a href="/auth/logout">
						<!--<i class="fa fa-power-off"></i>-->
						Sign Out
					</a>
				</li>
			</ul>
		</li>
	@else
		<li>
			<a href="/auth/login">
				Sign In
			</a>
		</li>
	@endif


@stop