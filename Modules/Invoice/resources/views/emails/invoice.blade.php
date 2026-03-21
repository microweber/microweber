<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
        }
        .header {
            text-align: center;
            padding: 30px 0;
            border-bottom: 2px solid #eee;
        }
        .header h1 {
            margin: 0;
            color: #2d3748;
            font-size: 24px;
        }
        .content {
            padding: 30px 0;
        }
        .invoice-details {
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .invoice-details table {
            width: 100%;
        }
        .invoice-details td {
            padding: 8px 0;
        }
        .invoice-details td:first-child {
            font-weight: 600;
            color: #4a5568;
        }
        .total-box {
            background-color: #2d3748;
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            margin: 20px 0;
        }
        .total-box .amount {
            font-size: 32px;
            font-weight: bold;
            margin: 10px 0;
        }
        .cta-button {
            display: inline-block;
            background-color: #4299e1;
            color: white;
            text-decoration: none;
            padding: 12px 30px;
            border-radius: 6px;
            margin: 20px 0;
            font-weight: 600;
        }
        .footer {
            text-align: center;
            padding: 20px 0;
            color: #718096;
            font-size: 14px;
            border-top: 1px solid #eee;
        }
        @media only screen and (max-width: 600px) {
            .container {
                padding: 10px;
            }
            .total-box .amount {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>INVOICE</h1>
            <p style="color: #718096; margin: 10px 0 0 0;">From {{ config('app.name') }}</p>
        </div>

        <div class="content">
            @if($customMessage)
                <p style="margin-bottom: 20px;">{{ $customMessage }}</p>
            @else
                <p style="margin-bottom: 20px;">Dear {{ $invoice->customer?->getFullName() ?? 'Customer' }},</p>
                <p>Please find your invoice attached below. Thank you for your business!</p>
            @endif

            <div class="invoice-details">
                <table>
                    <tr>
                        <td>Invoice Number:</td>
                        <td>{{ $invoice->invoice_number }}</td>
                    </tr>
                    <tr>
                        <td>Invoice Date:</td>
                        <td>{{ $invoice->invoice_date?->format('M d, Y') ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>Due Date:</td>
                        <td>{{ $invoice->due_date?->format('M d, Y') ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <td>Status:</td>
                        <td>
                            <span style="
                                display: inline-block;
                                padding: 4px 12px;
                                border-radius: 4px;
                                font-size: 12px;
                                font-weight: 600;
                                text-transform: uppercase;
                                @switch($invoice->status)
                                    @case('paid')
                                    @case('completed')
                                        background-color: #c6f6d5;
                                        color: #276749;
                                        @break
                                    @case('overdue')
                                        background-color: #fed7d7;
                                        color: #c53030;
                                        @break
                                    @case('sent')
                                    @case('viewed')
                                        background-color: #bee3f8;
                                        color: #2c5282;
                                        @break
                                    @default
                                        background-color: #e2e8f0;
                                        color: #4a5568;
                                @endswitch
                            ">
                                {{ ucfirst($invoice->status) }}
                            </span>
                        </td>
                    </tr>
                </table>
            </div>

            <div class="total-box">
                <div style="font-size: 14px; opacity: 0.9;">Amount Due</div>
                <div class="amount">${{ $invoice->formatted_due_amount }}</div>
                @if($invoice->isOverdue())
                    <div style="color: #fc8181; font-size: 14px; margin-top: 10px;">⚠️ This invoice is overdue</div>
                @endif
            </div>

            <p style="text-align: center; margin: 30px 0;">
                <a href="{{ url('/invoices/' . $invoice->unique_hash) }}" class="cta-button" style="color: white;">View Invoice Online</a>
            </p>

            @if($invoice->notes)
                <div style="background-color: #fffbeb; border-left: 4px solid #f59e0b; padding: 15px; margin: 20px 0;">
                    <strong>Notes:</strong><br>
                    {{ $invoice->notes }}
                </div>
            @endif
        </div>

        <div class="footer">
            <p>If you have any questions about this invoice, please contact us.</p>
            <p style="margin-top: 20px;">
                &copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
            </p>
        </div>
    </div>
</body>
</html>
