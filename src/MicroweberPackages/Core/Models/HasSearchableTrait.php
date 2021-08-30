<?php


namespace MicroweberPackages\Core\Models;


trait HasSearchableTrait
{
    public function getSearchable()
    {
        return $this->searchable;
    }
}
