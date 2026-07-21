<h2>Refund Processed ↩️</h2>

<p>Hello {{ $transaction->user->fullname }},</p>

<p>
    Your refund has been processed successfully.
</p>

<p>
    <strong>Amount Refunded:</strong>

    {{ $transaction->currency_symbol }}
    {{ number_format($transaction->transaction_amount, 2) }}
</p>

<p>
    If the funds do not reflect immediately, please allow a few business days.
</p>

@include('david-oghi-notifications-system::email.partials.transaction_context')
