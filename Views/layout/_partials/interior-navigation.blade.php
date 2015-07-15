@section('css')
    @parent
   <style>
    #interior-nav-list {
        padding-left:0px;
        list-style:none;  
        padding-bottom:3px;
        border-bottom:2px solid #dddddd;   
    }

    #interior-nav-list a {
        text-decoration:none;
        border-bottom:2px solid #dddddd;          
        padding-bottom:10px;       
    }

    #interior-nav-list li {
        display:inline-block;
        text-align:center;
        padding:5px;
        width:120px;        
        color:#777777;  
    }

    #interior-nav-list a:hover, #interior-nav-list a.active {
        border-bottom:2px solid #336699;
    }

    #interior-nav-list a:hover li, #interior-nav-list a.active li {
        color: #336699;
    }

    #interior-nav-list .fa {
        font-size:16px;
    }    

    </style>
@stop

@section('interior-navigation')
    @if (isset($interiorNav))
        <ul id="interior-nav-list">
            @foreach ($interiorNav as $item)
                <a href="{{ URL::to($item['url']) }}" @if (isset($page->interiorKey) && $page->interiorKey == $item['key']) class="active" @endif>
                    <li>
                        <span class="fa {{ $item['icon'] }}"></span><br />
                        {!! $item['display'] !!}
                    </li>
                </a>    
            @endforeach
        </ul>
    @endif
@stop

