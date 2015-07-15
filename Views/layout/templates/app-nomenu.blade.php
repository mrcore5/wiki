@include('_partials.show-help')

@section('css')
    @parent    
    <style>
    #page-title {
        font-size:24px;
        color:#666666;
        border-bottom:1px solid #dddddd;
        color:#336699;
        margin-bottom:5px;
        margin-top:0px;
        font-weight:bold;
    }

    #page-subtitle {
        margin:0px;
        font-size:18px;
        padding:10px;      
        color:#666666;
        background-color:#fdfdfd;        
        border-bottom:1px solid #eeeeee;
        margin-bottom:10px;
    }

    #page-title-text {
        display:inline;
        float:right;
        color:#666666;
        font-size:14px;
    }

    #page-help {
        display:inline;
        float:right;
        font-weight:bold;
        width:25px;
    }

    #page-content {
        border-top:1px solid #dddddd;
        padding-top:10px;
    }

    /** action bar css here for now */

    .action-bar {
        text-align:right;
    }

    .action-bar-items {
        list-style: none;
    }

    .action-item {        
        display:inline-block;   
    }

    .action-item a, .action-item div {        
        display:inline-block; 
        margin-left:5px;  
    }
    
    .section-bar {
        margin:0px;
        border-left:4px solid #336699;
        background-color:#fbfbfb;
        padding:7px;
        border-bottom:1px solid #dddddd;
        margin-bottom:15px;
        color:#555555;
    }
    </style>
@stop

@section('template')
    @if (!isset($useContainer) || $useContainer)    
        <div class="container">
    @endif
        <div class="row">
            <div class="col-md-12">
                <!-- Page Content -->
                <div id="main-content">
                    <h1 id="page-title">{{ $page->title }} <div id="page-title-text">{{ $page->displayText or '' }}</div></h1>
                    @if (isset($page->subtitle))
                        <h4 id="page-subtitle">{{ $page->subtitle }}</h4>
                    @endif
                    <!-- Content -->     
                    @yield('wb-content')
                </div>
            </div>
        </div>   
      @if (!isset($useContainer) || $useContainer)   
        </div>
    @endif    

    @yield('show-help-modal')
@stop