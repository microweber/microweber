<?php
namespace MicroweberPackages\Content;

use Conner\Tagging\Taggable;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Category\Traits\CategoryTrait;
use MicroweberPackages\Content\Models\ModelFilters\ContentFilter;
use MicroweberPackages\ContentData\Traits\ContentDataTrait;
use MicroweberPackages\Core\Models\HasSearchableTrait;
use MicroweberPackages\CustomField\Traits\CustomFieldsTrait;
use MicroweberPackages\Database\Traits\CacheableQueryBuilderTrait;
use MicroweberPackages\Database\Traits\HasCreatedByFieldsTrait;
use MicroweberPackages\Database\Traits\HasSlugTrait;
use MicroweberPackages\Media\Traits\MediaTrait;
use MicroweberPackages\Menu\Traits\HasMenuItem;
use MicroweberPackages\Multilanguage\Models\Traits\HasMultilanguageTrait;
use MicroweberPackages\Product\Models\ModelFilters\ProductFilter;
use MicroweberPackages\Tag\Traits\TaggableTrait;

class Content extends Model
{
    use HasMultilanguageTrait;
    use TaggableTrait;
    use ContentDataTrait;
    use CustomFieldsTrait;
    use CategoryTrait;
    use HasSlugTrait;
    use HasSearchableTrait;
    use HasMenuItem;
    use MediaTrait;
    use Filterable;
    use HasCreatedByFieldsTrait;
    use CacheableQueryBuilderTrait;

    protected $table = 'content';
    protected $content_type = 'content';
    public $additionalData = [];

    public $cacheTagsToClear = ['repositories', 'content', 'content_fields_drafts', 'menu', 'content_fields', 'categories'];

    public $translatable = ['title','url','description','content','content_body','content_meta_title','content_meta_keywords'];

    protected $attributes = [
        'is_active' => '1',
        'is_deleted' => '0',
        'is_shop' => '0',
        'is_home' => '0',
    ];

    protected $searchable = [
        'id',
        'title',
        'content',
        'content_body',
        'content_type',
        'subtype',
        'description',
        'is_home',
        'is_shop',
        'is_deleted',
        'subtype',
        'subtype_value',
        'parent',
        'layout_file',
        'active_site_template',
        'url',
        'content_meta_title',
        'content_meta_keywords',
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

    public function scopeActive($query)
    {
        return $query->where('is_active', 1)->where('is_deleted', 0);
    }

    public function related()
    {
        return $this->hasMany(ContentRelated::class,'content_id','id')->orderBy('position', 'ASC');
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


    public function getDescriptionAttribute($value)
    {
        if(is_string($value) and $value){
          return  strip_tags($value);
        }
    }

    public function shortDescription($limit = 224, $end = '...')
    {
        if (empty($this->description)) {
            return false;
        }

        $shortDescription = $this->description;
        $shortDescription = strip_tags($shortDescription);
        $shortDescription = trim($shortDescription);
        $shortDescription = str_limit($shortDescription, $limit, $end);

        return $shortDescription;
    }

}
