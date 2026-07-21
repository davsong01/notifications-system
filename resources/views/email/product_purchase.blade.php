<h2>Purchase Successful 🛒</h2>

<p>Hello {{ $transaction->user->fullname }},</p>

<p>
    Your purchase was completed successfully.
</p>

<p>
    <strong>Product:</strong>
    {{ $transaction->product->name }}
</p>

<p>
    <strong>Amount:</strong>
    {{ $transaction->currency_symbol }}
    {{ number_format($transaction->transaction_amount, 2) }}
</p>

<p>
    You can access your purchased items from your dashboard.
</p>

@include('david-oghi-notifications-system::email.partials.transaction_context')
