<h2>Account Adjustment Notice ⚙️</h2>

<p>Hello {{ $transaction->user->fullname }},</p>

<p>
    An administrator has made an adjustment to your account.
</p>

<p>
    <strong>Reference:</strong>
    {{ $transaction->reference }}
</p>

<p>
    <strong>Amount:</strong>

    {{ $transaction->currency_symbol }}
    {{ number_format($transaction->transaction_amount, 2) }}
</p>

<p>
    If you believe this adjustment was made in error, please contact support immediately.
</p>

@include('david-oghi-notifications-system::email.partials.transaction_context')
