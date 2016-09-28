@include('file.menubar')
@include('file.navbar')

<div class="fm-content">
    @if(!$params['nomenu'])
        @yield('menubar')
    @endif

    @if(!$params['nonav'])
        @yield('navbar')
    @endif

    <div class="fm-files">
        <table class="fm-table table table-condensed table-striped table-hover {{ $params['embed'] ? 'fm-table-autowidth' : '' }}">
            <thead>
            <tr>
                <th width="5"></th>
                <th>File Name</th>
                <th width="5">Size (K)</th>
                <th width="5">Type</th>
                <th width="5">Modified</th>
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
                    <td>
                    </td>
                    <td>
                        Folder
                    </td>
                    <td>
                        {{ \Carbon\Carbon::createFromTimeStamp($child->getlastModified()) }}
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
                        File
                    </td>
                    <td>
                        {{ \Carbon\Carbon::createFromTimeStamp($child->getlastModified()) }}
                    </td>
                @endif
            </tr>
            @endforeach
            </tbody>
        {{-- var_dump($dir->getChildren()) --}}
        {{-- var_dump($url) --}}
    </div>
</div>