@section('main-menu')
    @if (isset($post))
    <li class="dropdown">
        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <i class="fa fa-bars" style="font-size: 130%"></i>
        </a>

        <ul id="post-menu" class="dropdown-menu">
            @if (@$post->hasPermission('write'))
            <li>
                <a href="{{ URL::route('edit', array('id' => $post->id)) }}" title='Edit (Ctrl+Enter)'>
                    <i class="fa fa-edit"></i>
                    Edit Post
                </a>
            </li>
            <li class="divider"></li>
            @endif
        
            <li>
                <a href="{{ URL::route('file').'/'.$post->id }}" target="_blank">
                    <i class="fa fa-folder-o"></i>
                    Post Files
                </a>
            </li>

            <li>
                <a href="#" title="Info (Ctrl+I)">
                    <i class="fa fa-exclamation-circle"></i>
                    Post <sup>#</sup>{{ $post->id }} Info
                </a>
            </li>


            <li class="divider"></li>

            <li>
                <a href="?simple" target="_blank">
                    <i class="fa fa-file-o" style="padding-right: 0px"></i>
                    View Simple
                </a>
            </li>

            <li>
                <a href="?raw" target="_blank">
                    <i class="fa fa-html5"></i>
                    View Raw
                </a>
            </li>

            <!--<li>
                <a href="?source" target="_blank">
                    <i class="fa fa-file-code-o" style="padding-right: 5px"></i>
                    View Source
                </a>
            </li>-->
        </ul>
    </li>
    @endif



@stop