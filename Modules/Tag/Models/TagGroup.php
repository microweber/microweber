<?php
namespace Modules\Tag\Models;

use Illuminate\Support\Collection;
use MicroweberPackages\Database\Traits\CacheableQueryBuilderTrait;

/**
 * @package Conner\Tagging\Models
 *
 * @property string id
 * @property string slug
 * @property string name
 * @property-read Collection|Tag[] tags
 */
class TagGroup extends \Conner\Tagging\Model\TagGroup
{
    use CacheableQueryBuilderTrait;
}
