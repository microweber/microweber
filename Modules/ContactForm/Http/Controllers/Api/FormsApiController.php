<?php

declare(strict_types=1);

namespace Modules\ContactForm\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Modules\ContactForm\Http\Resources\FormResource;
use Modules\ContactForm\Models\Form;
use Symfony\Component\HttpFoundation\Response;

class FormsApiController extends Controller
{
    public function index(Request $request): AnonymousResourceCollection|JsonResponse
    {
        try {
            $limit = (int) $request->get('limit', 30);
            $isAdmin = $request->user() !== null
                && (int) $request->user()->is_admin === 1;

            $query = Form::query();

            // Non-admin callers only see active forms; admin sees everything.
            if (!$isAdmin) {
                $query->where(function ($q) {
                    $q->where('is_active', 1)->orWhereNull('is_active');
                });
            }

            if ($request->filled('module_id')) {
                $query->where('module_id', (string) $request->input('module_id'));
            }
            if ($request->filled('list_id')) {
                $query->where('list_id', (int) $request->input('list_id'));
            }
            if ($request->filled('search')) {
                $term = (string) $request->input('search');
                $query->where(function ($q) use ($term) {
                    $q->where('name', 'like', "%{$term}%")
                        ->orWhere('slug', 'like', "%{$term}%");
                });
            }

            $forms = $query->orderByDesc('id')->paginate($limit);
            $forms->appends($request->except('page'));

            return FormResource::collection($forms);
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
        if (!$request->user() || (int) $request->user()->is_admin !== 1) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.',
            ], Response::HTTP_FORBIDDEN);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'module_id' => 'nullable|string|max:255',
            'list_id' => 'nullable|integer',
            'description' => 'nullable|string',
            'confirmation_message' => 'nullable|string',
            'emails_notifications' => 'nullable|string',
            'emails_notifications_subject' => 'nullable|string|max:1000',
            'is_active' => 'nullable|boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $data = $validator->validated();
        $data['is_active'] = array_key_exists('is_active', $data) ? (int) $data['is_active'] : 1;

        try {
            $form = Form::create($data);

            return response()->json([
                'success' => true,
                'message' => 'Form created successfully',
                'data' => new FormResource($form->fresh()),
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create form',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show(Request $request, int $id): JsonResponse
    {
        $form = Form::find($id);

        if (!$form) {
            return response()->json([
                'success' => false,
                'message' => 'Form not found',
            ], Response::HTTP_NOT_FOUND);
        }

        $isAdmin = $request->user() !== null
            && (int) $request->user()->is_admin === 1;

        if (!$isAdmin && $form->is_active !== null && (int) $form->is_active === 0) {
            return response()->json([
                'success' => false,
                'message' => 'Form not found',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'success' => true,
            'data' => new FormResource($form),
        ]);
    }

    public function update(Request $request, int $id): JsonResponse
    {
        if (!$request->user() || (int) $request->user()->is_admin !== 1) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.',
            ], Response::HTTP_FORBIDDEN);
        }

        $form = Form::find($id);

        if (!$form) {
            return response()->json([
                'success' => false,
                'message' => 'Form not found',
            ], Response::HTTP_NOT_FOUND);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'nullable|string|max:255',
            'slug' => 'nullable|string|max:255',
            'module_id' => 'nullable|string|max:255',
            'list_id' => 'nullable|integer',
            'description' => 'nullable|string',
            'confirmation_message' => 'nullable|string',
            'emails_notifications' => 'nullable|string',
            'emails_notifications_subject' => 'nullable|string|max:1000',
            'is_active' => 'nullable|boolean',
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
            if (array_key_exists('is_active', $data)) {
                $data['is_active'] = (int) $data['is_active'];
            }
            $form->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Form updated successfully',
                'data' => new FormResource($form->fresh()),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update form',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function destroy(Request $request, int $id): JsonResponse
    {
        if (!$request->user() || (int) $request->user()->is_admin !== 1) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.',
            ], Response::HTTP_FORBIDDEN);
        }

        $form = Form::find($id);

        if (!$form) {
            return response()->json([
                'success' => false,
                'message' => 'Form not found',
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            $form->delete();

            return response()->json([
                'success' => true,
                'message' => 'Form deleted successfully',
                'data' => ['id' => $id],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete form',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
