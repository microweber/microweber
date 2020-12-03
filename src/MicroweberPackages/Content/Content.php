<?php
namespace MicroweberPackages\Content;

use Conner\Tagging\Taggable;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Category\Traits\CategoryTrait;
use MicroweberPackages\Content\Models\ModelFilters\ContentFilter;
use MicroweberPackages\ContentData\Traits\ContentDataTrait;
use MicroweberPackages\CustomField\Traits\CustomFieldsTrait;
use MicroweberPackages\Database\Traits\HasSlugTrait;
use MicroweberPackages\Media\Traits\MediaTrait;
use MicroweberPackages\Product\Models\ModelFilters\ProductFilter;
use MicroweberPackages\Tag\Tag;
use MicroweberPackages\Tag\Traits\TaggableTrait;

class Content extends Model
{
   // use Taggable;
    use TaggableTrait;
    use ContentDataTrait;
    use CustomFieldsTrait;
    use CategoryTrait;
    use HasSlugTrait;
    use MediaTrait;
    use Filterable;


    protected $table = 'content';
    protected $content_type = 'content';
    public $additionalData = [];

    protected $attributes = [
        'is_active' => '1',
        'is_deleted' => '0',
        'is_shop' => '0',
        'is_home' => '0',
    ];

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

    public function modelFilter()
    {
        return $this->provideFilter(ContentFilter::class);
    }

    public function getMorphClass()
    {
        return 'content';
    }
}