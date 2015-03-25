<!-- Admin Roles Partial -->
<div class="row">      				
	<div class="col-md-6">
		{!! Form::label('Name', 'Name') !!}
		{!! Form::text('name', '', array('id' => 'form-Name', 'class' => 'required form-control')) !!}
	</div>
	<div class="col-md-6">
		{!! Form::label('Constant', 'Constant') !!}
		{!! Form::text('constant', '', array('id' => 'form-Constant', 'class' => 'required form-control')) !!}
	</div>	
</div>	