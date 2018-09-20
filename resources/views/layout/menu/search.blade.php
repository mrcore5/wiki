@section('search-menu')

<li class="dropdown yamm-fullwidth">
    <a href="#" id="search-button" class="dropdown-toggle" data-toggle="dropdown">
        <i class="fa fa-search text-danger" title="search"></i> <span class="text-danger">Search</span>
    </a>
    <ul class="dropdown-menu" id="search-menu">
        <li>
            <div class="yamm-content">
                    <div id="searchbox">
                        <div class="loading-icon">
                            <i class="fa fa-spinner fa-spin"></i>
                            Loading Search ...
                        </div>
                    </div>
            </div>
        </li>
    </ul>
</li>

@stop

@section('scripts')
    @parent
    <script type="text/javascript">
        // Search
        var searchLoaded = false;
        $('#search-button').click(function() {
            setTimeout(function() { $('input[name="search"]').focus() }, 100);
            if (!searchLoaded) {
                searchLoaded = true;            
                $.get(
                    "{{ URL::route('searchMenu') }}"
                ).done(function(response) {
                    $('#searchbox').html(response);
                });
            }
        });

        //Hotkey / click search button
        $(document).bind('keyup', '/', function() {
            if (typeof onSearch === 'undefined') {
                $('#search-button').click();
            }
            $("#search").focus();
        });


        // Hotkey ESCAPE closes main search menu
        $(document).bind('keyup', 'esc', function() {
            if ($('#search-menu').is(":visible")) {
                $('#search-button').click();
            }
        });
    </script>
@stop