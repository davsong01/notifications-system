<h2>Escrow Created 🔒</h2>

<p>Hello {{ $transaction->user->fullname }},</p>

<p>
    An escrow transaction has been created successfully.
</p>

<p>
    Funds will remain securely held until all conditions are fulfilled.
</p>

<p>
    <strong>Reference:</strong>
    {{ $transaction->reference }}
</p>

<p>
    Both buyer and seller will receive notifications as the transaction progresses.
</p>

@include('david-oghi-notifications-system::email.partials.transaction_context')
