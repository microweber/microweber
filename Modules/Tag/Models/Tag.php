<?php

namespace Modules\Tag\Models;

use MicroweberPackages\Database\Traits\CacheableQueryBuilderTrait;

/**
 * @package Conner\Tagging\Models
 * @property string id
 * @property string name
 * @property string slug
 * @property bool suggest
 * @property integer count
 * @property integer tag_group_id
 * @property TagGroup group
 * @property string description
 * @method static suggested()
 * @method static inGroup(string $group)
 */
class Tag extends \Conner\Tagging\Model\Tag
{
  use CacheableQueryBuilderTrait;

}
