<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            line-height: 1.6;
            color: #333;
        }
        .invoice-header {
            padding: 20px 0;
            border-bottom: 2px solid #eee;
            margin-bottom: 30px;
        }
        .invoice-title {
            font-size: 28px;
            color: #2d3748;
            margin: 0;
        }
        .company-details {
            float: right;
            text-align: right;
        }
        .customer-details {
            margin-bottom: 30px;
        }
        .invoice-info {
            margin-bottom: 30px;
        }
        .invoice-info table {
            width: 100%;
        }
        .invoice-info td {
            padding: 5px 0;
        }
        .items-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 30px;
        }
        .items-table th {
            background-color: #f8f9fa;
            border-bottom: 2px solid #dee2e6;
            padding: 12px;
            text-align: left;
        }
        .items-table td {
            padding: 12px;
            border-bottom: 1px solid #dee2e6;
        }
        .total-section {
            float: right;
            width: 300px;
        }
        .total-row {
            padding: 8px 0;
            border-bottom: 1px solid #dee2e6;
        }
        .total-row.final {
            border-bottom: 2px solid #333;
            font-weight: bold;
            font-size: 16px;
        }
        .text-right {
            text-align: right;
        }
        .clearfix::after {
            content: "";
            clear: both;
            display: table;
        }
    </style>
</head>
<body>
    @php($company = $company ?? [])
    @php($cur = function_exists('currency_symbol') ? (currency_symbol() ?: '$') : '$')
    <div class="invoice-header clearfix">
        <div class="company-details">
            <h1 class="invoice-title">INVOICE</h1>
            @if(!empty($company['logo']))
                <img src="{{ $company['logo'] }}" alt="{{ $company['name'] ?? '' }}" style="max-height:60px; max-width:220px; margin-bottom:8px;"><br>
            @endif
            <strong>{{ $company['name'] ?? config('app.name') }}</strong><br>
            @if(!empty($company['address'])){{ $company['address'] }}<br>@endif
            @if(!empty($company['city']) || !empty($company['country']))
                {{ trim(($company['city'] ?? '') . (!empty($company['city']) && !empty($company['country']) ? ', ' : '') . ($company['country'] ?? '')) }}<br>
            @endif
            @if(!empty($company['vat_number']))VAT: {{ $company['vat_number'] }}<br>@endif
            @if(!empty($company['company_number']))Company No: {{ $company['company_number'] }}<br>@endif
        </div>
    </div>

    <div class="customer-details">
        <strong>Bill To:</strong><br>
        {{ $invoice->customer->getFullName() }}<br>
    </div>

    <div class="invoice-info">
        <table>
            <tr>
                <td><strong>Invoice Number:</strong></td>
                <td>{{ $invoice->invoice_number }}</td>
                <td><strong>Invoice Date:</strong></td>
                <td>{{ $invoice->invoice_date?->format('M d, Y') ?? now()->format('M d, Y') }}</td>
            </tr>
            <tr>
                <td><strong>Reference:</strong></td>
                <td>{{ $invoice->reference_number }}</td>
                <td><strong>Due Date:</strong></td>
                <td>{{ $invoice->due_date?->format('M d, Y') ?? 'N/A' }}</td>
            </tr>
        </table>
    </div>

    <table class="items-table">
        <thead>
            <tr>
                <th>Item</th>
                <th>Description</th>
                <th class="text-right">Price</th>
                <th class="text-right">Quantity</th>
                <th class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            @foreach($invoice->items as $item)
            <tr>
                <td>{{ $item->name }}</td>
                <td>{{ $item->description }}</td>
                <td class="text-right">{{ $cur }}{{ $item->formatted_price }}</td>
                <td class="text-right">{{ $item->quantity }}</td>
                <td class="text-right">{{ $cur }}{{ $item->formatted_subtotal }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-section">
        <div class="total-row">
            <table width="100%">
                <tr>
                    <td><strong>Subtotal:</strong></td>
                    <td class="text-right">{{ $cur }}{{ $invoice->formatted_sub_total }}</td>
                </tr>
            </table>
        </div>
        @if($invoice->discount_val > 0)
        <div class="total-row">
            <table width="100%">
                <tr>
                    <td><strong>Discount:</strong></td>
                    <td class="text-right">{{ $cur }}{{ $invoice->formatted_discount_val }}</td>
                </tr>
            </table>
        </div>
        @endif
        <div class="total-row final">
            <table width="100%">
                <tr>
                    <td><strong>Total:</strong></td>
                    <td class="text-right">{{ $cur }}{{ $invoice->formatted_total }}</td>
                </tr>
            </table>
        </div>
    </div>

    @if(!empty($company['bank_details']) || !empty($company['additional_info']))
        <div class="clearfix"></div>
        @if(!empty($company['bank_details']))
        <div style="margin-top:40px; padding-top:16px; border-top:1px solid #dee2e6; white-space:pre-line;">
            <strong>Bank transfer details</strong><br>
            {{ $company['bank_details'] }}
        </div>
        @endif
        @if(!empty($company['additional_info']))
        <div style="margin-top:16px; white-space:pre-line;">
            {{ $company['additional_info'] }}
        </div>
        @endif
    @endif
</body>
</html>
