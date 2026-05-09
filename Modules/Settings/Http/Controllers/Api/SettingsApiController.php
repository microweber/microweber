<?php

declare(strict_types=1);

namespace Modules\Settings\Http\Controllers\Api;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use MicroweberPackages\Option\Models\Option;
use Symfony\Component\HttpFoundation\Response;

/**
 * Exposes the site-wide `options` table under /api/module/settings.
 *
 * Reads are public but restricted to a whitelist of safe keys so that
 * nothing sensitive (mail credentials, payment provider keys, api
 * secrets) ever leaks. Writes require admin.
 *
 * AI-108 / TICKET-BG (cycle-133 2026-05-09): migrated from raw
 * DB::table('options') queries to the Option Eloquent model. The
 * model's $fillable was simultaneously expanded to include option_key
 * and module so mass assignment works without silently dropping fields,
 * and routing writes through the model fires OptionWasCreated /
 * OptionWasUpdated / OptionWasDeleted events — which means
 * TemplateClearCachedCssListener now fires when settings are changed
 * via this API (previously a stale-cache bug — settings changed via
 * /api/module/settings would not invalidate the cached template CSS
 * because the events were bypassed).
 */
class SettingsApiController extends Controller
{
    /**
     * Keys that are safe to expose anonymously (website branding, seo,
     * analytics IDs, maintenance mode flags).
     *
     * @var array<int, string>
     */
    private const PUBLIC_KEYS = [
        'website_title',
        'website_description',
        'website_keywords',
        'website_footer',
        'website_head',
        'favicon_image',
        'logo_image',
        'date_format',
        'maintenance_mode',
        'maintenance_mode_text',
        'google-analytics-id',
        'google-tag-manager-id',
        'facebook-pixel-id',
        'app_version',
        'default_currency',
        'language',
        'timezone',
    ];

    /**
     * @OA\Get(
     *     path="/api/module/settings",
     *     operationId="api.module.settings.index",
     *     tags={"Settings"},
     *     summary="List settings",
     *     @OA\Response(response=200, description="Success"),
     *     @OA\Response(response=404, description="Not found")
     * )
     */
    public function index(Request $request): JsonResponse
    {
        $isAdmin = $request->user() && (int) $request->user()->is_admin === 1;
        $group = $request->input('group');

        $query = Option::query();

        if (!empty($group)) {
            $query->where('option_group', (string) $group);
        }

        if (!$isAdmin) {
            $query->whereIn('option_key', self::PUBLIC_KEYS);
        }

        $options = $query->orderBy('option_key')->get();

        $data = $options->map(fn (Option $o) => [
            'id' => (int) $o->id,
            'option_key' => $o->option_key,
            'option_value' => $o->option_value,
            'option_group' => $o->option_group,
            'module' => $o->module,
        ])->values();

        return response()->json([
            'success' => true,
            'data' => $data,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/module/settings/{key}",
     *     operationId="api.module.settings.key.show",
     *     tags={"Settings"},
     *     summary="Show a single setting",
     *     @OA\Parameter(
     *         name="key",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=200, description="Success"),
     *     @OA\Response(response=404, description="Not found")
     * )
     */
    public function show(Request $request, string $key): JsonResponse
    {
        $isAdmin = $request->user() && (int) $request->user()->is_admin === 1;

        if (!$isAdmin && !in_array($key, self::PUBLIC_KEYS, true)) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Admin access required.',
            ], Response::HTTP_FORBIDDEN);
        }

        $group = $request->input('group');
        $query = Option::query()->where('option_key', $key);
        if (!empty($group)) {
            $query->where('option_group', (string) $group);
        }
        $option = $query->first();

