<?php
namespace MicroweberPackages\Page;

use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Content\Scopes\PageScope;

class Page extends Model
{

    protected $table = 'content';
    protected $primaryKey = 'id';

    /**
     * The "booted" method of the model.
     *
     * @return void
     */
    protected static function booted()
    {
        static::addGlobalScope(new PageScope());
    }


}
