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
    <div class="invoice-header clearfix">
        <div class="company-details">
            <h1 class="invoice-title">INVOICE</h1>
            <p>{{ config('app.name') }}</p>
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
                <td>{{ $invoice->invoice_date->format('M d, Y') }}</td>
            </tr>
            <tr>
                <td><strong>Reference:</strong></td>
                <td>{{ $invoice->reference_number }}</td>
                <td><strong>Due Date:</strong></td>
                <td>{{ $invoice->due_date->format('M d, Y') }}</td>
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
                <td class="text-right">${{ $item->formatted_price }}</td>
                <td class="text-right">{{ $item->quantity }}</td>
                <td class="text-right">${{ $item->formatted_subtotal }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="total-section">
        <div class="total-row">
            <table width="100%">
                <tr>
                    <td><strong>Subtotal:</strong></td>
                    <td class="text-right">${{ $invoice->formatted_sub_total }}</td>
                </tr>
            </table>
        </div>
        @if($invoice->discount_val > 0)
        <div class="total-row">
            <table width="100%">
                <tr>
                    <td><strong>Discount:</strong></td>
                    <td class="text-right">${{ $invoice->formatted_discount_val }}</td>
                </tr>
            </table>
        </div>
        @endif
        <div class="total-row final">
            <table width="100%">
                <tr>
                    <td><strong>Total:</strong></td>
                    <td class="text-right">${{ $invoice->formatted_total }}</td>
                </tr>
            </table>
        </div>
    </div>
</body>
</html>
