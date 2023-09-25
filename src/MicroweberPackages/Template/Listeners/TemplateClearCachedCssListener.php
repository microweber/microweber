<?php

namespace MicroweberPackages\Template\Listeners;

use MicroweberPackages\Core\Events\AbstractModelEvent;

class TemplateClearCachedCssListener
{
    public function handle(AbstractModelEvent $event)
    {
        $deleteWhenOptionGroups = [
            'template',
            'website',
        ];
        if ($event && $event->model && $event->model->option_group) {
            if ($deleteWhenOptionGroups) {
                if (in_array($event->model->option_group, $deleteWhenOptionGroups)) {
                    app()->template->clear_cache();
                }
            }
        }

    }
}
