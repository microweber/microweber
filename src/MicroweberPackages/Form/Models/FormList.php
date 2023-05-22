<?php
namespace MicroweberPackages\Form\Models;

use Illuminate\Database\Eloquent\Model;

class FormList extends Model
{
    protected $table = 'forms_lists';

    public $timestamps = false;


    public function formsData()
    {
        return $this->hasMany(FormData::class, 'list_id');
    }
}
