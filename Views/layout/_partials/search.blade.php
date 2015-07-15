@section('style')
	@parent
	<style type="text/css">
		#search {
			margin-top: 5px;
			display: inline;
		}
	</style>
@stop

@section('subheader-title')
	<i class="fa fa-search"></i> Search
@stop

@section('subheader-content')
	<div id="search-container">
		<input type="text" name="search" id="search" class="form-control" style="width:500px;" value="{{ $searchQuery or '' }}" /> 
		<button id="search-go-btn" class="btn btn-success btn-sm" style="margin-left:15px;margin-bottom:8px;">Go <i class="fa fa-arrow-right"></i></button>
		<div id="search-results-box" style="display:none;">
		</div>	
	</div>
@stop

@section('script')
@parent
	<script type="text/javascript">
	var position = -1;
	var timeout;

	$('#search').on('keyup', function(e) {
		var keyword = $(this).val();
		var key = e.keyCode;
		if ( key == 40 ) {
	    	// Down key
	    	if (position != $('#search-results-box a').length-1) {
	    		position += 1;
	    	}
	    	$('#search-results-box a').removeClass('selected');
	        $('#search-results-box a:eq(' + position + ')').addClass('selected');
	    }
	    else if ( key == 38 ) {
	    	// Up key
	    	if (position != -1) {
	        	position -= 1;
	        }
	        $('#search-results-box a').removeClass('selected');
	        if (position != -1) {
	        	$('#search-results-box a:eq(' + position + ')').addClass('selected');
	        }	        
	    } else if (key == 13 ) {
	    	// Enter key 
	    	if (position > -1) {
	    		// go to direct link
	    		window.location = $('#search-results-box a:eq(' + position + ')').attr('href');
	    	} else {
	    		// go to search page
	    		//window.location = '/search/' + keyword;	    		
	    		submitSearch();
	    	}
	    } else {
	    	if (keyword.length >= 3) {
	    		if (timeout) {
			      	clearTimeout(timeout);
			    }

			    timeout = setTimeout(function() {
			   	var query = getSearchQuery();
			   	
		        // ajax
				$.ajax({
					method: 'GET',
					url: '/search/ajax' + query,
				}).done(function(data) {
					$('#search-results-box').empty();					
					$('#search-results-box').show();
					$.each(data.data, function(key, item) {

						if (item.id) {			
							$('#search-results-box').append(buildSearchResultItem(item, keyword));				
						}
					});	
				});
			    }.bind(this), 250);	    		
	    	} else {
	    		$('#search-results-box').empty();
	    	}
	    }
	});

	function buildSearchResultItem(item, keyword) {		
		var str = '<a class="item" href="' + item.url + '">';
		str += '<span class="item-title">' + highlightKeyword(item.title, keyword) + '</span><br />';
		str += '<span class="item-url">' + highlightKeyword(item.url, keyword) + '</span>';
		str += '</a>';
		return str;
	}

	function highlightKeyword(text, keyword) {	
		if (keyword.indexOf('badge:') == 0) {
			keyword = getTrueKeyword(keyword, 6);
		} else if (keyword.indexOf('tag:') == 0) {
			keyword = getTrueKeyword(keyword, 4);
		} else if (keyword.indexOf('type:') == 0) {
			keyword = getTrueKeyword(keyword, 5);
		} else if (keyword.indexOf('format:') == 0) {
			keyword = getTrueKeyword(keyword, 6);
		}
		if (text) {
			text = text.toLowerCase().replace(new RegExp(keyword.toLowerCase(), 'g'), '<span class="item-highlight">' + keyword.toLowerCase() + '</span>');			
		}
		return text;
	}

	function getTrueKeyword(keyword, char) {
		keyword = keyword.substr(char).trim();
		var split = keyword.split(' ');
		if (split.length > 1) {
			split.shift();
			keyword = split.join(' ');
		}
		return keyword;
	}

	$('body').click(function() {
		if (!$('#search').is(':focus')) {
			$('#search-results-box').hide();
		}
	});

	$('#search').focus();

	$('#search-go-btn').click(function() {
		submitSearch();
	});

	function getSearchQuery() {
		var query = $('#search').val();
		var keyword = '';
		var url = '';
		
		if (query.indexOf('badge:') >= 0 || query.indexOf('type:') >= 0 || query.indexOf('format:') >= 0) {
			// contains badge, type or format
			var badges, types, formats;
			var keywords = [];
			var params = [];
			var pieces = query.split(' ');
			for (var i = 0; i < pieces.length; i++) {
				if (pieces[i].length > 0) {
					console.log('a:'+pieces[i]);
					if (pieces[i].indexOf('badge:') >= 0) {
						// is badge
						if (pieces[i] == 'badge:') {
							// has space
							badges = 'badge=' + pieces[i+1];	
							i++;
						} else {
							// no space
							var split = pieces[i].split(':');
							badges = 'badge=' + split[1];
						}
						params.push(badges);									
					} else if (pieces[i].indexOf('type:') >= 0) {
						// is type
						if (pieces[i] == 'type:') {
							// has space
							types = 'type=' + pieces[i+1];
							i++;
						} else {
							// no space
							var split = pieces[i].split(':');
							types = 'type=' + split[1];	
						}					
						params.push(types);					
					} else if (pieces[i].indexOf('format:') >= 0) {
						// is format
						if (pieces[i] == 'format:') {
							// has space
							formats = 'format=' + pieces[i+1];
							i++;
						} else {
							// no space
							var split = pieces[i].split(':');
							formats = 'format=' + split[1];	
						}					
						params.push(formats);					
					} else {
						// keyword
						keywords.push(pieces[i]);
					}
				}
			}
			url += '?' + params.join('&');
			keyword = keywords.join(' ');			
		} else {
			keyword = query;
		}

		keyword = encodeURIComponent(keyword)
			.replace(/%20/g, '+')
			.replace(/%3A/g, '=')
			.replace(/%23/g, '#')
			.replace(/%2C/g, ',')
			.replace(/%2F/g, '/');

		if (url.indexOf('?') >= 0) {
			url += '&';
		} else {
			url += '?';
		}
		url += 'key=' + keyword;

		return url;
	}

	// Submit Search
	function submitSearch() {
		var url = "{{ URL::route('search') }}" + getSearchQuery();
		window.location = url;		
	}

	</script>
@stop