@extends('layout')

@include('post.header')

@section('title')
    {{ Layout::title() }}
@stop

@section('content')
    @if (isset($post))
        @if (Layout::modeIs('raw'))
            {!! $post->content !!}
        @else
            <form method="post">

                @if ($post->type->constant == 'app')

                    <div class="post-content {{ $post->format->constant }}">
                        {!! $post->content !!}
                    </div>

                @else

                    <div class="panel">
                        <div class="panel-body">

                            @if ($post->type->constant == 'doc')
                                @yield('post-header')
                            @endif

                            <div class="post-content {{ $post->format->constant }}">
                                {!! $post->content !!}
                            </div>
                        </div>
                    </div>

                @endif

            </form>
        @endif
    @endif
@stop

@section('script')
    <script>
        @if (isset($post))
            @if ($post->hasPermission('write', $post->perms))
                // Hotkey Ctrl+Enter edits post
                $(document).bind('keydown', 'ctrl+return', function() {
                    window.location = '{{ URL::route("edit", array("id" => $post->id)) }}';
                });

                $(document).bind('keydown', 'meta+return', function() {
                    window.location = '{{ URL::route("edit", array("id" => $post->id)) }}';
                });
            @endif
        @endif
    </script>
@stop
