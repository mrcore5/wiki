@section('utilities')
    @parent
    <div class="modal fade" id="show-help-modal" role="dialog" aria-labelledby="myModalTitle" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header dyna-accent-border">
                    <h4 class="modal-title" id="myModalLabel" style="border:0px;">{{ $showHelp->title or 'Help & Information'}}</h4>
                </div>
                <div class="modal-body" style="padding:20px">
                    @yield('show-help-content')
                </div>
                <div class="modal-footer">
                    <button id="btn-done" type="button" class="btn btn-primary">Done</button>
                </div>
            </div>
        </div>
    </div>
@stop

@section('scripts')
@parent
    <script type="text/javascript">
        function help() {
            $('#show-help-modal').modal()

            $('#show-help-modal #btn-done').click(function(e) {
                $('#show-help-modal.in').modal('hide');
            });
        }
    </script>
@stop