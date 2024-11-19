<?php

namespace Modules\Content\Models;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Concerns\HasEvents;
use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Category\Traits\CategoryTrait;
use MicroweberPackages\Content\Models\ContentRelated;
use MicroweberPackages\Content\Models\ModelFilters\ContentFilter;
use MicroweberPackages\ContentField\Traits\HasContentFieldTrait;
use MicroweberPackages\Core\Models\HasSearchableTrait;
use MicroweberPackages\CustomField\Traits\CustomFieldsTrait;
use MicroweberPackages\Database\Traits\CacheableQueryBuilderTrait;
use MicroweberPackages\Database\Traits\HasCreatedByFieldsTrait;
use MicroweberPackages\Database\Traits\HasSlugTrait;
use MicroweberPackages\Database\Traits\MaxPositionTrait;
use MicroweberPackages\Database\Traits\ParentCannotBeSelfTrait;
use MicroweberPackages\Media\Traits\MediaTrait;
use MicroweberPackages\Multilanguage\Models\Traits\HasMultilanguageTrait;
use Modules\ContentData\Traits\ContentDataTrait;
use Modules\Menu\Concerns\HasMenuItem;
use Modules\Tag\Traits\TaggableTrait;

//use Kirschbaum\PowerJoins\PowerJoins;

class Content extends Model
{
    use TaggableTrait;
    use ContentDataTrait;
    use CustomFieldsTrait;
    use CategoryTrait;
    use HasContentFieldTrait;
    use HasSlugTrait;
    use HasSearchableTrait;
    use HasMenuItem;
    use MediaTrait;
    use Filterable;
    use HasCreatedByFieldsTrait;
    use CacheableQueryBuilderTrait;
 //   use PowerJoins;
    use HasEvents;
    use HasMultilanguageTrait;
    use MaxPositionTrait;
    use ParentCannotBeSelfTrait;

    protected $table = 'content';
    protected $content_type = 'content';
    public $additionalData = [];

    public $cacheTagsToClear = ['repositories', 'content', 'content_fields_drafts', 'menu', 'content_fields', 'categories','custom_fields','custom_fields_values'];

    public $translatable = ['title','url','description','content','content_body','content_meta_title','content_meta_keywords'];

    protected $attributes = [
        'is_active' => '1',
        'is_deleted' => '0',
        'is_shop' => '0',
        'is_home' => '0',
    ];
    protected $searchableByKeyword = [
        'title',
        'content',
        'content_body',
        'description',
        'url',
        'content_meta_title',
        'content_meta_keywords',
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
        'is_active',
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
        "session_id",
        "updated_at",
        "created_at",
    ];

    public function scopeActive($query)
    {
        return $query
            ->where('is_active', 1)
            ->where(function($subQuery) {
                $subQuery
                    ->whereNull('is_deleted')
                    ->orWhere('is_deleted', 0);
            });
    }

   public function scopeInactive($query)
    {
        return $query
            ->where('is_active', 0)
            ->where(function($subQuery) {
                $subQuery
                    ->whereNull('is_deleted')
                    ->orWhere('is_deleted', 0);
            });
    }

    public function scopeTrashed($query)
    {
        return $query->where('is_deleted', 1);
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
        // TODO
        return Content::class;
    }
    public function getImageAttribute()
    {
        return  content_picture($this->id);
    }
    public function getLinkAttribute()
    {
        return $this->link();
    }
    public function link()
    {
        return content_link($this->id);
    }

    public function editLink()
    {
        return content_edit_link($this->id);
    }

    public function liveEditLink()
    {
        return content_link($this->id).'?editmode=y';
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
