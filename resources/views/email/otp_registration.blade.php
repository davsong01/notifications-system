<h2>Verify Your Account</h2>

<p>Hello {{ $name ?? 'there' }},</p>

<p>
    Please use the verification code below:
</p>

<div class="otp-box">
    {{ $otp->otp }}
</div>

@if(!empty($extra_message))
    <p>
        {{ $extra_message }}
    </p>
@endif
