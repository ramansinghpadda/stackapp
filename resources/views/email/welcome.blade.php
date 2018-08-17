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
                                        <h1>Hi {{$user->name}}!</h1>
<p>Welcome to {{ env('APP_NAME')}}.</p>
<p>Thank you for joining our growing community of organizations that need to inventory their applications as a team..</p>
<p>If you haven't started yet, go ahead, <a href="{{ env('APP_URL')}}/home">create your first organization</a> (think about it as a "workspace" or "project") and add one of 5,000+ applications already available, or add your own custom one.</p>
<p>I am here to help, so don't hesitate to reach out via any of the available support channels, you can also simply reply to this email.</p>
<p>Since {{ env('APP_NAME')}} is still in very early stages, i will ask you for a favor: please send me your feedback and suggestions, it will help me make {{ env('APP_NAME')}} a better application management platform.</p>
<p>Thank you again, and don't be shy, I'd love to learn more about your needs!</p>
<p>Sincerely,</p>
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
