<?php

namespace MicroweberPackages\Livewire\Views\Columns;

use Illuminate\Database\Eloquent\Model;
use Rappasoft\LaravelLivewireTables\Views\Column;

class MwCardTitleCategoriesButtonsColumn extends Column
{
    protected string $view = 'livewire::livewire.livewire-tables.includes.columns.mw-card-title-categories-buttons';

    protected $buttonsCallback;

    public function __construct(string $title, string $from = null)
    {
        parent::__construct($title, $from);

        $this->label(fn () => null);
    }

    public function buttons(callable $callback): self
    {
        $this->buttonsCallback = $callback;

        return $this;
    }

    public function getView(): string
    {
        return $this->view;
    }

    public function getContents(Model $row)
    {
        return view($this->getView())
            ->withColumn($this)
            ->withRow($row)
            ->withButtons(app()->call($this->buttonsCallback, ['row'=>$row]));
    }
}
