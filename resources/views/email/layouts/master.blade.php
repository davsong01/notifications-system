@php
    $siteName = data_get($meta ?? [], 'site_name', config('david-oghi-notifications-system.branding.site_name', config('app.name', 'Application')));
    $siteTitle = data_get($meta ?? [], 'site_title', config('david-oghi-notifications-system.branding.site_title'));

    $supportEmail = data_get($meta ?? [], 'support_email', config('david-oghi-notifications-system.branding.support_email'));
    $supportPhone = data_get($meta ?? [], 'support_phone', config('david-oghi-notifications-system.branding.support_phone'));
    $supportWhatsapp = data_get($meta ?? [], 'support_whatsapp', config('david-oghi-notifications-system.branding.support_whatsapp'));

    $primaryColor = data_get($meta ?? [], 'primary_color', config('david-oghi-notifications-system.branding.primary_color', '#0d6efd'));
    $secondaryColor = data_get($meta ?? [], 'secondary_color', config('david-oghi-notifications-system.branding.secondary_color', '#0f172a'));

    $subject = $subject ?? 'Notification';
    $preambleText = trim((string) ($preamble ?? ''));

    $logoUrl = data_get($meta ?? [], 'logo_url', config('david-oghi-notifications-system.branding.logo_url'));

    $currentYear = now()->year;
@endphp

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0">

    <title>{{ $subject }}</title>

    <style>

        body{
            margin:0;
            padding:0;
            background:#f3f6fb;
            font-family:
                -apple-system,
                BlinkMacSystemFont,
                "Segoe UI",
                Roboto,
                Helvetica,
                Arial,
                sans-serif;
        }

        .container{
            max-width:680px;
            margin:40px auto;
            background:#ffffff;
            border-radius:18px;
            overflow:hidden;
            box-shadow:
                0 12px 40px rgba(15,23,42,.08);
        }

        .header{
            background:{{ $secondaryColor }};
            color:#ffffff;
            padding:40px;
            text-align:center;
            position:relative;
        }

        .header:after{
            content:'';
            position:absolute;
            left:0;
            right:0;
            bottom:0;
            height:4px;
            background:{{ $primaryColor }};
        }

        .logo{
            max-height:60px;
            margin-bottom:18px;
        }

        .site-name{
            font-size:28px;
            font-weight:800;
            margin:0;
        }

        .site-title{
            margin-top:12px;
            color:#cbd5e1;
            font-size:14px;
            line-height:1.7;
        }

        .content{
            padding:50px 45px;
            color:#334155;
            font-size:15px;
            line-height:1.8;
        }

        .extra-message{
            margin-top:35px;
            padding:20px;
            background:#f8fafc;
            border-left:4px solid {{ $primaryColor }};
            border-radius:10px;
            color:#475569;
            font-size:14px;
        }

        .footer{
            background:#fafbfd;
            border-top:1px solid #e2e8f0;
            padding:30px 45px;
        }

        .footer-grid{
            display:flex;
            justify-content:space-between;
            gap:20px;
        }

        .footer-left,
        .footer-right{
            width:50%;
        }

        .footer-title{
            font-weight:700;
            color:#0f172a;
            margin-bottom:10px;
        }

        .footer-text{
            color:#64748b;
            font-size:13px;
            line-height:1.7;
        }

        .support-link{
            color:{{ $primaryColor }};
            text-decoration:none;
            font-weight:600;
        }

        .copyright{
            margin-top:15px;
            color:#94a3b8;
            font-size:12px;
        }

        @media (max-width: 640px){

            .content,
            .header,
            .footer{
                padding:30px 25px;
            }

            .footer-grid{
                display:block;
            }

            .footer-left,
            .footer-right{
                width:100%;
            }

            .footer-right{
                margin-top:25px;
            }
        }

    </style>

</head>
<!-- Invisible Tracking Pixel -->
{{-- @if(isset($trackingId))
    <img src="{{ route('email.track-open', ['id' => $trackingId]) }}" width="1" height="1" border="0" alt="" style="display:none; visibility:hidden; opacity:0; overflow:hidden;">
@endif --}}
<body>
<div style="display:none; max-height:0; overflow:hidden; opacity:0; color:transparent; mso-hide:all; font-size:1px;">
    {!! $preambleText !== '' ? $preambleText : $subject !!}
    &nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;&nbsp;&zwnj;
</div>
<div class="container">

    <div class="header">

        @if($logoUrl)

            <img
                src="{{ $logoUrl }}"
                class="logo"
                alt="{{ $siteName }}">

        @endif

        <h1 class="site-name">
            {{ $siteName }}
        </h1>

        @if($siteTitle)

            <div class="site-title">
                {{ $siteTitle }}
            </div>

        @endif

    </div>

    <div class="content">
        {!! $content !!}
    </div>
    <div class="footer">

        <div class="footer-grid">

            <div class="footer-left">

                <div class="footer-title">
                    {{ $siteName }}
                </div>

                <div class="footer-text">

                    Thank you for using our platform.

                    We remain committed to providing
                    secure and reliable services.

                </div>

            </div>

            <div class="footer-right">

                <div class="footer-title">
                    Need Help?
                </div>

                <div class="footer-text">

                    @if($supportEmail)

                        Email:
                        <a
                            class="support-link"
                            href="mailto:{{ $supportEmail }}">

                            {{ $supportEmail }}

                        </a>

                        <br>

                    @endif

                    @if($supportPhone)

                        Phone:
                        {{ $supportPhone }}

                        <br>

                    @endif

                    @if($supportWhatsapp)

                        WhatsApp:
                        {{ $supportWhatsapp }}

                    @endif

                </div>

                <div class="copyright">

                    © {{ $currentYear }}
                    {{ $siteName }}.

                    All rights reserved.

                </div>

            </div>

        </div>

    </div>

</div>

</body>
</html>
