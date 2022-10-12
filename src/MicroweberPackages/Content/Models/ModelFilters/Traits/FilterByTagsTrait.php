<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 10/15/2020
 * Time: 3:42 PM
 */

namespace MicroweberPackages\Content\Models\ModelFilters\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Config;

trait FilterByTagsTrait
{
    public function allTags($tags)
    {
        if (is_string($tags)) {
            $tags = explode(',', $tags);
        } elseif (!is_array($tags)) {
            $tags = array($tags);
        }

        if (is_array($tags) and !empty($tags)) {
            $tags = array_values($tags);
            $tags = array_filter($tags);
            $this->query->withAllTags($tags);
        }

        return $this->query;
    }

    public function tags($tags)
    {
        if (!empty($tags)) {
            return $this->allTags($tags);
        }
    }

}
