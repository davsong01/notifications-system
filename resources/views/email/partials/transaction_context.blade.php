@php
    $transactionMeta = $meta['transaction'] ?? [];
    $orderMeta = $meta['order'] ?? [];
    $customerMeta = $meta['customer'] ?? [];
    $addressMeta = $meta['address'] ?? [];
    $statuses = $meta['statuses'] ?? [];

    $currencySymbol = $transactionMeta['currency_symbol']
        ?? ($transaction->currency_symbol ?? config('david-oghi-notifications-system.currency.symbol', '$'));

    $reference = $transactionMeta['reference'] ?? $transaction->reference;
    $amount = (float) ($transactionMeta['amount'] ?? $transaction->transaction_amount ?? 0);
    $paidAmount = (float) ($transactionMeta['customer_paid_amount'] ?? $transaction->customer_paid_amount ?? $amount);
@endphp

<div style="margin-top:28px;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;">
    <div style="background:#f8fafc;padding:14px 18px;font-weight:700;color:#0f172a;">
        Transaction Details
    </div>

    <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
        <tbody>
            <tr>
                <td style="padding:12px 18px;border-top:1px solid #e2e8f0;color:#64748b;width:35%;">Reference</td>
                <td style="padding:12px 18px;border-top:1px solid #e2e8f0;color:#0f172a;font-weight:600;">{{ $reference }}</td>
            </tr>
            <tr>
                <td style="padding:12px 18px;border-top:1px solid #e2e8f0;color:#64748b;">Amount</td>
                <td style="padding:12px 18px;border-top:1px solid #e2e8f0;color:#0f172a;font-weight:600;">{{ $currencySymbol }}{{ number_format($amount, 2) }}</td>
            </tr>
            <tr>
                <td style="padding:12px 18px;border-top:1px solid #e2e8f0;color:#64748b;">Paid Amount</td>
                <td style="padding:12px 18px;border-top:1px solid #e2e8f0;color:#0f172a;font-weight:600;">{{ $currencySymbol }}{{ number_format($paidAmount, 2) }}</td>
            </tr>
            <tr>
                <td style="padding:12px 18px;border-top:1px solid #e2e8f0;color:#64748b;">Transaction Status</td>
                <td style="padding:12px 18px;border-top:1px solid #e2e8f0;color:#0f172a;font-weight:600;">{{ str($statuses['transaction'] ?? $transaction->status ?? 'pending')->headline() }}</td>
            </tr>
            @if(!empty($orderMeta['reference']) || $transaction->order)
                <tr>
                    <td style="padding:12px 18px;border-top:1px solid #e2e8f0;color:#64748b;">Order Reference</td>
                    <td style="padding:12px 18px;border-top:1px solid #e2e8f0;color:#0f172a;font-weight:600;">{{ $orderMeta['reference'] ?? $transaction->order?->reference }}</td>
                </tr>
                <tr>
                    <td style="padding:12px 18px;border-top:1px solid #e2e8f0;color:#64748b;">Payment Status</td>
                    <td style="padding:12px 18px;border-top:1px solid #e2e8f0;color:#0f172a;font-weight:600;">{{ str($statuses['payment'] ?? $transaction->order?->payment_status ?? 'pending')->headline() }}</td>
                </tr>
                <tr>
                    <td style="padding:12px 18px;border-top:1px solid #e2e8f0;color:#64748b;">Delivery Status</td>
                    <td style="padding:12px 18px;border-top:1px solid #e2e8f0;color:#0f172a;font-weight:600;">{{ str($statuses['delivery'] ?? $transaction->order?->delivery_status ?? 'pending')->headline() }}</td>
                </tr>
                <tr>
                    <td style="padding:12px 18px;border-top:1px solid #e2e8f0;color:#64748b;">Order Status</td>
                    <td style="padding:12px 18px;border-top:1px solid #e2e8f0;color:#0f172a;font-weight:600;">{{ str($statuses['order'] ?? $transaction->order?->order_status ?? 'pending')->headline() }}</td>
                </tr>
            @endif
        </tbody>
    </table>
