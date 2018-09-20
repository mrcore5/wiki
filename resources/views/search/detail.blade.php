@extends('search.layout')

@section('results')
    @foreach ($posts as $result)
        <div class="search-post">
            <div class="search-detail-container hvr-glow theme-border-color-1 panel">
                <div class="search-detail-image theme-bg-color-4 theme-border-color-1">
                    <img src="{{ asset('uploads/'.Mrcore\Auth\Models\User::find($result->created_by)->avatar) }}" class="theme-border-color-2"  />
                </div>
                <div style="position:relative;display:table-cell;width:100%">
                    <div class="search-detail-content">
                        <span style="font-size:14px"><a href="{{ Mrcore\Wiki\Models\Post::route($result->id) }}">{{ $result->title }}</a></span>
                        <div class="search-post-url">
                            @if ($result->type_id == 2)
                                <a href="{{ URL::route('search').'?type=page' }}">
                                    <i class="fa fa-globe text-success"></i>
                                </a>
                            @elseif ($result->type_id == 3)
                                <a href="{{ URL::route('search').'?type=app' }}">
                                    <i class="fa fa-sun-o text-danger"></i>
                                </a>
                            @else
                                <a href="{{ URL::route('search').'?type=document' }}">
                                    <i class="fa fa-file-text-o text-primary"></i>
                                </a>
                            @endif
                            {{ Mrcore\Wiki\Models\Post::route($result->id) }}
                        </div>
                        <div style="font-size:10px;padding:5px;">{{ $result->teaser }}</div>
                    </div>

                    @if (Auth::check())
                        <div class="search-detail-permissions theme-bg-color-1 theme-border-color-1 ">
                        @if (sizeOf($result->permissions) > 0)
                            @foreach ($result->permissions as $key => $permission)
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
                    @endif
                </div>
                <div class="search-detail-bottom theme-bg-color-3 theme-border-color-1">
                    <span class="search-post-badges">
                    @foreach ($result->badges as $badge)
                        <a href="{{ URL::route('search').'?badge='.$badge->name }}">
                            <img src="{{ asset('uploads/'.$badge->image) }}">
                        </a>
                    @endforeach
                    </span>
                    <span class="theme-text-color-1">|</span>
                    <span class="search-post-tags">
                        <i class="fa fa-tags"></i>
                        @foreach ($result->tags as $tag)
                            <a href="{{ URL::route('search').'?tag='.$tag->name }}" class="search-post-tag">{{ $tag->name }}</a>
                        @endforeach
                    </span>
                    <div style="float:right;">
                    <span class="search-post-creator">
                            post #{{ $result->id }}
                            by <b>{{ Mrcore\Auth\Models\User::find($result->created_by)->alias }}</b>
                            {{ date("M jS Y", strtotime($result->created_at)) }}
                            ({{ $result->clicks }} views)
                        </span>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
@stop
