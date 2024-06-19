<?php

namespace MicroweberPackages\Filament\Tables\Columns;

use Closure;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\Concerns\CanOpenUrl;
use Filament\Tables\Columns\Layout\Component;

class ClickableColumn extends Component
{

    use CanOpenUrl;

    /**
     * @var view-string
     */
    protected string $view = 'filament-tables::columns.clickable-column';

    /**
     * @param  array<Column | Component> | Closure  $schema
     */
    final public function __construct(array | Closure $schema)
    {
        $this->schema($schema);
    }

    /**
     * @param  array<Column | Component> | Closure  $schema
     */
    public static function make(array | Closure $schema): static
    {
        $static = app(static::class, ['schema' => $schema]);
        $static->configure();

        return $static;
    }
}
