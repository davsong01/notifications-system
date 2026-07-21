<div style="max-width:600px;margin:20px auto;padding:30px;border:1px solid #e5e7eb;border-radius:12px;background:#ffffff;font-family:Arial,sans-serif;">
    <div style="text-align:center;margin-bottom:25px;">
        <h2 style="margin:0;color:#111827;">
            {{ config('app.name') }}
        </h2>

        <p style="margin-top:8px;color:#6b7280;font-size:14px;">
            Secure Password Recovery
        </p>
    </div>

    <h3 style="color:#111827;">
        Hello {{ $name ?? 'there' }},
    </h3>

    <p style="color:#374151;line-height:1.7;">
        We received a request to reset your account password.
        Use the verification code below to continue:
    </p>

    <div style="margin:30px 0;text-align:center;">

        <div style="
            display:inline-block;
            background:#f3f4f6;
            border:1px dashed #d1d5db;
            padding:18px 35px;
            border-radius:12px;
            font-size:32px;
            font-weight:700;
            letter-spacing:8px;
            color:#004cab;
        ">
            {{ $otp->otp }}
        </div>

    </div>

    <p style="color:#374151;line-height:1.7;">
        This OTP will expire at:
        <strong>{{ $otp->expires_at->format('M d, Y h:i A') }}</strong>
    </p>

    @if(!empty($extra_message))
        <div style="
            margin:20px 0;
            padding:15px;
            background:#f9fafb;
            border-left:4px solid #004cab;
            color:#374151;
            border-radius:6px;
        ">
            {{ $extra_message }}
        </div>
    @endif

    <p style="color:#6b7280;line-height:1.7;font-size:14px;">
        If you did not request a password reset, you can safely ignore this email.
        Your account remains secure.
    </p>

    <hr style="border:none;border-top:1px solid #e5e7eb;margin:30px 0;">

    <p style="margin:0;color:#6b7280;font-size:14px;">
        Regards,<br>
        <strong>{{ config('app.name') }} Team</strong>
    </p>

</div>
