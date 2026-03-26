<?php

declare(strict_types=1);

namespace Modules\Page\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Validator;
use Illuminate\Routing\Controller;
use Modules\Page\Http\Resources\PageResource;
use Modules\Page\Repositories\PageApiRepository;
use Symfony\Component\HttpFoundation\Response;

class PageApiController extends Controller
{
    public function __construct(
        private readonly PageApiRepository $page
    ) {
    }

    /**
     * Display a listing of pages.
     *
     * @param Request $request
     * @return AnonymousResourceCollection
     */
    public function index(Request $request): AnonymousResourceCollection
    {
        $limit = $request->get('limit', 30);
        $query = $this->page->filter($request->all());

        $pages = $query->paginate($limit);
        $pages->appends($request->except('page'));

        return PageResource::collection($pages);
    }

    /**
     * Store a new page.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:500',
            'url' => 'nullable|string|max:500|unique:content,url',
            'content' => 'nullable|string',
            'description' => 'nullable|string',
            'content_body' => 'nullable|string',
            'content_meta_title' => 'nullable|string|max:500',
            'content_meta_keywords' => 'nullable|string|max:500',
            'content_meta_description' => 'nullable|string|max:1000',
            'is_home' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'require_login' => 'nullable|boolean',
            'parent' => 'nullable|integer',
            'layout_file' => 'nullable|string|max:500',
            'active_site_template' => 'nullable|string|max:500',
            'position' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $page = $this->page->create($validator->validated());

            return response()->json([
                'success' => true,
                'message' => 'Page created successfully',
                'data' => new PageResource($page),
            ], Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to create page',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Display the specified page.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show(int $id): JsonResponse
    {
        $page = $this->page->show($id);

        if (!$page) {
            return response()->json([
                'success' => false,
                'message' => 'Page not found',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'success' => true,
            'data' => new PageResource($page),
        ]);
    }

    /**
     * Update the specified page.
     *
     * @param Request $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(Request $request, int $id): JsonResponse
    {
        $page = $this->page->show($id);

        if (!$page) {
            return response()->json([
                'success' => false,
                'message' => 'Page not found',
            ], Response::HTTP_NOT_FOUND);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'nullable|string|max:500',
            'url' => 'nullable|string|max:500|unique:content,url,' . $id,
            'content' => 'nullable|string',
            'description' => 'nullable|string',
            'content_body' => 'nullable|string',
            'content_meta_title' => 'nullable|string|max:500',
            'content_meta_keywords' => 'nullable|string|max:500',
            'content_meta_description' => 'nullable|string|max:1000',
            'is_home' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'require_login' => 'nullable|boolean',
            'parent' => 'nullable|integer',
            'layout_file' => 'nullable|string|max:500',
            'active_site_template' => 'nullable|string|max:500',
            'position' => 'nullable|integer',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $updated = $this->page->update($validator->validated(), $id);

            return response()->json([
                'success' => true,
                'message' => 'Page updated successfully',
                'data' => new PageResource($updated),
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update page',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Remove the specified page.
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy(int $id): JsonResponse
    {
        $page = $this->page->show($id);

        if (!$page) {
            return response()->json([
                'success' => false,
                'message' => 'Page not found',
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            $this->page->delete($id);

            return response()->json([
                'success' => true,
                'message' => 'Page deleted successfully',
                'data' => ['id' => $id],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete page',
                'error' => $e->getMessage(),
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
