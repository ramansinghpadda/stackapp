@extends('layouts.app')
@section('content')  

<div class="container">
	@include('admin.service_category.partials.navigation')
        <h1 class="title">Details</h1>
        <table class="table">
		    <tbody>
		    	<tr><th>Name</th><td>{{ @$ServiceCategoryList->name }}</td></tr>
		    	<tr><th>Company</th><td>{{ @$ServiceCategoryList->company }}</td></tr>
		    	<tr><th>Description</th><td>{{ @$ServiceCategoryList->description }}</td></tr>
		        <tr><th>Domain</th><td>{{ @$ServiceCategoryList->domain }}</td></tr> 
		        <tr><th>Url</th><td><a target="_blank" href="{{$ServiceCategoryList->url}}">{{ @$ServiceCategoryList->url }}</a></td></tr>
		        <tr><th>category Name</th><td>{{ @$ServiceCategoryList->category->name }}</td></tr>
		        <tr><th>Services Key Name</th><td>{{ @$ServiceCategoryList->service_key }}</td></tr>
		        <tr><th>Status</th> <td>{{ (@$ServiceCategoryList->statusID == 1) ? 'active' : 'pending/archived' }}</td></tr>
		        <tr><th>User</th> <td>{{ @$ServiceCategoryList->user->email }}</td></tr>
		        <tr><th>Parent Name</th><td>{{ @$ServiceCategoryList->parent->name }}</td></tr>
		    </tbody>
	  </table> 

</div>


@stop()