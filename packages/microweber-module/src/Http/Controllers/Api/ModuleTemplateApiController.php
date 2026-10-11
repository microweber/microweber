<?php

declare(strict_types=1);

namespace MicroweberPackages\Module\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use MicroweberPackages\Module\Http\Requests\DeleteModuleAsTemplateRequest;
use MicroweberPackages\Module\Http\Requests\SaveModuleAsTemplateRequest;
use MicroweberPackages\Module\ModuleManager;

/**
 * Legacy module template preset endpoints (formerly api_expose_admin).
 */
class ModuleTemplateApiController extends Controller
{
    private function moduleManager(): ModuleManager
    {
        /** @var ModuleManager $manager */
        $manager = app('module_manager');

        return $manager;
    }

    /**
     * ANY api/save_module_as_template
     */
    public function save(SaveModuleAsTemplateRequest $request): mixed
    {
        return $this->moduleManager()->save_module_as_template($request->validated());
    }

    /**
     * ANY api/delete_module_as_template
     */
    public function delete(DeleteModuleAsTemplateRequest $request): mixed
    {
        return $this->moduleManager()->delete_module_as_template($request->validated());
    }
}
