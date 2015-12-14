@section('css')
	@parent
	<style>
	#page-title {
		margin-bottom: 0px;
		margin-top: 0px;
		font-weight: bold;
	}
	.panel-subheading {
		margin: 0px;
		padding: 10px;
		padding-left: 15px;
		border-bottom-width: 1px;
		border-bottom-style: solid;
		font-weight: bold;
		font-size: 90%;
		min-height: 40px;
	}
	#page-subtitle {
		display: inline;
	}

	#page-actions {
		margin-top: -5px;
	}

	#page-actions select {
		max-height: 30px;
	}

	#page-help {
		display: block;
		float: right;
		font-weight: bold;
		width: 25px;
	}

	.panel-body-inner {
		margin-left: -15px;
		margin-right: -15px;
	}

	.popover {
		min-width: 250px;
	}

	#page-content {
		padding-top: 10px;
	}

	.action-bar {
		display: inline;
		text-align: right;
	}

	.action-bar-items {
		list-style: none;
		margin: 0px;
	}

	.action-item {
		display: inline-block;
	}

	.action-item .dropdown-menu li {
		font-size: 12px;
		margin: 4px;
	}

	.action-item a, .action-item div {
		display: inline-block;
		margin-left: 5px;
	}

	.section-bar {
		margin-left: -15px;
		margin-right: -15px;
		border-top: 1px solid;
		border-bottom: 1px solid;
		border-radius: 0px;
		margin-bottom: 10px;
	}

	.action-item .dropdown-menu li a:hover {
		background-color: transparent;
		cursor: pointer;
	}
	</style>
@stop
