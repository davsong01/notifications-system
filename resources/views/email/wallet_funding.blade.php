<h2>Wallet Funding Successful 💰</h2>

<p>Hello {{ $transaction->user->fullname }},</p>

<p>
    Your wallet has been credited successfully.
</p>

<p>
    <strong>Amount:</strong>

    {{ $transaction->currency_symbol }}
    {{ number_format($transaction->transaction_amount, 2) }}
</p>

<p>
    Your updated wallet balance is now available for purchases and withdrawals.
</p>

@include('david-oghi-notifications-system::email.partials.transaction_context')
