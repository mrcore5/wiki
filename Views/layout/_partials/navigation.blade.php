@section('css')
    @parent
   <style>
    #nav-title {
        font-size:24px;
        border-bottom:1px solid #dddddd;
        color:#336699;
        margin-bottom:5px;
        margin-top:0px;
        font-weight:bold;
    }

    #nav-list {
        list-style:none;
        padding:0px;
        background-color:#fcfcfc;
        border-right:2px solid #eeeeee;
        border-bottom:2px solid #eeeeee;
        border-bottom-right-radius:10px;
    }

    #nav-list .nav-item {
        color:#444444;
        padding:10px;
        cursor: pointer;
    }

    #nav-list a {
        display:block;
    }

    #nav-list .nav-item:hover, #nav-list .nav-item.active {
        border-right:4px solid #336699;
        background-color:#f7f7f7;
        font-weight:bold;
        border-top-left-radius:7px;
        border-bottom-left-radius:7px;
    }

    #nav-list a:hover {
        text-decoration:none;
    }

    .subnav-container {
        padding:5px;
    }

    .subnav-list {
        font-size:12px;
        list-style:none;
        padding:0px;
        margin:0px;
        border:1px solid #f4f4f4;
        background-color:#f7f7f7;
        border-radius:5px;
    }

    .subnav-list li {
        margin-bottom:5px;
        margin-left:4px;
        border:0px;
        padding:5px;
        cursor: pointer;
    }

    .subnav-list li:hover {
        border-left:4px solid #336699;
        background-color:#eeeeee;
        margin-left:0px;
        border-top-right-radius:7px;
        border-bottom-right-radius:7px;
    }

     .subnav-list li.active {
        border-left:4px solid #336699;
        background-color:#eeeeee;
        margin-left:0px;
        border-top-right-radius:7px;
        border-bottom-right-radius:7px;
     }

     #app-navigation-uncollapsed, #app-navigation-collapsed {
        font-size:20px;
        color:#336699;
        font-weight:bold;
        z-index:1000;
        cursor:pointer;
     }

     #app-navigation-uncollapsed {
        display:inline;
        float:right;
        color:#cccccc;
     }

     #app-navigation-collapsed {
        display:none;
        padding-right:5px;
        color:#888888;
     }

    </style>
@stop

@section('navigation')
    <div id="navigation">
        <h1 id="nav-title">
            {{ $navTitle }}
            <div id="app-navigation-uncollapsed"><i class="fa fa-bars"></i></div>
        </h1>
        <ul id="nav-list">
            @foreach ($navItems as $item)
                <li>
                    <div class="nav-item @if ($page->key == $item['key']) active @endif">
                    <a @if (isset($item['url'])) href="{{ URL::to($item['url']) }}" @endif data-toggle="collapse" data-target="#toggleNav-{{$item['key']}}">
                    {!! $item['display'] !!}
                    </a>
                    </div>
                    @if (isset($item['subnav']))
                    <div class="subnav-container collapse @if ($page->key == $item['key']) in @endif" id="toggleNav-{{$item['key']}}">
                        <ul class="subnav-list">
                        @foreach ($item['subnav'] as $subnav)
                            <li @if (isset($page->subkey) && $page->subkey == $subnav['key']) class="active" @endif>
                                <a href="{{ URL::to($subnav['url']) }}">{{ $subnav['display'] }}</a>
                            </li>
                        @endforeach
                        </ul>
                    </div>
                    @endif
                </li>
            @endforeach
        </ul>
    </div>
@stop


@section('scripts')
@parent
    <script type="text/javascript">
        var templateData = JSON.parse(sessionStorage.getItem("template"));

        if (templateData != null && templateData['navigationCollapsed'] != null && templateData['navigationCollapsed'] == true) {
            collapseNavigation();
        }

        function collapseNavigation()
        {
            $('#app-layout-navigation').hide();
            $('#app-layout-content').removeClass('col-md-10').addClass('col-md-12').attr('style', 'padding-left:20px');
            $('#app-navigation-collapsed').css('display', 'inline');

            if (templateData == null) {
                var templateData = {};
            }
            templateData['navigationCollapsed'] = true;
            sessionStorage.setItem("template", JSON.stringify(templateData));
        }

        function unCollapseNavigation()
        {
            $('#app-layout-navigation').show();
            $('#app-layout-content').removeClass('col-md-12').addClass('col-md-10');
            $('#app-navigation-collapsed').hide();

            if (templateData == null) {
                var templateData = {};
            }

            templateData['navigationCollapsed'] = false;
            sessionStorage.setItem("template", JSON.stringify(templateData));
        }

        $('#app-navigation-uncollapsed').click(function() {
            collapseNavigation();
        });

        $('#app-navigation-collapsed').click(function() {
           unCollapseNavigation();
        });
    </script>
@stop