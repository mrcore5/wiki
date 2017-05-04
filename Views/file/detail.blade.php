@include('file.menubar')
@include('file.navbar')

@if (!$params['embed'])
    {{-- Datatables only works in full mode, embed clashes with other datatables --}}
    <script src="{{ asset('js/dataTables.js')}} "></script>
@endif

<div class="fm-content">

    {{-- Do NOT show panel, or row or col-md-12 if embedded (in wiki page) or else it flows past the TOC --}}
    @if(!$params['embed'])
        <div class="panel panel-default">
        <div class="panel-body">
    @endif

    @if(!$params['nomenu'])
        @yield('menubar')
    @endif

    @if(!$params['nonav'])
        <div class="row">
            <div class="col-md-12 ">
                @yield('navbar')
            </div>
        </div>
    @endif

    @if(!$params['embed'])
        <div class="row">
        <div class="col-md-12">
    @endif

    <div class="fm-files">
        <table class="fm-table table table-condensed table-striped table-hover {{ $params['embed'] ?: 'dataTable' }}">
            <thead>
            <tr>
                <th width="5"></th>
                <th>File</th>
                <th width="5">KB</th>
                <th width="5">Type</th>
                <th width="5">Date</th>
                <th width="5"></th>
            </tr>
            </thead>
            <tbody>
            @foreach ($dir->getChildren() as $child)
                <tr>
                @if (isset($child->url))
                    {{-- Directory --}}
                    <td align="center">
                        <i class="fa fa-folder"></i>
                    </td>
                    <td>
                        <a href="{{ $url->getLink($child->getName()) }}">
                            {{ $child->getName() }}
                        </a>
                    </td>
                    <td></td>
                    <td>Folder</td>
                    <td>
                        {{ \Carbon\Carbon::createFromTimeStamp($child->getlastModified()) }}
                    </td>
                    <td>
                        @if ($url->isWritable() && !$params['embed'])
                            <button class="delete-btn btn btn-xs btn-danger" data-filename="{{ $child->getName() }}"><i class="fa fa-remove"></i></button>
                        @endif
                    </td>
                @else
                    {{-- File --}}
                    <td align="center">
                        <i class="fa fa-file-o"></i>
                    </td>
                    <td>
                        <a href="{{ $url->getLink($child->getName()) }}" target="_blank">
                            {{ $child->getName() }}
                        </a>
                    </td>
                    <td>
                        {{ round($child->getSize() / 1024, 2) }}
                    </td>
                    <td>
                        {{ $child->getExtension() }}
                    </td>
                    <td>
                        {{ \Carbon\Carbon::createFromTimeStamp($child->getlastModified()) }}
                    </td>
                    <td>
                        @if ($url->isWritable() && !$params['embed'])
                            <button class="delete-btn btn btn-xs btn-danger" data-filename="{{ $child->getName() }}"><i class="fa fa-remove"></i></button>
                        @endif
                    </td>
                @endif
            </tr>
            @endforeach
            </tbody>
        </table>
        {{-- var_dump($dir->getChildren()) --}}
        {{-- var_dump($url) --}}
    </div>

    @if(!$params['embed'])
        </div>
        </div>
        </div>
        </div>
    @endif

</div>

<div class="modal" id='upload-modal'>
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <center id="status"></center>
            </div>
        </div>
    </div>
</div>

<script>
    $(function() {

        /**
         * Upload File
         */
        $('#upload').change(function() {
            $('#status').html('Uploading...');
            $('#upload-modal').modal('show');
            $.ajax({
                url: '{{ '/file/'.$url->getPath() }}',
                data: new FormData($("#upload-form")[0]),
                dataType: 'json',
                async: false,
                cache: false,
                type: 'POST',
                processData: false,
                contentType: false,
                success: function(response) {
                    $('#status').html('Upload Complete!');
                    setInterval(function() {
                        $('#upload-modal').modal('hide');
                        location.reload();
                    }, 1000);
                },
                error: function(response) {
                    $('#status').html('Error');
                }
            });
        });

        /**
         * Delete File
         */
        $('.delete-btn').click(function() {
            var $this = $(this);
            var filename = $this.data("filename");

            if (confirm('Are you sure you want to delete ' + filename + '?')) {
                $('#status').html('Deleting ' + filename);
                $('#upload-modal').modal('show');
                $.ajax({
                    url: '{{ '/file/'.$url->getPath() }}',
                    data: { delete: filename },
                    dataType: 'json',
                    async: false,
                    type: 'POST',
                    success: function(response) {
                        $('#status').html(filename + ' deleted!');
                        setInterval(function() {
                            $('#upload-modal').modal('hide');
                            location.reload();
                        }, 1000);
                    },
                    error: function(response) {
                        console.log(response);
                        $('#status').html('Error');
                    }
                });

            }

        });
    });
</script>
