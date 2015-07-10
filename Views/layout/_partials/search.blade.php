@section('script')
@parent
<script type="text/javascript">
	$('#search').keyup(function(event) {
		var query = $('#search').val();
		//13 enter, 27 esc
		if (event.keyCode == 13) {
			submitSearch();
		} else if(event.keyCode == 27) {
			if (query) {
				$('#search').val('');
			} else {
				$('#search-button').click();
			}
		}
	});

	$('#search-go-btn').click(function() {
		submitSearch();
	});

	// Submit Search
	function submitSearch() {
		var query = $('#search').val();
		if (/#/i.test(query)) {
			//Replace # with hashtag becuase # doesn't work (not post back -anchor)
			query = query.replace(/#/g, 'hashtag:');
		};

		// Encode Query
		query = encodeURIComponent(query)
			.replace(/%20/g, '+')
			.replace(/%3A/g, ':')
			.replace(/%23/g, '#')
			.replace(/%2C/g, ',')
			.replace(/%2F/g, '/')
		;
		// Get Search URL
		var url = "{{ URL::route('search') }}";
		url += '/' + query;
		window.location = url;
	}
</script>
@stop