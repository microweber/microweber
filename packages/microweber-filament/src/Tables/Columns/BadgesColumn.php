<?php

namespace MicroweberPackages\Filament\Tables\Columns;

use Filament\Support\Concerns\EvaluatesClosures;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Support\HtmlString;

use Closure;

class BadgesColumn extends TextColumn
{

    protected string $view = 'filament-tables::columns.badges-column';

    public $badges = [];

    public function badges(array|Closure $badges) : static
    {
        $this->badges = $badges;

        return $this;
    }

    public function getBadges()
    {
        return $this->evaluate($this->badges);
    }
}
