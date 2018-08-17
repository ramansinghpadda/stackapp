@extends('layouts.app') @section('content')
<?php 
$userRoleInOrganization  = Auth::user()->userrole($organization);
//set the app_name
if ($application->name) {
    $app_name = $application->name;
} else {
    $app_name = $application->servicecatalog->name;
}
$updateAppPermission = $userRoleInOrganization ?  $userRoleInOrganization->canAccess('update-application') : false;
    $addAppPermission =  $userRoleInOrganization ? $userRoleInOrganization->canAccess('add-application') : false;
    $manageOrgPermission = $userRoleInOrganization ? $userRoleInOrganization->canAccess('manage-organization') : false;
    
?>
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    @include('organizations.navigation')
                </div>
                <div class="panel-body">

                    @if(Auth::user()->userrole($organization)->canAccess('delete-application'))
                    <form method="POST" action="{{ route('application-delete',['id'=>$organization->id,'appId'=>$application->id]) }}" onsubmit="return confirmDelete()" class="pull-right">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        <button class="btn btn-sm btn-danger"><i class="glyphicon glyphicon-remove"></i> Remove Application</button>
                    </form>
                    @endif

                    <h3>@if ($application->servicecatalog->domain)<img src="https://www.google.com/s2/favicons?domain={{$application->servicecatalog->domain}}" alt="{{$app_name }} logo"> @endif{{$app_name}}</h3>

                    <form method="POST" action="{{ route('organization-application-update',[$organization,$application]) }}" class="form">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                        @if ($application->servicecatalog->is_custom)
                        <div class="form-group">            
                            <label class="control-label">Name</label> 
                            <input type="text" name="name" class="form-control" value="{{ $app_name }}" /> 
                        </div>
                        @endif
                        @foreach($metaAttributes as $attribute)
                        <div class="form-group">

                            <label class="control-label">{{ $attribute->meta->name }}</label> 
                            @if($attribute->meta->type == 'text')
                            <input type="text" name="metamapping[{{ $attribute->id }}]" class="form-control" value="{{ $application->mappedValue($attribute) }}" /> 
                            @elseif($attribute->meta->type == 'long_text')
                            <textarea name="metamapping[{{ $attribute->id }}]" class="form-control">{{ $application->mappedValue($attribute) }}</textarea> 
                            @elseif($attribute->meta->type == 'integer')
                            <input type="number" class="form-control" name="metamapping[{{ $attribute->id }}]" value="{{ $application->mappedValue($attribute) }}" /> 
                            @elseif($attribute->meta->type == 'date')
                            <input type="date" class="form-control" name="metamapping[{{ $attribute->id }}]" value="{{ $application->mappedValue($attribute) }}" /> 
                            @elseif($attribute->meta->type == 'option')
                            <select name="metamapping[{{ $attribute->id }}]" class="form-control">
                                @foreach($attribute->meta->getOptions() as $option)
                                    <option value="{{ $option }}">{{$option}}</option>
                                @endforeach
                            </select>
                            @endif
                        </div>
                        @endforeach
                        @if ($groups->count() >0)
                        <div class="form-group">
                            <label class="control-label">Group</label> 
                            <select id="groups" name="groups[]" class="form-control" multiple="true">
                                @foreach($groups as $group){
                                <option value="{{ $group->id }}" @if(in_array($group->id,$applicationGroups)) selected @endif>{{ $group->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @endif
                        <button class="btn btn-primary">Update</button>
                        <a class="btn btn-warning" href="{{ route('organization-application',$organization) }}">Cancel</a>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>
<script>
    function confirmDelete(){
        return confirm("Are you sure you want to remove this application");
    }
</script>
@endsection
@section('scripts')
<script type="text/javascript" src="{{ asset('/js/select2.min.js')}}"></script>
<script>
    $('#groups').select2({
    placeholder: 'Select Group(s)'
    });
</script>
@endsection