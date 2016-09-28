@extends('layout')

@section('content')
<style type="text/css">
    hr {
        margin-bottom: 0px;
    }

    .modal-body .row {
        padding-top:15px;
    }

    .tdID {
        width:30px;
        padding-left:10px !important;
    }

    .tdAction {
        width:50px;
    }

    .tdUser {
        width:280px;
    }

    .tdEmail {
        width:280px;
    }

    .tdUser img {
        height:40px;
        border:1px solid #dddddd;
    }

    .tdDisabled {
        text-align:center;
    }

    .tdLastLogin {
        text-align:center;
    }

    .tdUser div {
        display:inline;
        padding-left:10px;
    }

    #data-table_info, #data-table_paginate {
        padding-left:10px;
        padding-right:10px;
    }

    #admin-notify {
        position:absolute;
        top:0px;
        height:50px;
        border-radius:0px;
        display:none;
        font-weight:bold;
        font-size:14px;
    }

    .admin-menu {
        padding: 0px;
    }

    .admin-menu li {
        list-style:none;
        border-bottom:1px solid #eeeeee;
        font-size:14px;
    }

    #panel-right {        
        margin-top:-7px;
        margin-right:-10px;
        display:inline;
        float:right;
    }

    #btn-add {
        display:inline;
        float:right;
    }

    #search-table {
        padding-right:10px;
    }

    #data-table_filter .form-control {
        display:inline;
        height:34px;
    }

    #user-roles, #user-permissions {
        list-style:none;
        padding-left:15px;
    }

    #user-roles input, #user-permissions input {
        margin-right:5px;
    }


    #form-modal .modal-dialog {
        width:800px;
    }

    #user-permissions {
        -moz-column-count: 2;
        -moz-column-gap: 2.5em;
        -webkit-column-count: 2;
        -webkit-column-gap: 2.5em;
         column-count: 2;
         column-gap: 2.5em;
    }

</style>
    <div id="admin-notify" class="alert alert-success container" ></div>
    <div class="row">
        <div class="admin-menu col-md-2">
            <div class="panel panel-default">
                  <div class="panel-body">
                    <h3 style="margin:0px;">Administration</h3>
                    <hr />
                      <ul class="nav nav-pills nav-stacked admin-menu">
                        <li><a href="/admin/badge">Badges</a></li>
                         <li><a href="/admin/framework">Frameworks</a></li>
                         <li><a href="/admin/mode">Modes</a></li>
                         <li><a href="/admin/role">Roles</a></li>
                         <li><a href="/admin/tag">Tags</a></li>
                         <li><a href="/admin/type">Types</a></li>
                         <li><a href="/admin/user">Users</a></li>
                      </ul>
                </div>
            </div>
        </div>
        <div class="admin-content col-md-10">
            @yield('admin-content')
        </div>
    </div>
@stop


