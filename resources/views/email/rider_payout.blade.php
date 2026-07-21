<h2>Rider Payout Completed 🚚</h2>

<p>Hello,</p>

<p>
    Your rider payout has been processed successfully.
</p>

<p>
    <strong>Amount:</strong>

    {{ $transaction->currency_symbol }}
    {{ number_format($transaction->transaction_amount, 2) }}
</p>

<p>
    Thank you for delivering excellent service.
</p>

@include('david-oghi-notifications-system::email.partials.transaction_context')
