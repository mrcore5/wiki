@extends('search.layout')

@section('results')
	<div class="search-results listview">
	@foreach ($posts as $result)
		<div class="search-post">
			<?
			$postBadges = DB::table('badges')
				->join('post_badges', 'badges.id', '=', 'post_badges.badge_id')
				->where('post_badges.post_id', $result->id)
				->select('badges.*')->get();

			$postTags = DB::table('tags')
				->join('post_tags', 'tags.id', '=', 'post_tags.tag_id')
				->where('post_tags.post_id', $result->id)
				->select('tags.*')->get();
			?>

			<div class="search-post-title">
				<span class="search-post-badges">
					@foreach ($postBadges as $badge)
						<a href="{{ URL::route('search').'?badge'.$badge->id.'=1' }}">
							<img src="{{ asset('uploads/'.$badge->image) }}">
						</a>
					@endforeach
				</span>

				<a href="{{ Mrcore\Modules\Wiki\Models\Post::route($result->id) }}">{{ $result->title }}</a>
				<span class="search-post-creator">
					<i class="fa fa-angle-double-right"></i>
					post {{ $result->id }}
					by {{ Mrcore\Modules\Wiki\Models\User::find($result->created_by)->alias }}
					{{ date("M jS Y", strtotime($result->created_at)) }}
					({{ $result->clicks }} views)
				</span>

			</div>
			<div class="search-post-url">
				@if ($result->type_id == 2)
					<a href="{{ URL::route('search').'?type2=1' }}">
						<i class="fa fa-globe text-success" style="margin-left: 3px"></i>
					</a>
				@elseif ($result->type_id == 3) 
					<a href="{{ URL::route('search').'?type3=1' }}">
						<i class="fa fa-sun-o text-danger" style="margin-left: 2px"></i>
					</a>
				@else
					<a href="{{ URL::route('search').'?type1=1' }}">
						<i class="fa fa-file-text-o text-primary" style="margin-left: 3px"></i>
					</a>
				@endif
				{{ Mrcore\Modules\Wiki\Models\Post::route($result->id) }}
			</div>
			<div class="search-post-tags">
				<i class="fa fa-tags"></i>
				@foreach ($postTags as $tag)
					<a href="{{ URL::route('search').'?tag'.$tag->id.'=1' }}" class="search-post-tag">{{ $tag->name }}</a>
				@endforeach
			</div>


		</div>
		<hr />
	@endforeach
	</div>

@stop
