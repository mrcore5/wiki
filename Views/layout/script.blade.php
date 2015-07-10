@section('scripts')

	{{-- Standard scripts --}}
	<script src="{{ asset('js/wiki-bundle.js') }}"></script>

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

		//Hotkey / click search button
		$(document).bind('keyup', '/', function() {
			if (typeof onSearch === 'undefined') {
				$('#search-button').click();
			}
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
