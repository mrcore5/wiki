@section('css')
<!--<link href="{{ asset('css/chosen.min.css') }}" rel="stylesheet" />-->

<style type="text/css" media="screen">
	body {
		overflow: hidden;
	}
	.body {
		margin: 0px;
	}
	.no-container .body {
		padding: 5px 0px 0px 0px;
	}
	.alert-box {
		position: absolute;
		top: 1px;
		/*left: 50%;*/
		right: 1px;
	}
	.alert {
		position: relative;
		/*left: -50%;*/
		z-index: 9999;
		padding: 5px;
		display: none;
	}
	.page-content {
		padding: 0px;
		margin: 0px;
	}
	.btn-scroll-up {
		display: none;
	}
	.tab-content {
		padding: 0px;
		overflow: auto;
	}
	.pad {
		padding: 15px;
	}
	.row, .col-xs-12 {
		margin: 0px;
		padding: 0px;
	}
	#editor {
		/*display: none;*/
		width: 100%;
		font-size: 12px;
	}
	#tab-tabs {
		padding-left: 50px;
	}
	#tab-tabs i {
		font-size: 120%;
	}
	#edit-menu li {
		cursor: pointer;
	}
	.chosen-container-multi .chosen-choices li.search-field input[type="text"] {
		height: 26px
	}
	.chosen-container {
		width: 250px;
	}

	/* FineDiff */
	.revision pre {
		max-height: 400px;
	}
	.modal-dialog {
		width: 80%;
		padding: 15px;
	}
	ins {
		color: green;
		background: #dfd;
		text-decoration: none;
	}
	del {
		color: red;
		background: #fdd;
		text-decoration: none;
	}

</style>
@stop
