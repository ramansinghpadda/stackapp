@extends('layouts.app')
@section('content')
@if (count($errors) > 0)
   <?php $err = $errors->getMessages();
  
    ?>
@else
   <?php $err = ''; ?>
@endif 

@if(Session::has('flash_message'))
<div class="alert alert-success">
 <button type="button" class="close"data-dismiss="alert">×</button>
 {{ Session::get('flash_message') }}
</div>
@endif

<div class="container">
	@include('admin.service_catalog.partials.navigation')
	<h1>Edit Service</h1>
	{!!Form::model( $dataSet, ['route' => ['service_catalog.update', $dataSet['id']] ,'files' => true, 'method' => 'put', 'role' => 'form', 'class' => 'content form-horizontal'] ) !!}
         @include('admin.service_catalog.partials.form', ['submitButtonText' => 'Update Services' , 'errors' => $err])
    {!! Form::close() !!} 
    {!! Form::open(array('method' => 'POST', 'url' => action('ServiceCatalogController@getservicebyURL')))!!}
    {!! Form::hidden('id', $dataSet['id'], ['class' => 'form-control form-url-bar ']) !!}
    {!! Form::hidden('act', "edit", ['class' => 'form-control form-url-bar ']) !!}
	{!! Form::hidden('url', $dataSet['url'], ['class' => 'form-control form-url-bar ']) !!}
    {!! Form::submit('Refresh', ['class' => 'btn btn-lg btn-default form-control']) !!}	
	{!! Form::close() !!}
</div>
@stop
