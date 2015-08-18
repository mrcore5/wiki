@section('css')
    @parent
   <style>
    #interior-nav-list {
        padding-left:0px;
        list-style:none;
        padding-bottom:3px;
        border-bottom-width:2px;
        border-bottom-style: solid;
    }

    #interior-nav-list a {
        text-decoration:none;
        border-bottom-width:2px;
        border-bottom-style: solid;
        padding-bottom:10px;
    }

    #interior-nav-list li {
        display:inline-block;
        text-align:center;
        padding:5px;
        width:120px;
    }

    #interior-nav-list .fa {
        font-size:16px;
    }

    </style>
@stop

@section('interior-navigation')
    @if (isset($interiorNav))
        <ul id="interior-nav-list" class="theme-border-color-1">
            @foreach ($interiorNav as $item)
                <a href="{{ URL::to($item['url']) }}" class="theme-border-color-1 @if (isset($page->interiorKey) && $page->interiorKey == $item['key']) active @endif">
                    <li>
                        <span class="fa {{ $item['icon'] }}"></span><br />
                        {!! $item['display'] !!}
                    </li>
                </a>
            @endforeach
        </ul>
    @endif
@stop

