@section('post-header')

@if (isset($post))
<div class="post-header">
	<div class="row">
		<div class="col-sm-9">
			@if ($post->deleted)
				<h1 class="post-title post-deleted">{{ $post->title }} (DELETED)</h1>
			@else
				<h1 class="post-title">{{ $post->title }}</h1>
			@endif

			@if ($post->type->constant == 'doc')
				<div class="post-info">
					<i class="fa fa-info-circle"></i>
					Post <a href="{{ route('permalink', $post->id) }}">#{{ $post->id }}</a> by {{ $post->creator->alias }} {{ date("Y-m-d H:i:s", strtotime($post->created_at)) }} updated by {{ $post->updater->alias }} {{ date("Y-m-d H:i:s", strtotime($post->updated_at)) }} ({{ $post->clicks }} views)
				</div>
				<div class="post-tags">
					<i class="fa fa-tags"></i>
					@foreach ($post->tags as $tag)
						<span class="post-tag">
							<a href="{{ URL::route('search').'?tag='.$tag->name }}">{{ $tag->name }}</a>
						</span>
					@endforeach
				</div>
			@endif
		</div>

		<div class="col-sm-1">
			@if (count($post->badges) > 1)
				@foreach ($post->badges as $badge)
					<a href="{{ URL::route('search').'?badge='.$badge->name }}"><img class="post-badge" src="{{ asset('uploads/'.$badge->image) }}" border="0" alt="{{ $badge->name }}"></a>
				@endforeach
			@else
				<a href="{{ URL::route('search').'?badge='.$post->badges[0]->name }}"><img class="post-badge" src="{{ asset('uploads/'.$post->badges[0]->image) }}" border="0" alt="{{ $post->badges[0]->name }}"></a>
			@endif
		</div>
		<div class="col-sm-2 post-permissions theme-bg-color-1 theme-border-color-1">
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
		</div>
	</div>
</div>
@endif
@stop

