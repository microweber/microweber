<?php
namespace MicroweberPackages\Option\Models;

use Illuminate\Database\Eloquent\Model;

class ModuleOption extends Model
{
    protected $table = 'options';

    public $translatable = [];
}