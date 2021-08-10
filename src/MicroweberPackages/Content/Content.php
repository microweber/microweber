<?php
namespace MicroweberPackages\Content;

use Conner\Tagging\Taggable;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Category\Traits\CategoryTrait;
use MicroweberPackages\Content\Models\ModelFilters\ContentFilter;
use MicroweberPackages\ContentData\Traits\ContentDataTrait;
use MicroweberPackages\CustomField\Traits\CustomFieldsTrait;
use MicroweberPackages\Database\Traits\CacheableQueryBuilderTrait;
use MicroweberPackages\Database\Traits\HasCreatedByFieldsTrait;
use MicroweberPackages\Database\Traits\HasSlugTrait;
use MicroweberPackages\Media\Traits\MediaTrait;
use MicroweberPackages\Product\Models\ModelFilters\ProductFilter;
use MicroweberPackages\Tag\Traits\TaggableTrait;

class Content extends Model
{
    use TaggableTrait;
    use ContentDataTrait;
    use CustomFieldsTrait;
    use CategoryTrait;
    use HasSlugTrait;
    use MediaTrait;
    use Filterable;
    use HasCreatedByFieldsTrait;
    use CacheableQueryBuilderTrait;

    protected $table = 'content';
    protected $content_type = 'content';
    public $additionalData = [];

    public $cacheTagsToClear = ['content', 'content_fields_drafts', 'menu', 'content_fields', 'categories'];

    public $translatable = ['title','url','description','content','content_body'];

    protected $attributes = [
        'is_active' => '1',
        'is_deleted' => '0',
        'is_shop' => '0',
        'is_home' => '0',
    ];

    protected $fillable = [
        "id",
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
        "is_deleted",
        "updated_at",
        "created_at",
    ];

//    public function tags()
//    {
//        return $this->belongsToMany(Taggable::class);
//    }

    public function whereCategoryIds($ids = []) {

        dump($ids);

    }

    public function related()
    {
        return $this->hasMany(ContentRelated::class)->orderBy('position', 'ASC');
    }

    public function modelFilter()
    {
        return $this->provideFilter(ContentFilter::class);
    }

    public function getMorphClass()
    {
        return 'content';
    }

    public function link()
    {
        return content_link($this->id);
    }
}
