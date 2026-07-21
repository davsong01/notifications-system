<h2>Payout Request Rejected</h2>

<p>Hello {{ $name ?? 'there' }},</p>

<p>
    Unfortunately, your payout request could not be approved.
</p>

@if(!empty($reason))
    <p>
        <strong>Reason:</strong> {{ $reason }}
    </p>
@endif

@if(!empty($extra_message))
    <p>
        {{ $extra_message }}
    </p>
@endif
