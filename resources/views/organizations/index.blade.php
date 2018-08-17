@extends('layouts.app') @section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel-body">
                @if(count($organizations) > 0)
                <div class="row">
                    @foreach($organizations as $organization)
                    <div class="col-md-6">
                        <div class="card organization-card">
                            <div class="card__body match-height">
                                <h2 class="card__title">
                                    <a href="{{ url('/organization/'.$organization->id.'/')}}">{{$organization->name}}</a>
                                </h2>

                                <div class="card__info">
                                    <ul class="list-unstyled">
                                        <li>{{ Auth::user()->userrole($organization)->display_name }}</li>
                                        <li>{{$organization->applications->count()}} applications</li>
                                        @if(count($organization->groups) > 1) 
                                        <li>{{count($organization->groups)}} groups</li>
                                        @endif
                                        @if(count($organization->collaborators) > 1) 
                                        <li>{{count($organization->collaborators)}} team members</li>
                                        @endif
                                    </ul>
                                </div>
                            </div>                            
                            <div class="card__actions">
                                <a class="btn btn-sm btn-primary" href="{{ url('/organization/'.$organization->id)}}"><i class="glyphicon glyphicon-edit"></i> View</a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="alert alert-info">
                    <p>Hi {{Auth::User()->name}}! Any organizations you have created, or got invited as a team member will show up below.</p>
                </div>
                <div class="lead">Try it out! Create an organization to inventory your applications.<br>Think of organizations as projects or workspaces.</div>
                <p><strong>Tip:</strong> You can create unlimited custom meta attributes for your inventory, you will find out in the next steps.</p>
                <p><strong>PS:</strong> there is no harm done to delete your first organization later, you can create a new one.</p>
                @endif 
                <a class="btn btn-success btn-small" href="{{ url('/organization/create') }}" title="create an organization"><i class="glyphicon glyphicon-plus"></i> Add new</a>
            </div>
        </div>
    </div>
</div>
</div>
@endsection