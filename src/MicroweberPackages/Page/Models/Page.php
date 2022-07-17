<?php
namespace MicroweberPackages\Page\Models;

use MicroweberPackages\Content\Content;
use MicroweberPackages\Content\Scopes\PageScope;
use MicroweberPackages\Database\Traits\HasSlugTrait;
use MicroweberPackages\Media\Traits\MediaTrait;


class Page extends Content
{
    use HasSlugTrait;
    use MediaTrait;

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
        "content",
        "description",
        "content_body",
        "content_meta_keywords",
        "original_link",
        "require_login",
        "created_by",
        "is_home",
        "is_shop",
        "is_active",
        "updated_at",
        "created_at",
    ];



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
