<?php
namespace MicroweberPackages\Core\Models;

use Illuminate\Database\Eloquent\Model;

class MicroweberModel extends Model {

    protected $searchable = [];

    public function getSearchable()
    {
        return $this->searchable;
    }

}
