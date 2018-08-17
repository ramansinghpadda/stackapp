@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <h1 class="panel-heading">Create an organization</h1>
                <div class="panel-body">
                {!! Form::model($organization, ['method' => 'POST', 'class' => 'form-horizontal', 'route' => ['organization.save']]) !!}
                @include('organizations._form')
                {!! Form::close() !!}
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
