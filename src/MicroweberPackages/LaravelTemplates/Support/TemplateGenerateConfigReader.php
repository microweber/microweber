<?php

namespace MicroweberPackages\LaravelTemplates\Support;

use Nwidart\Modules\Support\Config\GenerateConfigReader;
use Nwidart\Modules\Support\Config\GeneratorPath;

class TemplateGenerateConfigReader extends GenerateConfigReader
{
    public static function read(string $value): GeneratorPath
    {
        return new GeneratorPath(config("templates.paths.generator.$value"));
    }
}
