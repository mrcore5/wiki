@section('post-header')

<div class="panelx panel-defaultx wellx post-header">
<div class="panel-bodyx row">
	<div class="col-sm-11">
		@if ($post->deleted)
			<h1 class="post-title post-deleted">{{ $post->title }} (DELETED)</h1>
		@else
			<h1 class="post-title">{{ $post->title }}</h1>
		@endif 

		@if ($post->type->constant == 'doc')
			<div class="post-info">
				<i class="fa fa-info-circle smaller-90 grey"></i>
				Post <a href="{{ route('permalink', $post->id) }}">#{{ $post->id }}</a> by {{ $post->creator->alias }} {{ date("Y-m-d H:i:s", strtotime($post->created_at)) }} updated by {{ $post->updater->alias }} {{ date("Y-m-d H:i:s", strtotime($post->updated_at)) }} ({{ $post->clicks }} views)
			</div>
			<div class="post-tags">
				<i class="fa fa-tags smaller-90 grey"></i>
				@foreach ($post->tags as $tag)
					<span class="post-tag">
						<a href="{{ URL::route('search').'?tag'.$tag->id.'=1' }}">{{ $tag->name }}</a>
					</span>
				@endforeach
			</div>
		@endif
	</div>

	<div class="col-sm-1">
		@if (count($post->badges) > 1)
			@foreach ($post->badges as $badge)
				<a href="{{ URL::route('search').'?badge'.$badge->id.'=1' }}"><img class="post-badge" src="{{ asset('uploads/'.$badge->image) }}" border="0" alt="{{ $badge->name }}"></a>
			@endforeach
		@else
			<a href="{{ URL::route('search').'?badge'.$post->badges[0]->id.'=1' }}"><img class="post-badge" src="{{ asset('uploads/'.$post->badges[0]->image) }}" border="0" alt="{{ $post->badges[0]->name }}"></a>
		@endif
	</div>


</div>
</div>
@stop

