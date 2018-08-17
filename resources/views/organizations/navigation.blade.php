<div class="f-r">
<ul class="nav nav-pills" role="navigation" aria-labelledby="Organization navigation">
    @if($manageOrgPermission)
    <li class="nav-item {{ Route::currentRouteName() == 'organization-application' ||  Route::currentRouteName() =='organization-application-edit' ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('organization-application',$organization->id) }}" title="Link to application management">Applications</a>
    </li>
    @endif
    @if($manageOrgPermission)
    <li class="nav-item {{ Route::currentRouteName() == 'organization-meta' ? 'active' : '' }}">
        <a class="nav-link" href="{{ route('organization-meta',$organization)}}" title="Link to columns management">Columns</a>
    </li>
    @endif 
    @if($userRoleInOrganization && ($userRoleInOrganization->canAccess('add-group') || $userRoleInOrganization->canAccess('delete-group') || $userRoleInOrganization->canAccess('update-group')))
    <li class="nav-item {{ Route::currentRouteName() == 'organization-groups' ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('/organization/'.$organization->id.'/groups')}}" title="Link to group management">Groups</a>
    </li>
    @endif 
    @if($userRoleInOrganization && $userRoleInOrganization->canAccess('manage-collaborators'))
    <li class="nav-item {{ Route::currentRouteName() == 'organization-team' ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('/organization/'.$organization->id.'/team')}}" title="Link to team management">Team</a>
    </li>
    @endif 
    @if($manageOrgPermission)
    <li class="nav-item {{ Route::currentRouteName() == 'organization-edit' ? 'active' : '' }}">
        <a class="nav-link" href="{{ url('/organization/'.$organization->id.'/edit')}}" title="Link to edit organization">Edit</a>
    </li>
    </ul>
    @endif
</div>
<h1><a href="{{ route('organization-application',$organization->id) }}">{{$organization->name}}</a></h1>