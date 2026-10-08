<?php

namespace Modules\Invoice\Services;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use Modules\Invoice\Mail\InvoiceMail;
use Modules\Invoice\Models\Invoice;
use Modules\Invoice\Models\InvoiceItem;
use Modules\Order\Models\Order;

class InvoiceService
{
    public function generateInvoice(array $params = []): array
    {
        try {
            // Validate required parameters
            if (!isset($params['customer_id'])) {
                return [
                    'error' => true,
                    'message' => 'Customer ID is required'
                ];
            }

            // Generate invoice number
            $prefix = $params['prefix'] ?? '';
            $invoice_number = $prefix . '-' . Invoice::getNextInvoiceNumber($prefix);

            // Create new invoice
            $invoice = new Invoice();
            $invoice->invoice_number = $invoice_number;
            $invoice->reference_number = $params['reference_number'] ?? null;
            $invoice->customer_id = $params['customer_id'];
            $invoice->company_id = $params['company_id'] ?? null;
            $invoice->invoice_template_id = $params['invoice_template_id'] ?? null;
            $invoice->status = $params['status'] ?? Invoice::STATUS_DRAFT;
            $invoice->paid_status = $params['paid_status'] ?? Invoice::STATUS_UNPAID;
            $invoice->invoice_date = $params['invoice_date'] ?? now();
            $invoice->due_date = $params['due_date'] ?? null;
            $invoice->sub_total = $params['sub_total'] ?? 0;
            $invoice->discount = $params['discount'] ?? null;
            $invoice->discount_type = $params['discount_type'] ?? null;
            $invoice->discount_val = $params['discount_val'] ?? 0;
            $invoice->total = $params['total'] ?? 0;
            $invoice->due_amount = $params['due_amount'] ?? 0;
            $invoice->tax_per_item = $params['tax_per_item'] ?? false;
            $invoice->discount_per_item = $params['discount_per_item'] ?? false;
            $invoice->tax = $params['tax'] ?? null;
            $invoice->notes = $params['notes'] ?? null;
            $invoice->unique_hash = $params['unique_hash'] ?? md5(uniqid());
            $invoice->save();

            return [
                'success' => true,
                'invoice_id' => $invoice->id,
                'message' => 'Invoice generated successfully'
            ];

        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => $e->getMessage()
            ];
        }
    }

    public function getInvoiceById(int $invoice_id): ?Invoice
    {
        return Invoice::with(['items', 'customer'])->find($invoice_id);
    }

    public function getAllInvoices(): array
    {
        return Invoice::with(['items', 'customer'])->get()->toArray();
    }

    public function getInvoicesByCustomerId(int $customer_id): array
    {
        return Invoice::with(['items'])
            ->where('customer_id', $customer_id)
            ->get()
            ->toArray();
    }

    public function saveInvoice(array $data): array
    {
        try {
            $invoice = Invoice::updateOrCreate(
                ['id' => $data['id'] ?? null],
                $data
            );

            return [
                'success' => true,
                'invoice_id' => $invoice->id,
                'success_edit' => true
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => $e->getMessage()
            ];
        }
    }

    public function deleteInvoice(int $invoice_id): array
    {
        try {
            $invoice = Invoice::find($invoice_id);
            
            if (!$invoice) {
                return [
                    'status' => 'failed',
                    'message' => 'Invoice not found'
                ];
            }

            $invoice->delete();
            
            return [
                'status' => 'success',
                'message' => 'Invoice deleted successfully'
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'failed',
                'message' => $e->getMessage()
            ];
        }
    }

    public function updateInvoiceStatus(int $invoice_id, string $status): array
    {
        try {
            $invoice = Invoice::find($invoice_id);
            
            if (!$invoice) {
                return [
                    'error' => true,
                    'message' => 'Invoice not found'
                ];
            }

            if (!in_array($status, [
                Invoice::STATUS_DRAFT,
                Invoice::STATUS_SENT,
                Invoice::STATUS_VIEWED,
                Invoice::STATUS_OVERDUE,
                Invoice::STATUS_PAID,
                Invoice::STATUS_COMPLETED,
                Invoice::STATUS_VOID
            ])) {
                return [
                    'error' => true,
                    'message' => 'Invalid status'
                ];
            }

            $invoice->status = $status;
            $invoice->save();

            return [
                'success' => true,
                'message' => 'Invoice status updated successfully'
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => $e->getMessage()
            ];
        }
    }

