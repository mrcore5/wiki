@if (sizeOf($post->permissions()) > 0)
	@foreach ($post->permissions() as $key => $permission)
		@if ($key == 'Public')
			<span class="text-success">
		@else
			<span class="text-primary">
		@endif
			{{ $key }}</span>: {{ implode($permission, ',') }}<br />
	@endforeach
@else
	<span class="text-danger">Private</span>
@endif