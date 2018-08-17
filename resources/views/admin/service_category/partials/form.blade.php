
<div class='form-group category-name'>
 {!! Form::label('name', 'Name:') !!}
 {!! Form::text('name', null, ['class' => 'form-control']) !!}
 {{ isset($errors['name'][0]) ? $errors['name'][0] : ''}}
</div>

<div class='form-group'>
{!! Form::label('key', 'Category Key:') !!}	
{!! Form::text('key', null, ['class' => 'form-control']) !!}
{{ isset($errors['key'][0]) ? $errors['key'][0] : ''}}
</div>

<div class='form-group'>
 {!! Form::label('description', 'Description:') !!}
 {!! Form::textarea('description', null, ['class' => 'form-control']) !!}
 {{ isset($errors['description'][0]) ? $errors['description'][0] : ''}}
</div>

<div class='form-group'>
 {!! Form::label('parent-category', 'Parent category:') !!}
 {{ Form::select('parentID', $dataKey, null, ['placeholder' => 'Select Parent category ', 'class' => 'form-control']
 )}}
</div>

<div class='form-group'>
 {!! Form::label('statusID', 'statusID:') !!}
 {!! Form::text('statusID', null, ['class' => 'form-control']) !!}
 {{ isset($errors['statusID'][0]) ? $errors['statusID'][0] : ''}}
</div>

<div class='form-group'>
 {!! Form::submit($submitButtonText, ['class' => 'btn btn-lg btn-success form-control']) !!}
</div>
