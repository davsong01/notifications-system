<h2>Payout Approved</h2>

<p>Hello {{ $name ?? 'there' }},</p>

<p>
    Your payout request has been approved successfully.
</p>

@if(!empty($amount))
    <p>
        <strong>Amount:</strong> {{ $amount }}
    </p>
@endif

@if(!empty($extra_message))
    <p>
        {{ $extra_message }}
    </p>
@endif