        if (!$option) {
            return response()->json([
                'success' => false,
                'message' => 'Option not found',
            ], Response::HTTP_NOT_FOUND);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => (int) $option->id,
                'option_key' => $option->option_key,
                'option_value' => $option->option_value,
                'option_group' => $option->option_group,
                'module' => $option->module,
            ],
        ]);
    }

    /**
     * @OA\Post(
     *     path="/api/module/settings",
     *     operationId="api.module.settings.store",
     *     tags={"Settings"},
     *     summary="Create a new setting",
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="Success"),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden — admin required")
     * )
     */
    public function store(Request $request): JsonResponse
    {
        if ($deny = $this->requireAdmin($request)) {
            return $deny;
        }

        $validator = Validator::make($request->all(), [
            'option_key' => 'required|string|max:255',
            'option_value' => 'nullable',
            'option_group' => 'nullable|string|max:255',
            'module' => 'nullable|string|max:255',
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
            $match = ['option_key' => $data['option_key']];
            if (!empty($data['option_group'])) {
                $match['option_group'] = $data['option_group'];
            }

            $existing = Option::query()->where($match)->first();

            if ($existing) {
                $existing->update([
                    'option_value' => $data['option_value'] ?? null,
                    'module' => $data['module'] ?? $existing->module,
                ]);
                $row = $existing->refresh();
                $created = false;
            } else {
                $row = Option::create([
                    'option_key' => $data['option_key'],
                    'option_value' => $data['option_value'] ?? null,
                    'option_group' => $data['option_group'] ?? null,
                    'module' => $data['module'] ?? null,
                ]);
                $created = true;
            }

            return response()->json([
                'success' => true,
                'message' => 'Setting saved successfully',
                'data' => [
                    'id' => (int) $row->id,
                    'option_key' => $row->option_key,
                    'option_value' => $row->option_value,
                    'option_group' => $row->option_group,
                    'module' => $row->module,
                ],
            ], $created ? Response::HTTP_CREATED : Response::HTTP_OK);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to save setting',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/module/settings/{key}",
     *     operationId="api.module.settings.key.update",
     *     tags={"Settings"},
     *     summary="Update a setting",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="key",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=200, description="Success"),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden — admin required")
     * )
     */
    public function update(Request $request, string $key): JsonResponse
    {
        if ($deny = $this->requireAdmin($request)) {
            return $deny;
        }

        $validator = Validator::make($request->all(), [
            'option_value' => 'nullable',
            'option_group' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $data = $validator->validated();
        $group = $data['option_group'] ?? $request->input('group');

        $query = Option::query()->where('option_key', $key);
        if (!empty($group)) {
            $query->where('option_group', (string) $group);
        }
        $option = $query->first();

        if (!$option) {
            return response()->json([
                'success' => false,
                'message' => 'Option not found',
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            $option->update([
                'option_value' => $data['option_value'] ?? null,
            ]);
            $row = $option->refresh();

            return response()->json([
                'success' => true,
                'message' => 'Setting updated successfully',
                'data' => [
                    'id' => (int) $row->id,
                    'option_key' => $row->option_key,
                    'option_value' => $row->option_value,
                    'option_group' => $row->option_group,
                    'module' => $row->module,
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update setting',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * @OA\Delete(
     *     path="/api/module/settings/{key}",
     *     operationId="api.module.settings.key.destroy",
     *     tags={"Settings"},
     *     summary="Delete a setting",
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="key",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="string")
     *     ),
     *     @OA\Response(response=200, description="Success"),
     *     @OA\Response(response=401, description="Unauthenticated"),
     *     @OA\Response(response=403, description="Forbidden — admin required")
     * )
     */
    public function destroy(Request $request, string $key): JsonResponse
    {
        if ($deny = $this->requireAdmin($request)) {
            return $deny;
        }

        $group = $request->input('group');
        $query = Option::query()->where('option_key', $key);
        if (!empty($group)) {
            $query->where('option_group', (string) $group);
        }
        $option = $query->first();

        if (!$option) {
            return response()->json([
                'success' => false,
                'message' => 'Option not found',
            ], Response::HTTP_NOT_FOUND);
        }

        try {
            $option->delete();

            return response()->json([
                'success' => true,
                'message' => 'Setting deleted successfully',
                'data' => ['id' => (int) $option->id],
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete setting',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    private function requireAdmin(Request $request): ?JsonResponse
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

        return null;
    }
}
