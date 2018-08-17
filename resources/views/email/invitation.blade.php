<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
</head>
<body>
    <style>
        @media only screen and (max-width: 600px) {
            .inner-body {
                width: 100% !important;
            }

            .footer {
                width: 100% !important;
            }
        }

        @media only screen and (max-width: 500px) {
            .button {
                width: 100% !important;
            }
        }
    </style>

    <table class="wrapper" width="100%" cellpadding="0" cellspacing="0">
        <tr>
            <td align="center">
                <table class="content" width="100%" cellpadding="0" cellspacing="0">
                    {{ $header or '' }}

                    <!-- Email Body -->
                    <tr>
                        <td class="body" width="100%" cellpadding="0" cellspacing="0">
                            <table class="inner-body" align="center" width="570" cellpadding="0" cellspacing="0">
                                <!-- Body content -->
                                <tr>
                                    <td class="content-cell">
                                        <p><strong>{{Auth::user()->name}}</strong> added you as a team member for {{$invitation->organization->name}}.</p>
<p>Welcome to the team!</p>
<p>Click here to <a href="{{ $invitation->getLink() }}">accept</a> invitation</p>
<p>You can also copy and paste this link: {{$invitation->getLink()}}</p>
<h3>What's this about?</h3>
<p><a href="{{ env('APP_URL')}}">StackrApp</a> is for organizations that need to inventory their applications as a team.<p>
<p>Have any questions or feedback? Help is <a href="{{route('contact')}}">one click away</a>, or you can simply reply to this email.</p>
<p>See you soon,</p>
<p>Damien Arlabosse<br>
Founder {{ env('APP_NAME')}}<br>
<a href="mailto:damien@stackrapp.com">damien@stackrapp.com</a><br>
Website: <a href="{{ env('APP_URL')}}">StackrApp</a><br>
Twitter: <a href="https://www.twitter.com/darlabosse">@darlabosse</a><br>
Linkedin: <a href="https://www.linkedin.com/in/damienarlabosse/">@damienarlabosse</a></p>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    {{ $footer or '' }}
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
