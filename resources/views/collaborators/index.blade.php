@extends('layouts.app') @section('content')
<?php $userRoleInOrganization  = Auth::user()->userrole($organization);
    $collaboratorsRoles = [] ; 
    foreach($roles as $role){
        $collaboratorsRoles[]=['text'=>$role->display_name,'value'=>$role->id];
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
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <td>Name</td>
                                    <td>Role</td>
                                    <td>Status</td>
                                    <td>Action</td>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($collaborators as $collaborator)
                                <tr>
                                    <td>{{$collaborator->user ? $collaborator->user->email : ''}}</td>
                                    <td>
                                        @if($collaborator->role->is(['manager','member']))
                                        <a class="inlineEditable" data-mode="inline" data-params="{{ '{_token:\''.csrf_token().'\',oID:\''.$organization->id.'\'}' }}" data-url="{{ route('role-update',$organization) }}" data-pk="{{ $collaborator->id }}" data-name="collaborator" data-type="select"
                                            data-value="{{ $collaborator->role->id }}">{{$collaborator->role->display_name}}</a> @else {{$collaborator->role->display_name}} @endif
                                    </td>
                                    <td>
                                        <span class="label label-{{ $collaborator->statusID ? 'success' : 'info'}}">{{ $collaborator->statusID ? 'Active' : 'Pending'}}</span></td>
                                    <td>
                                        @if($collaborator->role->is(['manager','member']))
                                        <form onsubmit="return revokePermission()" action="{{ route('delete-collaboration',[$organization,$collaborator]) }}" method="POST">
                                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                                            <button class="btn btn-xs btn-danger">Revoke</button>
                                        </form>
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>

                <div class="panel-footer">
                    <h3>Pending Invitations</h3>
                    <table class="table table-striped">
                        <tr>
                            <th>Email </th>
                            <th>Invitation Date</th>
                            <th></th>
                        </tr>
                        @if($invitations->count() > 0 ) @foreach($invitations as $invitation)
                        <tr>
                            <td>{{$invitation->email}}</td>
                            <td>{{ date('F j Y H:i',strtotime($invitation->created_at)) }}</td>
                            <td>
                                <form method="POST" action="{{ route('resend-invitation',[$organization,$invitation]) }}"><input type="hidden" name="_token" value="{{ csrf_token() }}" /><button class="btn btn-sm btn-default">Resend</button></td>
                        </tr>
                        @endforeach @else
                        <tr>
                            <td colspan="3">There are no pending invitations.</td>
                        </tr>
                        @endif
                    </table>

                    @if(!Auth::user()->planMemberLimitValid($organization))
                    <div class="alert alert-warning">
                        The limit of collaborators for this organization has been reached. @if (Auth::user()->userrole($organization)->name == "owner") Please <a href="{{ route('user-subscription')}}">upgrade</a> your plan. @endif
                    </div>
                    @else
                    <form class="form-inline" method="POST" onsubmit="return validateForm()" action="{{ route('add-member',$organization->id)}}">
                        <h3>Invite a team member</h3>
                        <div class="form-group">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}" />
                            <label for="email" class="col-md-4 control-label">Email:</label>
                            <div class="col-md-8">
                                <input type="email" id="email" name="email" placeholder="Enter email to invite collaborator" class="form-control" required/>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-4">
                                <select id="role" name="roleID" class="form-control">
                                    <option value="">Choose Role</option>
                                    @foreach(\App\Role::invitable() as $role)
                                    <option value="{{$role->id}}">{{$role->display_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-md-3">
                                <button type="submit" class="btn btn-sm btn-primary">Invite</button>
                            </div>
                        </div>
                        <div class="clear clearfix"></div><br/>
                        <p id="message" class="text-center text-red"></p>
                    </form>
                    @endif

                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    function validateForm(){
        if($('#email').val() == ''){
            $('#message').text("Please enter email");
            return false;
        }
        if($('#role').val() == ''){
            $('#message').text("Please select role");
            return false;
        }
        return true;
    }
    function revokePermission(){
        return confirm("Are you sure to perform remove action ? ");
    }
</script>
@endsection @section('styles')
<link href="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/css/bootstrap-editable.css" rel="stylesheet" /> @endsection @section('scripts')
<script src="//cdnjs.cloudflare.com/ajax/libs/x-editable/1.5.0/bootstrap3-editable/js/bootstrap-editable.min.js"></script>
<script>
    var roleSources = <?=json_encode($collaboratorsRoles) ?>;
    $('.inlineEditable').editable({
            source :roleSources,
            success: function(response, newValue) {
                console.log(response);
                if(response.status == 'error') 
                return response.msg; //msg will be shown in editable form
            }
            });
</script>
@endsection