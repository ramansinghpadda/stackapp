@extends('layouts.app')

@section('content')
<?php $userRoleInOrganization  = Auth::user()->userrole($organization); ?>
<div class="container">
    <div class="row">
        <div class="col-md-12">

        <div class="panel panel-default">
                <div class="panel-heading">
                    @include('organizations.navigation')
                </div>
                <div class="panel-body">
                    {!! Form::model($organization, ['method' => 'PATCH', 'class' => 'form-horizontal','route' => ['organization.store', $organization->id]]) !!}
                        @include('organizations._form')
                    {!! Form::close() !!}
                </div>
                @if($userRoleInOrganization && $userRoleInOrganization->canAccess('delete-organization'))
                <div class="panel-footer text-center">
                    <p>Important! Clicking the button below will immediately delete the organization</p>
                    <form method="POST" action="{{ route('organization-delete',$organization) }}" onsubmit="return confirmDelete()">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <button class="btn btn-sm btn-danger"><i class="glyphicon glyphicon-remove"></i> Delete Organization</button>
                    </form>
                </div>
                @endif   
            </div>
        </div>
        </div>
    </div>
</div>
<script>
    function confirmDelete(){
        return confirm("Are you sure to perform delete action ? ");
    }
</script>
@endsection
