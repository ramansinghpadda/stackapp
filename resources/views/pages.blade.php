@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <h1 class="panel-heading">{{ ucfirst($page) }}</h1>
                <div class="panel-body">
                	@switch($page)
    					@case('pricing')
                        <table class="table table-responsive">
                            <thead>
                                <th>Plan</th>
                                <th>Projects</th>
                                <th>Storage</th>
                                <th>Collaboration</th>
                            </thead>
                            <tbody>
                                <tr>
                                    @foreach($plans as $plan)
                                    <tr>
                                        <td><strong>{{$plan->name}}</strong></td>
                                        <td>{{$plan->num_organizations_limit}} organizations</td>
                                        <td>{{$plan->num_applications_limit}} applications</td>
                                        <td>{{$plan->num_collaborators}} team members</td>
                                    </tr>
                                    @endforeach
                                </tr>
                            </tbody>
                        </table>
                        <div class="text-center">
                            <div class="m-b-20"><a href="{{ route('register') }}" class="btn btn-success btn-lg" title="Sign up">Get started - FREE plan</a></div>
                            <p class="lead">PS: you can upgrade or downgrade anytime.</p>
                            <p><a href="{{ route('contact') }}" title="Contact us">Any questions? Talk to us, we are here to help!</a></p>
                        </div>
                       
        				@break

   						@case('features')
        				<span>Coming soon</span>
        				@break

        				@case('about')
                        <p>StackrApp was built in Janurary 2018 and is currently in beta.</p>
                        <p>We aim at keeping it a simple but robust solution, to help organizations manage their application inventory as a team and help them make better decision about their marketing, sales, advertising technology stacks.</p>

                        <h2>A solution to a problem</h2>
                        <p>We, as many others, have experienced the growing challenge organizations face: keeping a healthy, efficient, cost-effective stack of applications, easily manageable and acccessible.</p>
                        <p>With an average 90+ applications per organization, and an increasingly growing SaaS market, organizations need tools to easily track and manage the inventory of their applications.</p>
                        <p>Still today, most organizations use spreadsheets to manage their application inventory, which has been proven to be inefficient, not sustainable and open for human-errors.</p>
                        <p>StackrApp was built out of a need for a solution: its founder had to inventory the growing number of SaaS applications used in its department and capture key data (renewal dates, owner, vendor contacts, agreements/contracts, notes, etc.) that he could share with his team, his division and accross the organization. As the number of vailabale SaaS appplications grows and adoption becomes less and less frictionless, organizations will need better tools to manage their applications.</p>        

                        <h3>Application inventory management beyond SaaS</h3>
                        <p>Every organization needs to run application inventory management regularly to identify redudant software, plan for digital transformations or implementation of new technology, identify unused applications, budget planning, etc.</p>
                        <p>Our resarch has shown that most organizations still use spreadhseets, which does not scale, doesn't offer real-time visualizations, isn't collaborative-friendly, can gets lost and out of sync rapidly.</p>
                        <p>Web applications can behave as spreadsheets but offer but offer a lot more options related to collaboration, access (anywhere on any devices), stay in sync, provide instant vizualizations, notifications, and so much more.</p>
        				@break

        				@case('contact')
        				<span>Contact form</span>
        				@break

    					@default
        				<span>Oops</span>
						@endswitch
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
