@section('css')
    @parent
    <style type="text/css">
        #spinner {
            display:none;
        }

        #spinner-overlay {
            opacity:    0.3;
            background: #000;
            width:      100%;
            height:     100%;
            z-index:    10;
            top:        0;
            left:       0;
            position:   fixed;
        }

        #spinner-content {
            width: 300px;
            z-index:    10;
            top:        20%;
            left:       40%;
            position:   fixed;
            text-align:center;
        }

        #spinner-text {
            color:#ffffff;
            padding-top:15px;
            font-weight:bold;
            font-size:18px;
        }
    </style>
@stop

<!-- loading spinner -->
<div id="spinner">
    <div id="spinner-overlay"></div>
    <div id="spinner-content">
        <img src="{{ asset('images/ajax-loader2.gif') }}" />
        <div id="spinner-text"></div>
    </div>
</div>

@section('scripts')
    @parent
    <script type="text/javascript">
        var spinner = new function() {

            this.text = 'Processing, please wait.';

            this.init = function(text) {
                if (text && text != '') {
                    this.text = text;
                }
                // display spinner
                $('#spinner-text').html(this.text);
                $('#spinner').show();
            }

            this.clear = function() {
                // remove spinner
                $('#spinner').hide();
            }
        }
    </script>
@stop