</div>

<div style="margin-top:18px;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;">
    <div style="background:#f8fafc;padding:14px 18px;font-weight:700;color:#0f172a;">
        Customer Details
    </div>

    <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
        <tbody>
            <tr>
                <td style="padding:12px 18px;border-top:1px solid #e2e8f0;color:#64748b;width:35%;">Name</td>
                <td style="padding:12px 18px;border-top:1px solid #e2e8f0;color:#0f172a;font-weight:600;">{{ $customerMeta['name'] ?? $transaction->user?->fullname ?? $transaction->user?->name ?? 'Guest' }}</td>
            </tr>
            <tr>
                <td style="padding:12px 18px;border-top:1px solid #e2e8f0;color:#64748b;">Email</td>
                <td style="padding:12px 18px;border-top:1px solid #e2e8f0;color:#0f172a;font-weight:600;">{{ $customerMeta['email'] ?? $transaction->user?->email ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td style="padding:12px 18px;border-top:1px solid #e2e8f0;color:#64748b;">Phone</td>
                <td style="padding:12px 18px;border-top:1px solid #e2e8f0;color:#0f172a;font-weight:600;">{{ $customerMeta['phone'] ?? $transaction->user?->phone ?? 'N/A' }}</td>
            </tr>
        </tbody>
    </table>
</div>

@if(!empty($addressMeta['address']) || !empty($addressMeta['country']) || !empty($addressMeta['state']) || !empty($addressMeta['local_government']))
    <div style="margin-top:18px;border:1px solid #e2e8f0;border-radius:12px;overflow:hidden;">
        <div style="background:#f8fafc;padding:14px 18px;font-weight:700;color:#0f172a;">
            Delivery Address
        </div>

        <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
            <tbody>
                @if(!empty($addressMeta['title']))
                    <tr>
                        <td style="padding:12px 18px;border-top:1px solid #e2e8f0;color:#64748b;width:35%;">Label</td>
                        <td style="padding:12px 18px;border-top:1px solid #e2e8f0;color:#0f172a;font-weight:600;">{{ $addressMeta['title'] }}</td>
                    </tr>
                @endif
                @if(!empty($addressMeta['address']))
                    <tr>
                        <td style="padding:12px 18px;border-top:1px solid #e2e8f0;color:#64748b;">Address</td>
                        <td style="padding:12px 18px;border-top:1px solid #e2e8f0;color:#0f172a;font-weight:600;">{{ $addressMeta['address'] }}</td>
                    </tr>
                @endif
                @if(!empty($addressMeta['local_government']))
                    <tr>
                        <td style="padding:12px 18px;border-top:1px solid #e2e8f0;color:#64748b;">Local Government</td>
                        <td style="padding:12px 18px;border-top:1px solid #e2e8f0;color:#0f172a;font-weight:600;">{{ $addressMeta['local_government'] }}</td>
                    </tr>
                @endif
                @if(!empty($addressMeta['state']))
                    <tr>
                        <td style="padding:12px 18px;border-top:1px solid #e2e8f0;color:#64748b;">State</td>
                        <td style="padding:12px 18px;border-top:1px solid #e2e8f0;color:#0f172a;font-weight:600;">{{ $addressMeta['state'] }}</td>
                    </tr>
                @endif
                @if(!empty($addressMeta['country']))
                    <tr>
                        <td style="padding:12px 18px;border-top:1px solid #e2e8f0;color:#64748b;">Country</td>
                        <td style="padding:12px 18px;border-top:1px solid #e2e8f0;color:#0f172a;font-weight:600;">{{ $addressMeta['country'] }}</td>
                    </tr>
                @endif
            </tbody>
        </table>
    </div>
@endif
