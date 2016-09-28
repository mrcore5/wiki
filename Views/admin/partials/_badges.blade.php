<!-- Admin Badges Partial -->
<div class="row">
    <div class="col-md-6">
        {!! Form::label('Name', 'Name') !!}
        {!! Form::text('name', '', array('id' => 'form-Name', 'class' => 'required form-control')) !!}
    </div>
    <div class="col-md-6">
        {!! Form::label('Image', 'Image') !!}
        {!! Form::file('image', array('id' => 'form-Image1', 'class' => 'form-control')) !!}
    </div>    
</div>    