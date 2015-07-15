@section('search-menu')

<li class="dropdown yamm-fullwidth">
	<a href="#" id="search-button" class="dropdown-toggle" data-toggle="dropdown">
		<i class="fa fa-search text-danger" title="search"></i> <span class="text-danger">Search</span>
	</a>
	<ul class="dropdown-menu" id="search-menu">
		<li>
			<div class="yamm-content">
					<div id="searchbox">
						<div class="loading-icon">
							<i class="fa fa-spinner fa-spin"></i>
							Loading Search ...
						</div>
					</div>
			</div>
		</li>
	</ul>
</li>

@stop