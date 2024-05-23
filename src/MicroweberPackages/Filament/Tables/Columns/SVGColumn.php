<?php

namespace MicroweberPackages\Filament\Tables\Columns;

use Filament\Support\Concerns\EvaluatesClosures;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\HtmlString;

use Closure;

class SVGColumn extends TextColumn
{

    protected string $view = 'filament-tables::columns.svg-column';

    protected string | Closure | null $imageUrl = null;


}
