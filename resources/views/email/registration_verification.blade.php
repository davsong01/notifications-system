
<div style='max-width:600px;margin:20px auto;padding:30px;border:1px solid #e5e7eb;border-radius:12px;background:#ffffff;font-family:Arial,sans-serif;'>
    <h3 style='color:#111827;'>Hello, {{$name}}</h3>

    <p style='color:#374151;line-height:1.7;'>
        Thank you for registering with {{ config('app.name') }}. To complete your account setup,
        please verify your email address using the verification code below:
    </p>

    <div style='margin:30px 0;text-align:center;'>

        <div style='
            display:inline-block;
            background:#f3f4f6;
            border:1px dashed #d1d5db;
            padding:18px 35px;
            border-radius:12px;
            font-size:32px;
            font-weight:700;
            letter-spacing:8px;
            color:#004cab;
        '>
            {{ $otp->otp }}
        </div>

    </div>

    <p style='color:#374151;line-height:1.7;'>
        This verification code will expire at:
        <strong>{{ $otp->expires_at->toDayDateTimeString() }}</strong>
    </p>

    <p style='color:#6b7280;line-height:1.7;font-size:14px;'>
        If you did not create an account with {{ config('app.name') }}, please ignore this email.
        No further action is required.
    </p>

    <hr style='border:none;border-top:1px solid #e5e7eb;margin:30px 0;'>

    <p style='margin:0;color:#6b7280;font-size:14px;'>
        Regards,<br>
        <strong>{{ config('app.name') }} Team</strong>
    </p>

</div>";
