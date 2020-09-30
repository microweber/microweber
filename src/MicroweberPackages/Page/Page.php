<?php
namespace MicroweberPackages\Page;

use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Content\Scopes\PageScope;
use MicroweberPackages\Database\Traits\HasSlugTrait;
use MicroweberPackages\Menu\Traits\HasMenuItem;

class Page extends Model
{
    use HasMenuItem;
    use HasSlugTrait;

    protected $table = 'content';
    protected $primaryKey = 'id';

    protected $fillable = [
        "subtype",
        "subtype_value",
        "content_type",
        "parent",
        "layout_file",
        "active_site_template",
        "title",
        "url",
        "content_meta_title",
        "description",
        "content_meta_keywords",
        "original_link",
        "require_login",
        "created_by",
        "is_home",
        "is_shop",
        "is_active"
    ];

    public $translatable = ['title','description','content','content_body'];

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
