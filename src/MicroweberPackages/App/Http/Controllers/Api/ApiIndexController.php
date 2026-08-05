<?php

declare(strict_types=1);

namespace MicroweberPackages\App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Route;

/**
 * Replaces api_expose('api_index') — lists registered API route names/paths.
 */
class ApiIndexController extends Controller
{
    /**
     * ANY api/api_index
     *
     * @return array<int, string>|array{count: int, routes: array<int, string>}
     */
    public function index(Request $request): array
    {
        $routes = [];
        $routeCollection = Route::getRoutes();
        foreach ($routeCollection->getRoutes() as $route) {
            $uri = $route->uri();
            if (!str_starts_with($uri, 'api/') && !str_starts_with($uri, 'api_nosession/')) {
                continue;
            }
            $name = $route->getName() ?? $uri;
            $routes[] = $name;
        }

        $routes = array_values(array_unique($routes));
        sort($routes);

        if ($request->has('debug')) {
            return [
                'count' => count($routes),
                'routes' => $routes,
            ];
        }

        return $routes;
    }
}
