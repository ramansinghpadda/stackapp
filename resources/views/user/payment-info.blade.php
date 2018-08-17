@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
         <h1>Update your payment info</h1>
            <div class="row">
                <div class="col-md-7">
                    {!! Form::open(['url' => route('user-payment-info-update'), 'data-parsley-validate', 'id' => 'payment-form']) !!}
                        <div class=" alert payment-errors" style="color: red;margin-top:10px;"></div>
                        <div class="form-group" id="cc-group">
                            {!! Form::label(null, 'Credit card number:') !!}
                            {!! Form::text(null, null, [
                                'class'                         => 'form-control',
                                'required'                      => 'required',
                                'data-stripe'                   => 'number',
                                'data-parsley-type'             => 'number',
                                'maxlength'                     => '16',
                                'data-parsley-trigger'          => 'change focusout',
                                'data-parsley-class-handler'    => '#cc-group'
                                ]) !!}
                        </div>
                        <div class="form-group" id="ccv-group">
                            {!! Form::label(null, 'CVC (3 or 4 digit number):') !!}
                            {!! Form::text(null, null, [
                                'class'                         => 'form-control',
                                'required'                      => 'required',
                                'data-stripe'                   => 'cvc',
                                'data-parsley-type'             => 'number',
                                'data-parsley-trigger'          => 'change focusout',
                                'maxlength'                     => '4',
                                'data-parsley-class-handler'    => '#ccv-group'
                                ]) !!}
                        </div>
                        <div class="row">
                        <div class="col-md-6">
                            <div class="form-group" id="exp-m-group">
                                {!! Form::label(null, 'Ex. Month') !!}
                                {!! Form::selectMonth(null, null, [
                                    'class'                 => 'form-control',
                                    'required'              => 'required',
                                    'data-stripe'           => 'exp-month'
                                ], '%m') !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group" id="exp-y-group">
                                {!! Form::label(null, 'Ex. Year') !!}
                                {!! Form::selectYear(null, date('Y'), date('Y') + 10, null, [
                                    'class'             => 'form-control',
                                    'required'          => 'required',
                                    'data-stripe'       => 'exp-year'
                                    ]) !!}
                            </div>
                        </div>
                        </div>
                       
                        
                        <div class="form-group">
                            {!! Form::submit('Update', ['class' => 'btn btn-primary btn-order', 'id' => 'submitBtn']) !!}
                            <a href="{{ route('user-subscription') }}" class="btn btn-danger">Cancel</a>
                        </div>
                        
                        
                    {!! Form::close() !!}
                </div>
                
            </div>
            
        </div>
        
    </div>
</div>
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

@endsection
