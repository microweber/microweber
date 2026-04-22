<?php

declare(strict_types=1);

namespace Modules\Invoice\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Modules\Invoice\Http\Resources\InvoiceResource;
use Modules\Invoice\Models\Invoice;
use Symfony\Component\HttpFoundation\Response;

class InvoicesApiController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection|JsonResponse
    {
        // Invoices always require admin — they contain billing/customer data
        // that should never be exposed to anonymous or regular users.
        if (!$request->user()) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required.',
            ], Response::HTTP_UNAUTHORIZED);
        }
        if ((int) $request->user()->is_admin !== 1) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.',
            ], Response::HTTP_FORBIDDEN);
        }

        try {
            $limit = (int) $request->get('limit', 30);
            $query = Invoice::query();

            if ($request->filled('customer_id')) {
                $query->where('customer_id', (int) $request->input('customer_id'));
            }
            if ($request->filled('status')) {
                $query->where('status', (string) $request->input('status'));
            }
            if ($request->filled('paid_status')) {
                $query->where('paid_status', (string) $request->input('paid_status'));
            }
            if ($request->filled('search')) {
                $term = (string) $request->input('search');
                $query->where(function ($q) use ($term) {
                    $q->where('invoice_number', 'like', "%{$term}%")
                        ->orWhere('reference_number', 'like', "%{$term}%");
                });
            }

            $invoices = $query->orderByDesc('id')->paginate($limit);
            $invoices->appends($request->except('page'));

            return InvoiceResource::collection($invoices);
        } catch (\Illuminate\Database\QueryException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid query parameters',
                'data' => [],
            ], Response::HTTP_BAD_REQUEST);
        }
    }

    public function store(Request $request): JsonResponse
    {
        if (!$request->user()) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required.',
            ], Response::HTTP_UNAUTHORIZED);
        }
        if ((int) $request->user()->is_admin !== 1) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.',
            ], Response::HTTP_FORBIDDEN);
        }

        $validator = Validator::make($request->all(), [
            'invoice_number' => 'nullable|string|max:255|unique:invoices,invoice_number',
            'reference_number' => 'nullable|string|max:255',
            'invoice_date' => 'nullable|date',
            'due_date' => 'nullable|date',
            'customer_id' => 'nullable|integer',
            'company_id' => 'nullable|integer',
            'invoice_template_id' => 'nullable|integer',
            'status' => 'nullable|string|max:50',
            'paid_status' => 'nullable|string|max:50',
            'sub_total' => 'nullable|integer',
            'discount' => 'nullable|numeric',
            'discount_type' => 'nullable|string|max:50',
            'discount_val' => 'nullable|integer',
            'total' => 'nullable|integer',
            'due_amount' => 'nullable|integer',
            'tax' => 'nullable|numeric',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $data = $validator->validated();
            $data['invoice_number'] = $data['invoice_number']
                ?? 'INV-' . now()->format('YmdHis') . '-' . random_int(1000, 9999);
            $data['unique_hash'] = (string) Str::uuid();
            $data['status'] ??= Invoice::STATUS_DRAFT;
            $data['paid_status'] ??= Invoice::STATUS_UNPAID;
            $data['sub_total'] ??= 0;
            $data['total'] ??= 0;
            $data['due_amount'] ??= 0;

            $invoice = Invoice::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Invoice created successfully',
                'data' => new InvoiceResource($invoice->fresh()),
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create invoice',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(Request $request, int $id): JsonResponse
    {
        if (!$request->user()) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required.',
            ], Response::HTTP_UNAUTHORIZED);
        }
        if ((int) $request->user()->is_admin !== 1) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.',
            ], Response::HTTP_FORBIDDEN);
        }

        $invoice = Invoice::find($id);

        if (!$invoice) {
            return response()->json([
                'success' => false,
                'message' => 'Invoice not found',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'success' => true,
            'data' => new InvoiceResource($invoice),
        ]);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        if (!$request->user()) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required.',
            ], Response::HTTP_UNAUTHORIZED);
        }
        if ((int) $request->user()->is_admin !== 1) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.',
            ], Response::HTTP_FORBIDDEN);
        }

        $invoice = Invoice::find($id);

        if (!$invoice) {
            return response()->json([
                'success' => false,
                'message' => 'Invoice not found',
            ], Response::HTTP_NOT_FOUND);
        }

        $validator = Validator::make($request->all(), [
            'invoice_number' => 'nullable|string|max:255|unique:invoices,invoice_number,' . $id,
            'reference_number' => 'nullable|string|max:255',
            'invoice_date' => 'nullable|date',
            'due_date' => 'nullable|date',
            'status' => 'nullable|string|max:50',
            'paid_status' => 'nullable|string|max:50',
            'sub_total' => 'nullable|integer',
            'discount' => 'nullable|numeric',
            'discount_val' => 'nullable|integer',
            'total' => 'nullable|integer',
            'due_amount' => 'nullable|integer',
            'tax' => 'nullable|numeric',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $invoice->update($validator->validated());

            return response()->json([
                'success' => true,
                'message' => 'Invoice updated successfully',
                'data' => new InvoiceResource($invoice->fresh()),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update invoice',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        if (!$request->user()) {
            return response()->json([
                'success' => false,
                'message' => 'Authentication required.',
            ], Response::HTTP_UNAUTHORIZED);
        }
        if ((int) $request->user()->is_admin !== 1) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.',
            ], Response::HTTP_FORBIDDEN);
        }

        $invoice = Invoice::find($id);

        if (!$invoice) {
            return response()->json([
                'success' => false,
                'message' => 'Invoice not found',
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            $invoice->delete();

            return response()->json([
                'success' => true,
                'message' => 'Invoice deleted successfully',
                'data' => ['id' => $id],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete invoice',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
