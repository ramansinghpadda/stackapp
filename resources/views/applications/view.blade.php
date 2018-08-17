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

                    
                    @if($userRoleInOrganization && $userRoleInOrganization->canAccess('update-application'))
                    <div class="f-r">
                        <a class="btn btn-sm btn-primary" href="{{ route('organization-application-edit',[$organization,$application])}}"><i class="glyphicon glyphicon-edit"></i> Edit</a>

                        <a class="btn btn-sm btn-warning" href="{{ route('organization-application',[$organization])}}">Back</a>
                    </div> @endif
                    
                    <h3>@if ($application->servicecatalog->domain)<img src="https://www.google.com/s2/favicons?domain={{$application->servicecatalog->domain}}" alt="{{$app_name}} logo"> @endif{{$app_name}}</h3>

                    @if ($application->servicecatalog->url)
                    <p><a class="applications-table__link-url" title="Open {{$application->servicecatalog->name}}" href="{{$application->servicecatalog->url}} in new tab" target="_blank">{{$application->servicecatalog->url}}</a></p> @endif

                <div>
                    <h3>Files & Documents</h3>
                    <div >
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                        <div class="alert alert-danger" id="form-error" style="display:none"></div>
                        <form onsubmit="return validateUploadForm()" action="{{ route('application-upload',[$organization,$application]) }}" class="form-inline" method="post" enctype="multipart/form-data">
                            <div class="form-group">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}"/>
                                <input id="fileinput" class="form-control" type="file" name="file" onchange="validateUploadForm()"/>
                                <button class="btn btn-success">UPLOAD</button>
                            </div>
                           
                        </form>
                    </div>
                    <div class="container">
                     <div style="padding:30px;"> 
                     @if($application->attachments->count() > 0 )
                        @foreach($application->attachments as $attachment)
                        {!! Attachment::_link($attachment) !!}
                        @endforeach
                    @else
                        <p>No Attachments </p>
                    @endif
                    </div>
                    </div>
                </div>


                    <table class="table applications-table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th scope="col">Attribute</th>
                                <th scope="col">Value</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                @foreach($application->groups as $group)
                                <td>Groups</td>
                                <td><label class="label label-default">{{$group->group->name}}</label></td>
                                @endforeach
                            </tr>
                            @foreach($metaAttributes as $attribute)
                            <tr>
                                <td>{{ $attribute->meta->name }}:</td>
                                <td>{{ $application->mappedValue($attribute) }} </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <p>Last update {{$application->updated_at}}</p>

                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
   function validateUploadForm() {
       return true;
            var input, file;
            $('#form-error').hide();
            input = document.getElementById('fileinput');
            if (!input.files[0]) {
                bodyAppend("p", "Please select a file ");
                
            }
            else {
                file = input.files[0];
                var fileSizeInMB = (file.size/(1024*1024)) ; 
                if(parseFloat(fileSizeInMB) < parseFloat(50.0) ){
                    return true;
                }
                bodyAppend("p", "File " + file.name + " - size should be less than 50MB. ");
            }
        return false;
    }

    function bodyAppend(tagName, innerHTML) {
        $('#form-error').html(innerHTML).show();
    }
</script>

@endsection