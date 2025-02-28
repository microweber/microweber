<?php

namespace MicroweberPackages\Module\Models;

use Illuminate\Database\Eloquent\Model;

class ModulePreset extends Model
{
    public $table = 'module_templates';

    protected $fillable = [
        'id',
        'module_id',
        'name',
        'module',
        'position',


    ];



}
