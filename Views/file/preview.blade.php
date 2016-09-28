@include('file.menubar')
@include('file.navbar')

<div class="fm-content">

<!-- The Gallery as lightbox dialog, should be a child element of the document body -->
<div id="blueimp-gallery" class="blueimp-gallery">
    <div class="slides"></div>
    <h3 class="title"></h3>
    <a class="prev">‹</a>
    <a class="next">›</a>
    <a class="close">×</a>
    <a class="play-pause"></a>
    <ol class="indicator"></ol>
</div>

    @if(!$params['nomenu'])
        @yield('menubar')
    @endif

    @if(!$params['nonav'])
        @yield('navbar')
    @endif

    <div class="fm-files">
        @foreach ($dir->getChildren() as $child)
            <div class="fm-preview">
                @if (isset($child->url))
                    {{-- Directory --}}
                    <div class="fm-preview-icon">
                        <a href="{{ $url->getLink($child->getName()) }}">
                            <i class="icon-folder-close icon-5x grey"></i>
                            <div>{{ $child->getName() }}</div>
                        </a>
                    </div>
                @else
                    {{-- File --}}
                    <div class="fm-preview-name">
                        <a href="{{ $url->getLink($child->getName()) }}" target="_blank" title="test">
                            @if ($child->isImage())
                                <img src="{{ $url->getLink($child->getName()) }}">
                            @else
                                <i class="icon-file-text-alt green bigger-120"></i>
                            @endif
                            <div>{{ $child->getName() }}</div>
                        </a>
                    </div>
                @endif
            </div>
        
        @endforeach
    </div>
</div>

