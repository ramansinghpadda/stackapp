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
	@include('admin.service_category.partials.navigation')
	<h1 class="title">Add category</h1>
	{!! Form::open(['url' => action('ServiceCategoryController@store'), 'method' => 'POST','files' => true, 'class'=> 'content form-horizontal'])!!}
		@include('admin.service_category.partials.form', ['submitButtonText' => 'Add', 'errors' => $err])
	{!! Form::close() !!}
</div>
@stop
