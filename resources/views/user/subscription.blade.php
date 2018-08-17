@extends('layouts.app') @section('content')


<div class="container">
    <div class="row">
        <div class="col-md-10 col-md-offset-1">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h1>Account Information</h1>
                    <div class="row clearfix">
                        <div class="col-lg-6">
                            <h3>Current subscription:</h3>
                            <ul class="list-group">
                                <li class="list-group-item active">{{$plan->name}}</li>
                                <li class="list-group-item">{{ $plan->num_organizations_limit == '~' ? "Unlimited" : $plan->num_organizations_limit }} Organization(s)</li>
                                <li class="list-group-item">{{ $plan->num_applications_limit == '~' ? "Unlimited" : $plan->num_applications_limit }} Application(s)</li>
                                <li class="list-group-item">{{ $plan->num_collaborators == '~' ? "Unlimited" : $plan->num_collaborators }} Collaborator(s)</li>
                            </ul>
                        </div>
                        <div class="col-lg-6">
                            @if($isPlanUpgradable)
                            <h3>Need more?</h3>
                            <a class="btn btn-success btn-lg" href="{{ route('user-plan-upgrade') }}">Upgrade your plan</a>
                            <p class="m-t-10">Questions? <a href="{{ route('contact')}}">Contact us</a>.</p>
                            @endif
                            @if(Auth::user()->subscribed('main'))
                            <h3>Payment Information</h3>
                            <p>
                                <strong>Credit Card :</strong> {{Auth::user()->card_brand}}
                                <strong>Card Number :</strong> {{str_repeat('*',12).Auth::user()->card_last_four}}
                                <a href="{{ route('user-payment-info') }}" class="btn btn-sm btn-primary">Update Payment Info</a>
                            </p>
                            @endif
                        </div>
                    </div>
                    @if($invoices && $invoices->count() > 0)
                    <div class="panel panel-default m-t-20">
                        <div class="panel-heading">
                            <h3>Invoice History <button onclick="refreshInvoices()" class="btn btn-success pull-right">Refresh</button></h3>
                        </div>
                        <div class="panel-body">
                            @foreach($invoices as $invoice)
                            <div style="background: rgb(245, 248, 250);padding: 5px 10px;margin-bottom: 4px;">
                                <table class="table table-responsive">
                                    <tr>
                                        <td><strong>Invoice No. : </strong> {{ $invoice->number }}</td>
                                        <td><strong>Created : </strong> {{ $invoice->date() }}</td>
                                        <td><strong>Amount : </strong> {{ $invoice->total() }}</td>
                                        <td><strong>Status :</strong>{{ $invoice->paid ? 'Paid' : 'Not Paid' }}</td>
                                    </tr>
                                </table>
                                <details>
                                    <summary>View Details
                                    </summary>
                                    <table width="100%" class="table table-responsive" border="0">
                                        <tr>
                                            <th align="left">Description</th>
                                            <th align="right">Date</th>
                                            <th align="right">Amount</th>
                                        </tr>

                                        <!-- Existing Balance -->
                                        <tr>
                                            <td>Starting Balance</td>
                                            <td>&nbsp;</td>
                                            <td>{{ $invoice->startingBalance() }}</td>
                                        </tr>

                                        <!-- Display The Invoice Items -->
                                        @foreach ($invoice->invoiceItems() as $item)
                                        <tr>
                                            <td colspan="2">{{ $item->description }}</td>
                                            <td>{{ $item->total() }}</td>
                                        </tr>
                                        @endforeach

                                        <!-- Display The Subscriptions -->
                                        @foreach ($invoice->subscriptions() as $subscription)
                                        <tr>
                                            <td>Subscription ({{ $subscription->quantity }})</td>
                                            <td>
                                                {{ $subscription->startDateAsCarbon()->formatLocalized('%B %e, %Y') }} - {{ $subscription->endDateAsCarbon()->formatLocalized('%B %e, %Y') }}
                                            </td>
                                            <td>{{ $subscription->total() }}</td>
                                        </tr>
                                        @endforeach

                                        <!-- Display The Discount -->
                                        @if ($invoice->hasDiscount())
                                        <tr>
                                            @if ($invoice->discountIsPercentage())
                                            <td>{{ $invoice->coupon() }} ({{ $invoice->percentOff() }}% Off)</td>
                                            @else
                                            <td>{{ $invoice->coupon() }} ({{ $invoice->amountOff() }} Off)</td>
                                            @endif
                                            <td>&nbsp;</td>
                                            <td>-{{ $invoice->discount() }}</td>
                                        </tr>
                                        @endif

                                        <!-- Display The Tax Amount -->
                                        @if ($invoice->tax_percent)
                                        <tr>
                                            <td>Tax ({{ $invoice->tax_percent }}%)</td>
                                            <td>&nbsp;</td>
                                            <td>{{ Laravel\Cashier\Cashier::formatAmount($invoice->tax) }}</td>
                                        </tr>
                                        @endif

                                        <!-- Display The Final Total -->
                                        <tr style="border-top:2px solid #000;">
                                            <td>&nbsp;</td>
                                            <td style="text-align: right;"><strong>Total</strong></td>
                                            <td><strong>{{ $invoice->total() }}</strong></td>
                                        </tr>
                                    </table>
                                </details>
                            </div>
                            @endforeach
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    function refreshInvoices(){
        window.location.href  = window.location.href.split('?')[0]+'?cache=false';
    }
</script>
@endsection