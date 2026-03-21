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
        $pdf = Pdf::loadView('modules.invoice::pdf', ['invoice' => $invoice]);
        return $pdf->output();
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
