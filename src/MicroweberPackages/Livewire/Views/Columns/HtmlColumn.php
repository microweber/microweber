<?php

namespace MicroweberPackages\Livewire\Views\Columns;

use Illuminate\Database\Eloquent\Model;
use Rappasoft\LaravelLivewireTables\Views\Column;

class HtmlColumn extends Column
{
    protected string $view = 'livewire::livewire.mw-livewire-tables.includes.columns.html';

    public function __construct(string $title, string $from = null)
    {
        parent::__construct($title, $from);

        $this->label(fn () => null);
    }

    public function setOutputHtml(callable $callback): self
    {
        $this->outputHtmlCallback = $callback;

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
            ->withHtml(app()->call($this->outputHtmlCallback, ['row' => $row]));
    }
}
