@extends('layouts.app') @section('content')


<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h1>Upgrade your plan</h1>
                    <div class="row">
                        <div class="col-md-5">
                            <h4>Your current plan is: <strong>{{$plan->name}}</strong></h4>
                            <p>Your features include:</p>
                            <ul>
                                <li>{{ $plan->num_organizations_limit == '~' ? "Unlimited" : $plan->num_organizations_limit }} Organization(s)</li>
                                <li>{{ $plan->num_applications_limit == '~' ? "Unlimited" : $plan->num_applications_limit }} Application(s)</li>
                                <li>{{ $plan->num_collaborators == '~' ? "Unlimited" : $plan->num_collaborators }} Collaborator(s)</li>
                            </ul>
                            <a class="btn btn-primary" href="{{ route('pricing')}}" title="link to pricing page" target="_blank">Click to view pricing plans</a>
                        </div>
                        <div class="col-md-7">
                            {!! Form::open(['url' => route('user-plan-payment'), 'data-parsley-validate', 'id' => 'payment-form']) !!} @if ($message = Session::get('success'))
                            <div class="alert alert-success alert-block">
                                <button type="button" class="close" data-dismiss="alert">×</button>
                                <strong>{{ $message }}</strong>
                            </div>
                            @endif
                            <div class="form-group" id="product-group">
                                {!! Form::label('plan', 'Select Plan:') !!} {!! Form::select('plan', $plans, '', [ 'class' => 'form-control', 'required' => 'required', 'data-parsley-class-handler' => '#product-group' ]) !!}
                            </div>
                            @if(!Auth::user()->subscribed('main'))
                            <div class="form-group" id="cc-group">
                                {!! Form::label(null, 'Credit card number:') !!} {!! Form::text(null, null, [ 'class' => 'form-control', 'required' => 'required', 'data-stripe' => 'number', 'data-parsley-type' => 'number', 'maxlength' => '16', 'data-parsley-trigger' => 'change focusout',
                                'data-parsley-class-handler' => '#cc-group' ]) !!}
                            </div>
                            <div class="form-group" id="ccv-group">
                                {!! Form::label(null, 'CVC (3 or 4 digit number):') !!} {!! Form::text(null, null, [ 'class' => 'form-control', 'required' => 'required', 'data-stripe' => 'cvc', 'data-parsley-type' => 'number', 'data-parsley-trigger' => 'change focusout', 'maxlength'
                                => '4', 'data-parsley-class-handler' => '#ccv-group' ]) !!}
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group" id="exp-m-group">
                                        {!! Form::label(null, 'Ex. Month') !!} {!! Form::selectMonth(null, null, [ 'class' => 'form-control', 'required' => 'required', 'data-stripe' => 'exp-month' ], '%m') !!}
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group" id="exp-y-group">
                                        {!! Form::label(null, 'Ex. Year') !!} {!! Form::selectYear(null, date('Y'), date('Y') + 10, null, [ 'class' => 'form-control', 'required' => 'required', 'data-stripe' => 'exp-year' ]) !!}
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <span class=" alert payment-errors" style="color: red;margin-top:10px;"></span>
                                </div>
                            </div>
                            @endif
                            <div class="form-group">
                                {!! Form::submit('Process payment', ['class' => 'btn btn-success btn-order', 'id' => 'submitBtn']) !!}
                            </div>



                            {!! Form::close() !!}
                        </div>

                    </div>
                </div>

            </div>

        </div>

    </div>
</div>
@if(!Auth::user()->subscribed('main'))
<script src="http://parsleyjs.org/dist/parsley.js"></script>

<script type="text/javascript" src="https://js.stripe.com/v2/"></script>
<script>
    Stripe.setPublishableKey("<?php echo env('STRIPE_KEY') ?>");
        jQuery(function($) {
            $('#payment-form').submit(function(event) {
                var $form = $(this);
                $form.parsley().subscribe('parsley:form:validate', function(formInstance) {
                    //formInstance.submitEvent.preventDefault();
                    //alert();
                    return false;
                });
                $form.find('#submitBtn').prop('disabled', true);
                Stripe.card.createToken($form, stripeResponseHandler);
                return false;
            });
        });
        function stripeResponseHandler(status, response) {
            var $form = $('#payment-form');
            if (response.error) {
                $form.find('.payment-errors').text(response.error.message);
                $form.find('.payment-errors').addClass('alert alert-danger');
                $form.find('#submitBtn').prop('disabled', false);
                $('#submitBtn').button('reset');
            } else {
                var token = response.id;
                $form.append($('<input type="hidden" name="stripeToken" />').val(token));
                $form.get(0).submit();
            }
        };
</script>
@endif @endsection