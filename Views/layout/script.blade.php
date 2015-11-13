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

		// Filemanager
		var fm_instance = 0;
		$( ".fm" ).each(function( index ) {
			fm_instance ++;
			var path = $(this).attr('data-path');
			var params = $(this).attr('data-params');
			var id = 'fm' + fm_instance
			$(this).attr('id', id);
			$.ajaxSetup({
				headers: {
					'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
				}
			});
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
