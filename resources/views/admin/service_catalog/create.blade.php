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

<div class='container'>

	@include('admin.service_catalog.partials.navigation')

	<h1>Fetch From Files</h1>
	<div class='form-group catalog-services-name'>
	{!! Form::open(array('method' => 'POST', 'url' => action('ServiceCatalogController@fetchbyURL')))!!}
    {!! Form::submit('Submit', ['class' => 'btn btn-default']) !!}	
	{!! Form::close() !!}
	</div>
	
	<h1>Insert By Url</h1>
	<div class='form-group catalog-services-name'>
	{!! Form::open(array('method' => 'POST', 'url' => action('ServiceCatalogController@getservicebyURL')))!!}
	{!! Form::hidden('act', "add" , ['class' => 'form-control form-url-bar ']) !!}
	{!! Form::text('url', null, ['class' => 'form-control form-url-bar ', 'placeholder'=> 'Example: https://www.facebook.com']) !!}
    {!! Form::submit('Fetch & Save', ['class' => 'btn btn-default']) !!}	
	{!! Form::close() !!}
	</div>
	
	<h1>Add service</h1>
	{!! Form::open(['url' => action('ServiceCatalogController@store'), 'method' => 'POST','files' => true, 'class'=> 'content form-horizontal'])!!}
		@include('admin.service_catalog.partials.form', ['submitButtonText' => 'Add Services', 'errors' => $err])
	{!! Form::close() !!}
</div>
@stop