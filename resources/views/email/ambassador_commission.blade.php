<h2>Commission Earned</h2>

<p>Hello {{ $transaction->ambassador->fullname }},</p>

<p>
    Congratulations! You earned an ambassador commission.
</p>

<p>
    <strong>Commission:</strong>

    {{ $transaction->currency_symbol }}
    {{ number_format($transaction->ambassador_net_amount, 2) }}
</p>

<p>
    Continue promoting products to earn even more rewards.
</p>

@include('david-oghi-notifications-system::email.partials.transaction_context')
