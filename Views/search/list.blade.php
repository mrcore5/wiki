@extends('search.layout')

@section('results')
    <div class="search-results listview">
    @foreach ($posts as $result)
        <div class="search-post">
            <div class="search-post-title">
                <span class="search-post-badges">
                    @foreach ($result->badges as $badge)
                        <a href="{{ URL::route('search').'?badge='.$badge->name }}">
                            <img src="{{ asset('uploads/'.$badge->image) }}">
                        </a>
                    @endforeach
                </span>

                <a href="{{ Mrcore\Wiki\Models\Post::route($result->id) }}">{{ $result->title }}</a>
                <span class="search-post-creator">
                    <i class="fa fa-angle-double-right"></i>
                    post {{ $result->id }}
                    by {{ Mrcore\Auth\Models\User::find($result->created_by)->alias }}
                    {{ date("M jS Y", strtotime($result->created_at)) }}
                    ({{ $result->clicks }} views)
                </span>

            </div>
            <div class="search-post-url">
                @if ($result->type_id == 2)
                    <a href="{{ URL::route('search').'?type=page' }}">
                        <i class="fa fa-globe text-success" style="margin-left: 3px"></i>
                    </a>
                @elseif ($result->type_id == 3)
                    <a href="{{ URL::route('search').'?type=app' }}">
                        <i class="fa fa-sun-o text-danger" style="margin-left: 2px"></i>
                    </a>
                @else
                    <a href="{{ URL::route('search').'?type=document' }}">
                        <i class="fa fa-file-text-o text-primary" style="margin-left: 3px"></i>
                    </a>
                @endif
                {{ Mrcore\Wiki\Models\Post::route($result->id) }}
            </div>
            <div class="search-post-tags">
                <i class="fa fa-tags"></i>
                @foreach ($result->tags as $tag)
                    <a href="{{ URL::route('search').'?tag='.$tag->name }}" class="search-post-tag">{{ $tag->name }}</a>
                @endforeach
            </div>


        </div>
        <hr />
    @endforeach
    </div>

@stop
