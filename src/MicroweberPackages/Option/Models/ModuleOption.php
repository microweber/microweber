<?php
namespace MicroweberPackages\Option\Models;

use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Database\Traits\CacheableQueryBuilderTrait;

class ModuleOption extends Model
{
    protected $table = 'options';

    public $translatable = [];

    use CacheableQueryBuilderTrait;
}