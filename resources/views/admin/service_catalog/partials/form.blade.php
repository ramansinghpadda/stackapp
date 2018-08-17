
<div class='form-group catalog-services-name'>
 {!! Form::label('name', 'Name:') !!}
 {!! Form::text('name', null, ['class' => 'form-control']) !!}
 {{ isset($errors['name'][0]) ? $errors['name'][0] : ''}}
</div>

<div class='form-group'>
{!! Form::label('hex', 'hex:') !!}	
{!! Form::text('hex', null, ['class' => 'form-control']) !!}
{{ isset($errors['hex'][0]) ? $errors['hex'][0] : ''}}

</div>

<div class='form-group'>
{!! Form::label('service_key', 'Service Key:') !!}	
{!! Form::text('service_key', null, ['class' => 'form-control', 'readonly']) !!}
{{ isset($errors['service_key'][0]) ? $errors['service_key'][0] : ''}}

</div>

<div class='form-group'>
 {!! Form::label('company', 'Company Name:') !!}
 {!! Form::text('company', null, ['class' => 'form-control']) !!}
 {{ isset($errors['company'][0]) ? $errors['company'][0] : ''}}
 
</div>

<div class='form-group'>
 {!! Form::label('category', 'Category:') !!}
 {{ Form::select('catID', $items, null, [ 'placeholder' => 'Select Category', 'class' => 'form-control']
 )}}
 {{ isset($errors['catID'][0]) ? $errors['catID'][0] : ''}}

</div>

<div class='form-group'>
 {!! Form::label('description', 'Description:') !!}
 {!! Form::textarea('description', null, ['class' => 'form-control']) !!}
 {{ isset($errors['description'][0]) ? $errors['description'][0] : ''}}
 
</div>

<div class='form-group'>
 {!! Form::label('domain', 'Domain:') !!}
 {!! Form::text('domain', null, ['class' => 'form-control']) !!}
 {{ isset($errors['domain'][0]) ? $errors['domain'][0] : ''}}
 
</div>

<div class='form-group'>
 {!! Form::label('url', 'Url:') !!}
 {!! Form::text('url', null, ['class' => 'form-control']) !!}
 {{ isset($errors['url'][0]) ? $errors['url'][0] : ''}}
</div>

<div class='form-group'>
 {!! Form::label('statusID', 'statusID:') !!}
 {!! Form::text('statusID', null, ['class' => 'form-control']) !!}
 {{ isset($errors['statusID'][0]) ? $errors['statusID'][0] : ''}}
</div>

<div class='form-group'>
 {!! Form::label('is_custom', 'is_custom:') !!}
 {!! Form::text('is_custom', null, ['class' => 'form-control']) !!}
 {{ isset($errors['is_custom'][0]) ? $errors['is_custom'][0] : ''}}
</div>

<div class='form-group'>
 {!! Form::label('parent-service', 'Parent Service:') !!}
 {{ Form::select('parentID', $dataKey, null, ['placeholder' => 'Select Parent Service ', 'class' => 'form-control']
 )}}
</div>

<div class='form-group'>
 {!! Form::submit($submitButtonText, ['class' => 'btn btn-lg btn-success form-control']) !!}
</div>
