<?php
namespace MicroweberPackages\Template\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * TemplateManager facade — greppable public API for template manager.
 *
 * @see \MicroweberPackages\Template\TemplateManager
 * @mixin \MicroweberPackages\Template\TemplateManager
 */
class TemplateManager extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'template_manager';
    }
}
