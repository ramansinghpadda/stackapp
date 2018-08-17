<div class="form-group{{ isset($errors['name'][0]) ? ' has-error' : '' }}">
    {!! Form::label('name', 'Your name:', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('name', null,array('class' => 'form-control', 'required')) !!} {{ isset($errors['name'][0]) ? $errors['name'][0] : ''}}
    </div>
</div>

<div class='form-group{{ isset($errors['email'][0]) ? ' has-error' : '' }}'>
    {!! Form::label('email', 'Email address:', ['class' => 'col-md-4 control-label']) !!}
    <div class="col-md-6">
        {!! Form::text('email', null,array('class' => 'form-control', 'required')) !!} {{ isset($errors['email'][0]) ? $errors['email'][0] : ''}}
    </div>
</div>
<div class='form-group'>
    <div class="col-md-6 col-md-offset-4">
        {!! Form::submit($submitButtonText, ['class' => 'btn btn-primary']) !!}
    </div>
</div>