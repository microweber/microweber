<?php

namespace MicroweberPackages\LaravelTemplates\Support;

use Nwidart\Modules\Publishing\AssetPublisher;

class TemplateAssetPublisher extends AssetPublisher
{
    /**
     * Get source path.
     *
     * @return string
     */
    public function getSourcePath()
    {
        return $this->getModule()->getExtraPath(
            TemplateGenerateConfigReader::read('assets')->getPath()
        );
    }
}
