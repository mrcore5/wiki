@section('scripts')

	{{-- Standard scripts --}}
	<script src="{{ asset('js/jquery-2.1.1.min.js') }}"></script>
	<script src="{{ asset('js/jquery.hotkeys.min.js') }}"></script>
	<script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
	<script src="{{ asset('js/jquery.dataTables.bootstrap.js') }}"></script>
	<script src="{{ asset('js/dataTables.js') }}"></script>
	<script src="{{ asset('js/bootstrap.min.js') }}"></script>
	<script src="{{ asset('js/wiki.js') }}"></script>

	{{-- Layout script file array --}}
	@foreach (Layout::js() as $js)
	<script src="{{ asset($js) }}"></script>
	@endforeach

	{{-- Layout script code array --}}
	@if (Layout::script())
		<script>
		@foreach (Layout::script() as $script) 
			{!! $script !!}
		@endforeach
		</script>
	@endif

	{{-- Page script sections --}}
	@yield('script')

	{{-- Master script code --}}
	<script>
	$(function() {

		// Yamm
		$(document).on('click', '.yamm .dropdown-menu', function(e) {
			e.stopPropagation()
		})


		// Search
		var searchLoaded = false;
		$('#search-button').click(function() {
			setTimeout(function() { $('input[name="search"]').focus() }, 100);
			if (!searchLoaded) {
				searchLoaded = true;        	
				$.get(
					"{{ URL::route('searchMenu') }}"
				).done(function(response) {
					$('#searchbox').html(response);
				});
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


		// Search Events
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


		//Hotkey / click search button
		$(document).bind('keyup', '/', function() {
			$('#search-button').click();
			$("#search").focus();
		});


		// Hotkey ESCAPE closes main search menu
		$(document).bind('keyup', 'esc', function() {
			if ($('#search-menu').is(":visible")) {
				$('#search-button').click();
			}
		});


		// Filemanager
		var fm_instance = 0;
		$( ".fm" ).each(function( index ) {
			fm_instance ++;
			var path = $(this).attr('data-path');
			var params = $(this).attr('data-params');
			var id = 'fm' + fm_instance
			$(this).attr('id', id);
			$.post(
				"{{ URL::route('file') }}/" + path,
				{
					params: params
				}
			)
			.done(function(response) {
				$("#" + id).html(response);
			})
			.fail(function(response) {
				$("#" + id).html('Could not load file manager with path ' + path);
				console.log(response);
			});
		});


		// btn-scroll-up animations
		$(window).scroll(function(){
			if($(window).scrollTop() >= 600) {
				$('#btn-scroll-up').fadeIn(500);
			} else {
				$('#btn-scroll-up').fadeOut(500);
			}
		});


	});
	</script>

@stop
