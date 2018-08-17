@extends('layouts.app')
@section('content')  

<div class="container">
	@include('admin.service_catalog.partials.navigation')
        <h1>Details</h1>
        <table class="table">
		    <tbody>
		    	<tr><th>Name</th><td>{{ @$catalogList->name }}</td></tr>
		    	<tr><th>Company</th><td>{{ @$catalogList->company }}</td></tr>
		    	<tr><th>Description</th><td>{{ @$catalogList->description }}</td></tr>
		        <tr><th>Domain</th><td>{{ @$catalogList->domain }}</td></tr> 
		        <tr><th>Url</th><td><a target="_blank" href="{{@$catalogList->url}}">{{ @$catalogList->url }}</a></td></tr>
		        <tr><th>category Name</th><td>{{ @$catalogList->category->name }}</td></tr>
		        <tr><th>Services Key Name</th><td>{{ @$catalogList->service_key }}</td></tr>
		        <tr><th>Status</th> <td>{{ (@$catalogList->statusID == 1) ? 'active' : 'pending/archived' }}</td></tr>
		        <tr><th>User</th> <td>{{ @$catalogList->user->email }}</td></tr>
		        <tr><th>Parent Name</th><td>{{ @$catalogList->parent->name }}</td></tr>
		    </tbody>
	  </table> 
</div>


@stop()