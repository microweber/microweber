<?php

namespace MicroweberPackages\Admin\View\Columns;

use Rappasoft\LaravelLivewireTables\Views\Columns\ImageColumn;

class ImageWithLinkColumn extends ImageColumn
{
    protected string $view = 'admin::livewire.livewire-tables.includes.columns.image_with_link';

}
