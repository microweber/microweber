<?php

namespace Modules\Tag\Models;

use MicroweberPackages\Database\Traits\CacheableQueryBuilderTrait;

/**
 * @package Conner\Tagging\Models
 *
 * @property integer id
 * @property string taggable_id
 * @property string taggable_type
 * @property string tag_name
 * @property string tag_slug
 * @property Tag tag
 */
class Tagged extends \Conner\Tagging\Model\Tagged
{
    use CacheableQueryBuilderTrait;
}
