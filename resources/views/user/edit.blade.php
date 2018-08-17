@extends('layouts.app') @section('content')


<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">
                    @if (count($errors) > 0)
                    <?php $err = $errors->getMessages();
  
    ?> @else
                    <?php $err = ''; ?> @endif @if(Session::has('flash_message'))
                    <div class="alert alert-success">
                        <button type="button" class="close" data-dismiss="alert">×</button> {{ Session::get('flash_message') }}
                    </div>
                    @endif

                    <h1>Edit profile</h1>
                </div>
                <div class="panel-body">
                    {!!Form::model( $user, ['route' => ['user-update', $user] ,'files' => true, 'method' => 'put', 'role' => 'form', 'class' => 'form-horizontal'] ) !!} @include('user._form-edit', ['submitButtonText' => 'Update' , 'errors' => $err]) {!! Form::close()
                    !!}
                </div>
            </div>
        </div>
    </div>
</div>
@stop