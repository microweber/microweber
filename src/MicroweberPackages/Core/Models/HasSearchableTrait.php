<?php


namespace MicroweberPackages\Core\Models;


trait HasSearchableTrait
{
    public function getSearchable()
    {
        return $this->searchable;
    }
    public function getSearchableByKeyword()
    {
        if(isset($this->searchableByKeyword) and !empty($this->searchableByKeyword)){
            return $this->searchableByKeyword;
        }
        return $this->searchable;
    }
}
