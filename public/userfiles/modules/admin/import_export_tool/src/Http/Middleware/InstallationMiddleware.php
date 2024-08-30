<?php
namespace MicroweberPackages\Modules\Admin\ImportExportTool\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Schema;

class InstallationMiddleware
{
    public function handle($request, Closure $next)
    {

        if ($request->route()->getName() !== 'admin.import-export-tool.install') {
            if (!Schema::hasTable('import_feeds') || !Schema::hasTable('export_feeds')) {
                return redirect(route('admin.import-export-tool.install'));
            }
        }

        $response = $next($request);

        return $response;
    }
}