public function updateInvoicePaidStatus(int $invoice_id, string $paid_status): array
    {
        try {
            $invoice = Invoice::find($invoice_id);

            if (!$invoice) {
                return [
                    'error' => true,
                    'message' => 'Invoice not found'
                ];
            }

            if (!in_array($paid_status, [
                Invoice::STATUS_UNPAID,
                Invoice::STATUS_PARTIALLY_PAID,
                Invoice::STATUS_PAID,
                Invoice::STATUS_REFUNDED
            ])) {
                return [
                    'error' => true,
                    'message' => 'Invalid paid status'
                ];
            }

            $invoice->paid_status = $paid_status;
            $invoice->save();

            return [
                'success' => true,
                'message' => 'Invoice paid status updated successfully'
            ];
        } catch (\Exception $e) {
            return [
                'error' => true,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Generate PDF for an invoice.
     *
     * @param Invoice $invoice
     * @return string The PDF content as a string
     */
    public function generatePdf(Invoice $invoice): string
    {
        $pdf = Pdf::loadView('modules.invoice::pdf', [
            'invoice' => $invoice,
            'company' => $this->getCompanyDetails(),
        ]);
        return $pdf->output();
    }

    /**
     * Collect the company/invoice details configured on the shop invoice
     * settings page (admin/admin-shop-invoices-page) so they can be rendered
     * on the generated invoice (PDF + email). Falls back to the app name when
     * no company name has been configured.
     *
     * @return array<string, mixed>
     */
    public function getCompanyDetails(): array
    {
        $countries = [
            'US' => 'United States', 'CA' => 'Canada', 'GB' => 'United Kingdom',
            'AU' => 'Australia', 'DE' => 'Germany', 'NL' => 'Netherlands',
            'SE' => 'Sweden', 'NO' => 'Norway', 'DK' => 'Denmark', 'FI' => 'Finland',
            'IE' => 'Ireland', 'CH' => 'Switzerland', 'AT' => 'Austria',
            'BE' => 'Belgium', 'LU' => 'Luxembourg', 'FR' => 'France', 'IT' => 'Italy',
            'ES' => 'Spain', 'PT' => 'Portugal', 'GR' => 'Greece', 'CZ' => 'Czech Republic',
            'PL' => 'Poland', 'HU' => 'Hungary', 'RO' => 'Romania', 'BG' => 'Bulgaria',
            'HR' => 'Croatia', 'RS' => 'Serbia', 'SI' => 'Slovenia', 'SK' => 'Slovakia',
            'LT' => 'Lithuania', 'LV' => 'Latvia', 'EE' => 'Estonia', 'MT' => 'Malta',
            'CY' => 'Cyprus',
        ];

        $countryCode = (string) get_option('invoice_company_country', 'shop');

        return [
            'enabled'       => $this->invoicingEnabled(),
            'name'          => get_option('invoice_company_name', 'shop') ?: config('app.name'),
            'logo'          => $this->resolveCompanyLogo(),
            'country_code'  => $countryCode,
            'country'       => $countries[$countryCode] ?? $countryCode,
            'city'          => (string) get_option('invoice_company_city', 'shop'),
            'address'       => (string) get_option('invoice_company_address', 'shop'),
            'vat_number'    => (string) get_option('invoice_company_vat_number', 'shop'),
            'company_number' => (string) get_option('invoice_id_company_number', 'shop'),
            'bank_details'  => (string) get_option('invoice_company_bank_details', 'shop'),
            'additional_info' => (string) get_option('invoice_company_additional_info', 'shop'),
        ];
    }

    /**
     * Whether invoicing is enabled on the shop invoice settings page.
     * Unset (never saved) is treated as enabled so existing sites keep working;
     * only an explicit "off" disables it.
     */
    public function invoicingEnabled(): bool
    {
        $value = get_option('enable_invoices', 'shop');

        // Never configured -> enabled (don't break existing sites).
        if ($value === null) {
            return true;
        }

        if (is_bool($value)) {
            return $value;
        }

        // Only an explicit truthy value keeps it enabled; '0'/''/'n' disable it.
        return in_array(strtolower(trim((string) $value)), ['1', 'y', 'yes', 'true', 'on'], true);
    }

    /**
     * Whether a draft invoice should be auto-generated for each new order.
     * Opt-in: disabled unless explicitly enabled on the shop invoice settings
     * page, and only when invoicing itself is enabled.
     */
    public function autoGenerateEnabled(): bool
    {
        if (! $this->invoicingEnabled()) {
            return false;
        }

        $value = get_option('auto_generate_invoices', 'shop');

        if ($value === null || $value === '') {
            return false;
        }

        if (is_bool($value)) {
            return $value;
        }

        return in_array(strtolower(trim((string) $value)), ['1', 'y', 'yes', 'true', 'on'], true);
    }

    /**
     * Resolve the configured company logo to a base64 data URI so DomPDF can
     * embed it without remote fetching (enable_remote is off by default).
     * Returns null when no readable local logo file is configured.
     */
    private function resolveCompanyLogo(): ?string
    {
        $logo = trim((string) get_option('invoice_company_logo', 'shop'));

        if ($logo === '') {
            return null;
        }

        // Reduce a full URL to its path, then try the common local roots.
        $path = parse_url($logo, PHP_URL_PATH) ?: $logo;
        $path = ltrim($path, '/');

        $candidates = [
            $logo,                              // already an absolute filesystem path
            public_path($path),
            base_path($path),
        ];

        foreach ($candidates as $candidate) {
            if ($candidate && is_file($candidate) && is_readable($candidate)) {
                $data = @file_get_contents($candidate);
                if ($data === false) {
                    continue;
                }
                $ext = strtolower(pathinfo($candidate, PATHINFO_EXTENSION));
                $mime = match ($ext) {
                    'png' => 'image/png',
                    'gif' => 'image/gif',
                    'svg' => 'image/svg+xml',
                    'webp' => 'image/webp',
                    default => 'image/jpeg',
                };

                return 'data:' . $mime . ';base64,' . base64_encode($data);
            }
        }

        return null;
    }

    /**
     * Send invoice via email.
     *
     * @param int $invoice_id
     * @param string|null $toEmail
     * @param string|null $customMessage
     * @return array
     */
    public function sendInvoiceEmail(int $invoice_id, ?string $toEmail = null, ?string $customMessage = null): array
    {
        try {
            $invoice = Invoice::with(['items', 'customer'])->find($invoice_id);

            if (!$invoice) {
                return [
                    'error' => true,
                    'message' => 'Invoice not found'
                ];
            }

            // Determine recipient email
            $recipientEmail = $toEmail ?? $invoice->customer?->email;

            if (!$recipientEmail) {
                return [
                    'error' => true,
                    'message' => 'No recipient email address available'
                ];
            }

            // Generate PDF
            $pdfContent = $this->generatePdf($invoice);

            // Send email
            Mail::to($recipientEmail)->send(new InvoiceMail($invoice, $pdfContent, $customMessage));

            // Update invoice status to sent if currently draft
            if ($invoice->status === Invoice::STATUS_DRAFT) {
                $invoice->markAsSent();
            }

            Log::info('Invoice email sent', [
                'invoice_id' => $invoice_id,
                'invoice_number' => $invoice->invoice_number,
                'recipient' => $recipientEmail
            ]);

            return [
                'success' => true,
                'message' => 'Invoice sent successfully to ' . $recipientEmail
            ];
        } catch (\Exception $e) {
            Log::error('Failed to send invoice email', [
                'invoice_id' => $invoice_id,
                'error' => $e->getMessage()
            ]);

            return [
                'error' => true,
                'message' => 'Failed to send invoice: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Generate invoice from order.
     *
     * @param int $order_id
     * @param array $params
     * @return array
     */
    public function generateFromOrder(int $order_id, array $params = []): array
    {
        try {
            $order = Order::with(['customer', 'cart.products'])->find($order_id);

            if (!$order) {
                return [
                    'error' => true,
                    'message' => 'Order not found'
                ];
            }

            // Check if invoice already exists for this order
            $existingInvoice = Invoice::where('reference_number', 'ORDER-' . $order->order_reference_id)->first();
            if ($existingInvoice) {
                return [
                    'success' => true,
                    'invoice_id' => $existingInvoice->id,
                    'message' => 'Invoice already exists for this order'
                ];
            }

            // Generate invoice number
            $prefix = $params['prefix'] ?? 'INV';
            $invoice_number = $prefix . '-' . Invoice::getNextInvoiceNumber($prefix);

            // Calculate totals from order
            $subTotal = 0;
            $cartItems = [];

            if ($order->cart) {
                foreach ($order->cart as $cartItem) {
                    $itemPrice = $cartItem->price * 100; // Convert to cents
                    $itemTotal = $itemPrice * $cartItem->qty;
                    $subTotal += $itemTotal;

                    $cartItems[] = [
                        'name' => $cartItem->product?->title ?? 'Product',
                        'description' => $cartItem->product?->description ?? '',
                        'price' => $itemPrice,
                        'quantity' => $cartItem->qty
                    ];
                }
            }

            $discount = $order->discount_value ? ($order->discount_value * 100) : 0;
            $tax = $order->taxes_amount ? ($order->taxes_amount * 100) : 0;
            $total = ($subTotal - $discount + $tax);

            // Create invoice
            $invoice = new Invoice();
            $invoice->invoice_number = $invoice_number;
            $invoice->reference_number = 'ORDER-' . $order->order_reference_id;
            $invoice->customer_id = $order->customer_id;
            $invoice->company_id = $params['company_id'] ?? 0;
            $invoice->invoice_template_id = $params['invoice_template_id'] ?? null;
            $invoice->status = $params['status'] ?? Invoice::STATUS_DRAFT;
            $invoice->paid_status = $order->is_paid ? Invoice::STATUS_PAID : Invoice::STATUS_UNPAID;
            $invoice->invoice_date = $params['invoice_date'] ?? now();
            $invoice->due_date = $params['due_date'] ?? now()->addDays(14);
            $invoice->sub_total = $subTotal;
            $invoice->discount_val = $discount;
            $invoice->total = $total;
            $invoice->due_amount = $total;
            $invoice->tax = $tax;
            $invoice->notes = $params['notes'] ?? null;
            $invoice->unique_hash = Invoice::generateUniqueHash();
            $invoice->save();

            // Create invoice items from order cart
            foreach ($cartItems as $cartItem) {
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'name' => $cartItem['name'],
                    'description' => $cartItem['description'],
                    'price' => $cartItem['price'],
                    'quantity' => $cartItem['quantity']
                ]);
            }

// Update order with invoice_id
        $order->invoice_id = $invoice->id;
        $order->save();

        Log::info('Invoice generated from order', [
            'order_id' => $order_id,
            'invoice_id' => $invoice->id,
            'invoice_number' => $invoice->invoice_number
        ]);

        return [
            'success' => true,
            'invoice_id' => $invoice->id,
            'invoice_number' => $invoice->invoice_number,
            'message' => 'Invoice generated successfully from order'
        ];
        } catch (\Exception $e) {
            Log::error('Failed to generate invoice from order', [
                'order_id' => $order_id,
                'error' => $e->getMessage()
            ]);

            return [
                'error' => true,
                'message' => 'Failed to generate invoice: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Download invoice PDF.
     *
     * @param int $invoice_id
     * @return \Symfony\Component\HttpFoundation\StreamedResponse|false
     */
    public function downloadPdf(int $invoice_id)
    {
        try {
            $invoice = Invoice::with(['items', 'customer'])->find($invoice_id);

            if (!$invoice) {
                return false;
            }

            $pdf = Pdf::loadView('modules.invoice::pdf', ['invoice' => $invoice]);

            return response()->streamDownload(function () use ($pdf) {
                echo $pdf->output();
            }, $invoice->invoice_number . '.pdf');
        } catch (\Exception $e) {
            Log::error('Failed to download invoice PDF', [
                'invoice_id' => $invoice_id,
                'error' => $e->getMessage()
            ]);

            return false;
        }
    }
}
