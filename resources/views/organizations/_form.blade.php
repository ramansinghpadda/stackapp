@if ($errors->any())
<div class="alert alert-danger">
    <ul>
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div>    
@endif
<div class="form-group">
    <label for="name" class="col-md-3 control-label">Name:</label>
    <div class="col-md-8">
        {!! Form::text('name', null, array('placeholder' => 'Name','class' => 'form-control', 'required')) !!}
    </div>
</div>
<div class="form-group">
    <label for="url" class="col-md-3 control-label">URL:</label>
    <div class="col-md-8">
        {!! Form::text('url', null, array('placeholder' => 'URL','class' => 'form-control')) !!}
    </div>
</div>
<div class="form-group">
    <label for="industry" class="col-md-3 control-label">Industry:</label>
    <div class="col-md-8">
        {{ Form::select('industry', $industry , null, [ 'placeholder' => 'Select industry', 'class' => 'form-control', 'required']) }}
    </div>
</div>
<div class="form-group">
    <label for="size" class="col-md-3 control-label">Employees:</label>
    <div class="col-md-8">
        {{ Form::select('size', $company_size, null, [ 'placeholder' => 'Select size', 'class' => 'form-control', 'required']) }}
    </div>
</div>
<div class="form-group">
    <label for="description" class="col-md-3 control-label">Description:</label>
    <div class="col-md-8">
        {!! Form::textArea('description', null, array('placeholder' => 'Description','class' => 'form-control')) !!}
    </div>
</div>
<button type="submit" class="col-xs-12 col-md-2 col-md-offset-9 btn btn-primary">Save</button>
