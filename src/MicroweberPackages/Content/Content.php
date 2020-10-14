<?php
namespace MicroweberPackages\Content;

use Conner\Tagging\Taggable;
use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Category\Traits\CategoryTrait;
use MicroweberPackages\ContentData\Traits\ContentDataTrait;
use MicroweberPackages\CustomField\Traits\CustomFieldsTrait;
use MicroweberPackages\Database\Traits\HasSlugTrait;
use MicroweberPackages\Media\Traits\MediaTrait;
use MicroweberPackages\Tag\Tag;

class Content extends Model
{
    use Taggable;
    use ContentDataTrait;
    use CustomFieldsTrait;
    use CategoryTrait;
    use HasSlugTrait;
    use MediaTrait;

    protected $table = 'content';
    protected $content_type = 'content';
    public $additionalData = [];

    public function tags()
    {
        return $this->belongsToMany(Tag::class);
    }

}