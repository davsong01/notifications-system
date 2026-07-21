<h2>Supplier Payout Sent 💵</h2>

<p>Hello {{ $transaction->supplier->fullname }},</p>

<p>
    Your supplier earnings have been credited successfully.
</p>

<p>
    <strong>Amount:</strong>

    {{ $transaction->currency_symbol }}
    {{ number_format($transaction->supplier_net_amount, 2) }}
</p>

<p>
    You may withdraw your earnings from your wallet dashboard.
</p>

@include('david-oghi-notifications-system::email.partials.transaction_context')
