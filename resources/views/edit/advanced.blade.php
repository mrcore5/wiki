@section('advanced')
{!! Form::open(array(
    'id' => 'advanced-form',
    'class' => 'form-horizontal',
    'novalidate' => 'novalidate'
)) !!}

<div class="row">
    <div class="col-lg-12">
        <div class="page-header">
            <h3>Default Route</h3>
        </div>
    </div>
</div>

<div class="row">
    <div class="form-group">
        {!! Form::label('default-slug', 'Static Route Slug', array(
            'class' => 'col-sm-3 control-label'
        )) !!}
        <div class="col-sm-6">
            {!! Form::text('default-slug', $route->static ? $defaultSlug : '', array(
                'class' => 'form-control',
            )) !!}
            <span class="help-block">
                Example: <b>/app/myapp</b> or <b>/doc/help/mydoc</b> or <b>/myapp</b><br />
                A static route allows you to define a custom url for this post<br />
                To remove the static route and revert back to the post id/slug url, leave it blank
            </span>
        </div>
    </div>
</div>

<div class="row">
    <div class="form-group">
        {!! Form::label('symlink', 'Symlink', array(
            'class' => 'col-sm-3 control-label'
        )) !!}
        <div class="col-sm-6 checkbox">
            <label>
                {!! Form::checkbox('symlink', 'symlink', $post->symlink, array(
                    'id' => 'symlink',
                )) !!}
                Enable Symlink Folder Structure from Static Route
            </label>
            <span class="help-block">
                Static route must be defined above<br />
                Symlinks will only be created initially, not updated or managed<br />
            </span>
        </div>
    </div>
</div>






<div class="row">
    <div class="col-lg-12">
        <div class="page-header">
            <h3>Workbench/App Forge</h3>
        </div>
    </div>
</div>

<div class="row">
    <div class="form-group">
        {!! Form::label('workbench', 'Name', array(
            'class' => 'col-sm-3 control-label'
        )) !!}
        <div class="col-sm-6">
            {!! Form::text('workbench', $post->workbench, array(
                'id' => 'workbench',
                'class' => 'form-control',
            )) !!}
            <span class="help-block">
                Name must be in laravels vendor/package format (lowercase)<br />
                Example: <b>mreschke/test</b> or <b>mreschke/test-project</b><br />
                A static route must be defined in order to create or link a mrcore app<br /><br />
                To create a new app folder structure using artisan simply run:<br />
                <b>./artisan mrcore:wiki:app:make mreschke/test-project</b>
            </span>
        </div>
    </div>
</div>


<div class="row">
    <div class="form-actions">
        <div class="col-sm-offset-3 col-sm-9">
            {!! Html::decode(
                Form::button(
                    '<i class="fa fa-save"></i> Save Advanced Preferences',
                    array(
                        'name' => 'btnSaveAdv', 'id' => 'btnSaveAdv',
                        'class' => 'btn btn-success',
                        'title' => 'Save advanced preferences only, does not save post'
                    )
                )
            ) !!}
            {!! Html::decode(
                Form::button(
                    '<i class="fa fa-save"></i> Save & Publish',
                    array(
                        'name' => 'btnSaveAdvView', 'id' => 'btnSaveAdvView',
                        'class' => 'btn btn-primary',
                        'title' => 'Save advanced peferences, publish and view post'
                    )
                )
            ) !!}
        </div>
    </div>
</div>


{!! Form::close() !!}



<!--
Under construction
<pre>
advanced router optiona - multiple routes
change clicks
change creator created on
change updator updated on
enable symlink
enable static routing
button to copy selected framework template code over to post folder

delete button
revisions
</pre>
-->


@stop
