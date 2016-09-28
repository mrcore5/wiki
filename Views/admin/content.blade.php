@extends('admin.layout')

@section('title')
    Administration - {{ $data->name }}s
@stop

@section('admin-content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
              <div class="panel-heading">
                <h3 class="panel-title" style="display:inline;">{{ $data->name }}s</h3>
                <div id="panel-right">
                    <button id="btn-add" class="btn btn-primary btn-sm">Add</button>
                    <div id="search-table" style="display:inline;float:right"></div>
                </div>
              </div>
              <div class="panel-body" style="padding:0px;">
                  <table class="table table-striped table-condensed" id="data-table" data-url="{{ $data->dataUrl }}">
                </table>
              </div>
            </div>
        </div>
    </div>

    <div id="form-modal" class="modal fade">
          <div class="modal-dialog">
              {!! Form::open(['data-remote', 'id' => 'admin-form', 'name' => 'admin-form', 'files' => true]) !!}
            <div class="modal-content">
                  <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">Modify {{ $data->name }}</h4>
                  </div>
                  <div class="modal-body">
                      <div id="delete-msg" class="alert alert-danger" style="display:none;">Are you sure you want to delete this {{ $data->name }}?</div>
                      @include('admin/partials/'.$data->partial)
                  </div>
                  <div class="modal-footer">
                      {!! Form::hidden('id', 0, array('id' => 'form-ID')) !!}
                      {!! Form::hidden('form-action', '', array('id' => 'form-action')) !!}
                      {!! Form::hidden('_method', 'POST', array('id' => '_method')) !!}
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    {!! Form::submit('Submit', ['name' => 'submit', 'class' => 'btn btn-primary']) !!}
                </div>
            </div>
            {!! Form::close() !!}
          </div>
    </div>

@stop


@section('script')
    @parent

@stop