<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {!! SEO::generate() !!}

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link href="{{ asset('css/all.css') }}" rel="stylesheet">
    <script src="{{ asset('js/jquery.min.js') }}" ></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    @yield('styles')
    
    


@if (config('app.env') === 'production')
<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-TH3GRLB');</script>
<!-- End Google Tag Manager -->
@endif
    
</head>

<body>
    <div class="flex-center position-ref full-height">

        @include ('layouts.partials._navbar')

        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel panel-default">
                        <div class="panel-heading">
                            <h1 class="homepage__title">
                                Keep your applications handy
                            </h1>
                            <p class="homepage__title__p">StackrApp helps organizations staying organized.</p>
                        </div>
                        <div class="panel-body">
                            <div class="row homepage__lead">
                                <p class="text-center">Read this familiar email exchange...</p>
                                <div class="col col-md-offset-1 col-lg-5 vert-offset-top-1">
                                    <div class="card card__email">
                                        <div class="card-header">
                                            <div class="card__email__controls">New Message</div>
                                            <div class="card__email__recipients">To: John, Frank</div>
                                            <div class="card__email__subject">Email marketing app?</div>
                                        </div>
                                        <div class="card-block card__email__message">
                                            I need to set up an email campaign for a new account.<br><br>Do you guys know what we <strong>have been using for email marketing</strong>?<br><br>-- Jeff
                                        </div>
                                    </div>
                                </div>
                                <div class="col col-lg-5 vert-offset-top-7">
                                    <div class="card card__email">
                                        <div class="card-header">
                                            <div class="card__email__controls">Reply</div>
                                            <div class="card__email__recipients">To: Jeff, Frank</div>
                                            <div class="card__email__subject">RE: Email marketing app?</div>
                                        </div>
                                        <div class="card-block card__email__message">
                                            Mike was in charge, but he left two weeks ago.<br><br>I have no idea who this got transitioned to.<br><br>-- John
                                        </div>
                                    </div>
                                </div>
                                <div class="col col-lg-5 col-md-offset-1">
                                    <div class="card card__email">
                                        <div class="card-header">
                                            <div class="card__email__controls">Reply</div>
                                            <div class="card__email__recipients">To: John, Jeff, Laura</div>
                                            <div class="card__email__subject">RE: RE: Email marketing app?</div>
                                        </div>
                                        <div class="card-block card__email__message">
                                            We need to keep this info centralized.<br><br>I am CC-ing Laura - @Laura would you know?!<br><br>-- Frank
                                        </div>
                                    </div>
                                </div>
                                <div class="col col-lg-5 vert-offset-top-6">
                                    <div class="card card__email">
                                        <div class="card-header">
                                            <div class="card__email__controls">Reply</div>
                                            <div class="card__email__recipients">To: John, Jeff, Frank</div>
                                            <div class="card__email__subject">RE: RE: RE: Email marketing app?</div>
                                        </div>
                                        <div class="card-block card__email__message">
                                            Mailchimp!<br><br>We keep all our apps centralized in <strong>Stackrapp</strong> - check it out.<br><br>-- Laura
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="panel-footer">
                                <div class="homepage__more">

                                    <div class="homepage__image">
                                        <center><img class="img-responsive" alt="Stackrapp Stack Sample" src="https://s3.us-east-2.amazonaws.com/stackrapp/files/img/stackrapp_homepage.png"
                                            data-holder-rendered="true"></center>
                                    </div>
                                    <h4>Your team will thank you.</h4>
                                </div>
                                <div class="homepage__register">
                                    <div class="row">
                                        <h2>Let's build your inventory!</h2>

                                        <form class="form-row col-md-11 col-md-offset-1" action="{{ url('/analyze') }}">

                                            <div class="form-group col-md-3">
                                                <label for="url">Enter your URL:</label>
                                            </div>
                                            <div class="form-group col-md-6">
                                            <input type="url" id="url" name="q" class="form-control homepage__register" placeholder="https://yoursite.com" required="required"/>
                                        </div>
                                        <div class="form-group col-md-3 text-left">
                                            <button type="submit" class="btn btn-primary">Scan now</button>
                                        </div>
                                        </form>
                                        <p class="text-center">We'll scan your website to find known applications</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include ('layouts.partials._footer')
    
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
    @yield('scripts')

   

@if (config('app.env') === 'production')
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-TH3GRLB"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
@endif
</body>
</html>
</html>