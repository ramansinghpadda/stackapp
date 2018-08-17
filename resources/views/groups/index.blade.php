@extends('layouts.app') @section('content')
<?php $userRoleInOrganization  = Auth::user()->userrole($organization); 

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
                    <div><button class="btn btn-success" onclick="loadGroupModal()"><i class="glyphicon glyphicon-plus"></i> Add group</button></div>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <td>Name</td>
                                    <td>Action</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($groups as $group)
                                <tr>
                                    <td class="">{{ ucfirst($group->name) }}</td>
                                    <td>
                                        @if($userRoleInOrganization && $userRoleInOrganization->canAccess('update-group'))
                                        <button class="btn btn-xs btn-primary pull-left" onclick="loadGroupModal({{ $group->id }})"><i class="glyphicon glyphicon-edit">&nbsp;</i>Edit</button> @endif @if($userRoleInOrganization && $userRoleInOrganization->canAccess('delete-group'))
                                        <form method="post" action="{{ route('delete-group',[$organization,$group]) }}" class="pull-left" onsubmit="return validateGroup()">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}" /> &nbsp;&nbsp;&nbsp;
                                            <button class="btn btn-xs btn-danger"><i class="glyphicon glyphicon-trash">&nbsp;</i>Delete</button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach

                            </tbody>
                            <tfoot>
                                @if($groups->count() == 0)
                                <tr>
                                    <td colspan="3">No group found.</td>
                                </tr>
                                @endif
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" data-backdrop="static" id="groupModal" tabindex="-1" role="dialog" aria-labelledby="groupModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="groupModalLabel">Group</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
            </div>
            <div class="modal-body" id="group-modal-body">

            </div>
            <div class="modal-footer">
                <div class="small-progress" id="group-modal-progress" style="display:none;">
                    <div class="indeterminate"></div>
                </div>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" onclick="saveGroup()">Save changes</button>
            </div>
        </div>
    </div>
</div>
@endsection @section('scripts')
<script>
    function loadGroupModal(id){
       $('#groupModal').modal('show');
       $.ajax({
           url:"{{ route('create-group',$organization) }}"+"?id="+id,
           beforeSend:function(){
               $('#group-modal-progress').show();
           },
           success:function(response){
               $('#group-modal-body').html(response);
               $('#group-modal-progress').hide();
           }
       });
    }
    
    function printErrorMsg (msg) {
            $("#group-form-errors").find("ul").html('');
            $("#group-form-errors").css('display','block');
            $.each( msg, function( key, value ) {
                $("#group-form-errors").find("ul").append('<li>'+value+'</li>');
            });
    }
    
    function saveGroup(){
            $.ajax({
                type:"POST",
                url:"{{ route('organization-group-new',$organization)}}",
                data:$('#group-form').serialize(),
                beforeSend:function(){
                    $('#group-modal-progress').show();
                },
                
                success:function(data){
                    if(data.success){
                        window.location.reload();
                    }
                else if($.isEmptyObject(data.error)){
                        //alert(data.success);
                    }else{
                        printErrorMsg(data.error);
                    }
                $('#group-modal-progress').hide();
                }
            });
    }
    
    function validateGroup(){
        return  confirm("Are you sure you want to delete group ? ");
    }
</script>
@endsection