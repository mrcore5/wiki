@section('menubar')

<div class='fm-menubar pull-right'>


    <ul class="fm-menubar-items">
        <li class="fm-menubar-item">

            @if ($url->isWritable())
                <form enctype="multipart/form-data" id="upload-form" role="form" method="POST" action="" >
                    <span class="fm-upload-btn btn btn-sm btn-success" id='upload-btn'>
                        <i class="fa fa-upload"></i> Upload File <input id="upload" name="upload" type="file">
                    </span>
                </form>
            @endif
        </li>
    </ul>

</div>

@stop

