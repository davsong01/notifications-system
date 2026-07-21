<h2>Transaction Update</h2>

<p>Hello {{ $transaction->user?->fullname ?? $transaction->user?->name ?? 'there' }},</p>

<p>
    We have an update regarding your recent transaction.
</p>

@include('david-oghi-notifications-system::email.partials.transaction_context')
