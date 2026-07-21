<h2>Referral Reward Received 🎁</h2>

<p>Hello {{ $transaction->referral->fullname }},</p>

<p>
    Your referral commission has been credited successfully.
</p>

<p>
    <strong>Reward:</strong>

    {{ $transaction->currency_symbol }}
    {{ number_format($transaction->referral_commission, 2) }}
</p>

<p>
    Thank you for helping grow our community.
</p>

@include('david-oghi-notifications-system::email.partials.transaction_context')
