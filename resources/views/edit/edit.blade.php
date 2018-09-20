@extends('layout')

@include('edit.css')
@include('edit.script')
@include('edit.files')
@include('edit.organization')
@include('edit.permissions')
@include('edit.advanced')

@section('title')
    Edit {{ Layout::title() }}
@stop

@section('content')
{{-- Form::open(array('route' => array('updatePost', $post->id))) --}}
{{-- Form::open(array('route' => array('updatePost', $post->id),'id' => 'validation-form','class' => 'form-horizontal','novalidate' => 'novalidate')) --}}
<div class="form-horizontal">

    <div class="alert-box">
        <div id="alert" class="alert alert-block alert-warning"></div>
    </div>

    <div id="tabs">
        <ul class="nav nav-tabs" id="tab-tabs">
            <li class="active">
                <a data-toggle="tab" href="#content" title="Edit post">
                    <i class="fa fa-edit"></i>
                </a>
            </li>
            {{--<li>
                <a data-toggle="tab" href="#files" title="Post fles">
                    <i class="fa fa-folder"></i>
                </a>
            </li>--}}
            <li>
                <a data-toggle="tab" href="#organization" title="Post title and organization">
                    <i class="fa fa-tags"></i>
                </a>
            </li>
            <li>
                <a data-toggle="tab" href="#permissions" title="Post sharing and security">
                    <i class="fa fa-lock"></i>
                </a>
            </li>
            @if (Auth::admin())
            <li>
                <a data-toggle="tab" href="#advanced" title="Advanced settings">
                    <i class="fa fa-cog"></i>

                </a>
            </li>
            @endif




            <li class="dropdown">
                <a class="dropdown-toggle" data-toggle="dropdown" href="#">
                    <i class="fa fa-bars"></i>
                </a>
                <ul id="edit-menu" class="dropdown-menu">
                    {{--<li>
                        <a title="Help" id="btnHelp">
                            <i class="fa fa-question-circle text-success"></i>
                            Help
                        </a>
                    </li>--}}
                    <li>
                        <a title="Markup Cheatsheet" id="btnCheat">
                            <i class="fa fa-question-circle text-warning"></i>
                            Cheatsheet
                        </a>
                    </li>
                    <li>
                        <a title="Ace Editor Keyboard Shortcuts (? or Cmd+Alt+H on mac)" id="btnAceKeys">
                            <i class="fa fa-keyboard-o text-info"></i>
                            Editor Shortcuts
                        </a>
                    </li>
                    <li>
                        <a title="Ace Editor Options (Ctrl+, or Cmd+, on mac)" id="btnAceSettings">
                            <i class="fa fa-gears text-primary"></i>
                            Editor Settings
                        </a>
                    </li>
                    {{--<li>
                        <a title="Info (Ctrl+I)" id="btnInfo">
                            <i class="fa fa-exclamation-circle"></i>
                            Info
                        </a>
                    </li>--}}

                    <li class="divider"></li>

                    <li>
                        <a title="Discard changes (Ctrl+Shift+Esc or Cmd+Esc on mac)" id="btnCancel">
                            <i class="fa fa-times text-danger"></i>
                            Discard changes
                        </a>
                    </li>
                    <li class="divider"></li>

                    <li>
                        <a title="Publish and continue editing (Ctrl+S or Cmd+S on mac)" id="btnPublish">
                            <i class="fa fa-save text-success"></i>
                            Publish
                        </a>
                    </li>
                    <li class="divider"></li>

                    <li>
                        <a title="Publish and view (Ctrl+Shift+S or Ctrl+Enter or Cmd+Shift+S or Cmd+Enter on mac)" id="btnPublishShow">
                            <i class="fa fa-eye text-info"></i>
                            Publish and View
                        </a>
                    </li>

                </ul>
            </li>

        </ul>

        <div class="tab-content" id="tab-content">
            <div id="content" class="tab-pane in active tab-content-post">
                {{-- Form::textarea('content', $post->content, array('id' => 'editor', 'style' => 'width: 100%;')) --}}
                <div id="editor">{{{ $post->content }}}</div>
            </div>

            {{-- ===== Files Tab ===== --}}
            {{--<div id="files" class="tab-pane pad">
                @yield('files')
            </div>--}}

            {{-- ===== Organization Tab ===== --}}
            <div id="organization" class="tab-pane pad">
                @yield('organization')
            </div>

            {{-- ===== Permissions Tab ===== --}}
            <div id="permissions" class="tab-pane pad">
                @yield('permissions')
            </div>

            {{-- ===== Advanced Tag ===== --}}
            @if (Auth::admin())
                <div id="advanced" class="tab-pane pad">
                    @yield('advanced')
                </div>
            @endif

        </div>

    </div>


    @if (count($uncommitted) > 0)
    <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close btnCancelRevision" aria-hidden="true">&times;</button>
                    <h4 class="modal-title red" id="myModalLabel"><b>NOTICE:</b> Uncommitted Revisions</h4>
                </div>
                <div class="modal-body">
                    <p><b>There are {{ count($uncommitted) }} uncommitted revisions for this post.</b></p>
                    <p>This either means <span class="label label-danger">someone is currently editing this post</span> or someone was editing this post but forgot to click publish/discard.  Look at the revision date and use your own logic to solve this problem.</p>

                    <ul>
                    @foreach ($uncommitted as $revision)
                        <li>
                            Revision by {{ $revision->creator->alias }} on {{ $revision->created_at }} <b>({{ (new Carbon\Carbon($revision->created_at))->diffForHumans() }})</b>
                            <div class="revision"><pre>{!! $revision->diffHtml !!}</pre></div>
                            <p>
                                <button class="btn btn-danger" id="btnRevisionContinue" data-id="{{ $revision->id }}" title="Continue editing this revision and delete all other uncommitted revision">
                                    <i class="fa fa-save"></i> Edit this Revision and Delete all Others
                                </button>
                            </p>
                        </li>
                    @endforeach
                    </ul>


                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary btnCancelRevision">
                        <i class="fa fa-times"></i> Cancel Edit
                    </button>
                    {{--<button type="button" class="btn btn-primary">Save changes</button>--}}
                </div>
            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
    </div><!-- /.modal -->
    @endif

</div><!-- /.form-horizontal -->
@stop

@section('script')
@stop
