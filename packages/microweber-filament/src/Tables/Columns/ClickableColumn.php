<?php

namespace MicroweberPackages\Filament\Tables\Columns;

use Closure;
use Filament\Support\Components\Contracts\HasEmbeddedView;
use Filament\Tables\Columns\Column;
use Filament\Tables\Columns\Concerns\CanOpenUrl;
use Filament\Tables\Columns\Layout\Component;

class ClickableColumn extends Component implements HasEmbeddedView
{

    use CanOpenUrl;

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

    public function toEmbeddedHtml(): string
    {
        $url = $this->getUrl() ?? '#';

        $attributes = $this->getExtraAttributeBag()
            ->class(['fi-ta-clickable-column', 'flex', 'flex-col', 'items-center', 'py-8']);

        $record = $this->getRecord();
        $recordKey = $this->getRecordKey();
        $rowLoop = $this->getRowLoop();

        ob_start(); ?>

        <a href="<?= e($url) ?>" <?= $attributes->toHtml() ?>>
            <?php foreach ($this->getComponents() as $component) { ?>
                <?= $component->record($record)->recordKey($recordKey)->rowLoop($rowLoop)->renderInLayout() ?>
            <?php } ?>
        </a>

        <?php return ob_get_clean();
    }
}
