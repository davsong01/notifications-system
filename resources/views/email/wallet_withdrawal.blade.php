<h2>Withdrawal Processed 🏦</h2>

<p>Hello {{ $transaction->user->fullname }},</p>

<p>
    Your withdrawal request has been processed successfully.
</p>

<p>
    <strong>Amount:</strong>

    {{ $transaction->currency_symbol }}
    {{ number_format($transaction->transaction_amount, 2) }}
</p>

<p>
    Depending on your financial institution, settlement may take a few minutes.
</p>

@include('david-oghi-notifications-system::email.partials.transaction_context')
