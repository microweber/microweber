<?php

namespace MicroweberPackages\Admin\View\Columns;

use Illuminate\Database\Eloquent\Model;
use Rappasoft\LaravelLivewireTables\Views\Column;

class CardColumn extends Column
{
    protected string $view = 'admin::livewire.livewire-tables.includes.columns.card';
    protected $attributesCallback;

    public function attributes(callable $callback): self
    {
        $this->attributesCallback = $callback;

        return $this;
    }

    public function getAttributesCallback()
    {
        return $this->attributesCallback;
    }

    public function hasAttributesCallback()
    {
        return $this->attributesCallback !== null;
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
            ->withAttributes($this->hasAttributesCallback() ? app()->call($this->getAttributesCallback(), ['row' => $row]) : []);
    }
}
