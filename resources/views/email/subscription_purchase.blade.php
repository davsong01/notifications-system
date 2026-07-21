<h2>Subscription Activated 🎉</h2>

<p>Hello {{ $transaction->user->fullname }},</p>

<p>
    Your subscription plan has been activated successfully.
</p>

<p>
    <strong>Plan:</strong>
    {{ $transaction->plan->name }}
</p>

<p>
    <strong>Reference:</strong>
    {{ $transaction->reference }}
</p>

<p>
    Thank you for choosing us.
</p>

<p>
    Your subscription benefits are now available immediately.
</p>

@include('david-oghi-notifications-system::email.partials.transaction_context')