@section('script')
    @parent
        <script src="{{ asset('js/jquery.validate.min.js') }}"></script>
        <script type="text/javascript">

        var currentPage = 0;
        var currentFilter = '';

        /**
         * Load the table from ajax using the data-url attribute on the table-grid     
         */
        function getTableData()
        {
            if ($.fn.DataTable.isDataTable('#data-table')) {
                $('#data-table').DataTable().destroy();
            }    
            $.ajax({
                url: $('#data-table').attr('data-url')
            }).done(function(dataSet) {                    
                bindTableData(dataSet);                                                                    
            });
        }

        /**
         * Bind the returned dataSet to the table-grid
         * @param  JSON Object dataSet     
         */
        function bindTableData(dataSet)
        {
            //this gets Column Names from your DataSet
            var keys = Object.keys(dataSet[0]);
            var columnNames = [];
            $.each(keys, function(k, v) 
            {
                columnNames[k] = {};
                columnNames[k]['title'] = v; //.charAt(0).toUpperCase() + v.substring(1);;
                columnNames[k]['data'] = v;        
                columnNames[k]['class'] = 'td'+v;
            });

            $('#data-table').dataTable({
                "data": dataSet
                , "pageLength": 10
                , columns: columnNames
                , "pagingType": 'full'
                , dom:                            
                    "<'row'<'col-xs-12't>>" +
                    "<'row'<'col-xs-6'if><'col-xs-6'p>>"
                , "initComplete": function(settings, json) {
                    initTableEvents();
                     $(".dataTables_filter").each(function () {
                         $("#search-table").empty();
                        $("#search-table").append($(this));
                    });
                    oTable = $('#data-table').DataTable();
                    if (currentFilter != '') {
                        oTable.search(currentFilter).draw(false);
                    } else {
                        oTable.page(currentPage).draw(false);
                    }
                }
            });

           $('#data-table').on( 'search.dt', function () {
                   oTable = $('#data-table').DataTable();
                   if (oTable.search() != '') {
                       currentFilter = oTable.search();
                   }
            });

           $('#data-table').on( 'page.dt', function () {
                   oTable = $('#data-table').DataTable();
                   currentPage = oTable.page();
            });
        }    

        function initTableEvents() 
        {
            $('#data-table').off('click', '.btn-edit');
            $('#data-table').off('click', '.btn-delete');
            //Edit Button
            $('#data-table').on('click', '.btn-edit', function() {
                event.stopPropagation();    
                resetForm();
                populateForm(this);
                $('#form-action').val('edit');
                $('#form-modal').modal({ show: true });
                return false;
            });
            
            //Delete Button            
            $('#data-table').on('click', '.btn-delete', function(event) {
                event.stopPropagation();

                populateForm(this);                
                $('#delete-msg').show();
                $('#form-action').val('delete');
                $('#form-modal .modal-body :input').attr('disabled', true);    
                $('#form-modal').modal({ show: true });
                return false;
            });
        }

        $('#btn-add').click(function() {
            resetForm();
            $('#form-action').val('add');
            $('#form-modal').modal({ show: true });
        });

        /**
         * Bind to the Form submits to handle ajax posting
         */
        $('form[data-remote]').on('submit', function(event) {    
            event.preventDefault();    

            if ($("#admin-form").valid()) {
                var form = $(this);
                var method = 'POST';
                var url = form.prop('action');
                var notify = $('#admin-notify');

                if (form.find('#form-action').val() == 'add') {
                    $('#_method').val('POST');    
                } else if(form.find('#form-action').val() == 'edit') {
                    $('#_method').val('PUT');
                    url = url + '/' + $('#form-ID').val();
                } else {
                    $('#_method').val('DELETE');
                    url = url + '/' + $('#form-ID').val();
                }

                var formData = new FormData($(this)[0]);
                var dataFrom = new FormData(document.forms.namedItem("admin-form"));
                $.ajax({
                    type: 'POST',
                    url: url,
                    data: new FormData(document.forms.namedItem("admin-form")),
                    async: false,
                    //contentType: 'multipart/form-data',            
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        notify.html(data.message);
                        notify.removeClass('alert-danger').addClass('alert-success');
                    },
                    error: function(data) {
                        if (data.message) {
                            notify.html(data.message);
                        } else {
                            notify.html('Database Error!');
                        }
                        notify.removeClass('alert-success').addClass('alert-danger');    
                    },
                    complete: function(data) {
                        $('#form-modal').modal('hide');
                        notify.slideDown(500).delay(1500).slideUp(500);        
                        notify.html();            
                        getTableData();    
                    }
                });
            }
        });

        /**
         * Reset the Form to Defaults         
         */
        function resetForm() {
            $('#form-modal form').trigger('reset');
            $('#form-modal .modal-body :input').attr('disabled', false);
            $('#delete-msg').hide();    
        }

        /**
         * Populate the Form with Row Data
         * @param  form         
         */
        function populateForm(form) {
            var cells = $(form).closest('tr').find('td');
            $.each(cells, function(k, v)
            {
                var key = $(v).attr('class').trim().split(' ')[0].substring(2);
                var value = $(v).html();
                $('#form-modal').find('#form-'+key).val(value);
            });
        }

        /**
         * Form Input Validation
         */
        var validator = $('#admin-form').validate({
            errorElement: 'div',
            errorClass: 'help-inline',
            focusInvalid: true,
            ignore: ':hidden:not(.chzn-done)',

            rules: {
                title: {
                    required: true
                }
            },
            messages: {
                title: {
                    required: 'Title is required'
                }
            },

            highlight: function (e) {
                $(e).closest('.form-group').addClass('has-error');
            },

            success: function (e) {
                $(e).closest('.form-group').removeClass('has-error');
                $(e).remove();
            }
        });

        getTableData();

        </script>
    @stop

