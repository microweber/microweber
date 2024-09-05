<?php


namespace MicroweberPackages\Template;

use Butschster\Head\Facades\Meta;
use Illuminate\Support\Str;
use MicroweberPackages\App\Http\Controllers\JsCompileController;
use MicroweberPackages\Template\Adapters\AdminTemplateStyle;
use MicroweberPackages\Template\Adapters\MicroweberTemplate;
use MicroweberPackages\Template\Adapters\RenderHelpers\TemplateOptimizeLoadingHelper;
use MicroweberPackages\Template\Adapters\TemplateCssParser;
use MicroweberPackages\Template\Adapters\TemplateCustomCss;
use MicroweberPackages\Template\Adapters\TemplateFonts;
use MicroweberPackages\Template\Adapters\TemplateIconFonts;
use MicroweberPackages\Template\Adapters\TemplateLiveEditCss;
use MicroweberPackages\Template\Adapters\TemplateStackRenderer;

/**
 * Class to render the frontend template and views.
 *
 * @category Template
 * @desc    These functions will allow to wo work with microweber template files.
 *
 * @property \MicroweberPackages\Template\Adapters\MicroweberTemplate $templateAdapter
 */
class Template
{

}
