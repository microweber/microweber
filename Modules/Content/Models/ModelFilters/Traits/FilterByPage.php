<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 10/15/2020
 * Time: 3:42 PM
 */

namespace Modules\Content\Models\ModelFilters\Traits;

trait FilterByPage
{
    public function page($pageId)
    {
        if ($pageId) {
            $this->query->where('parent', $pageId);
        }
    }
    public function pageAndParent($pageId)
    {
        if ($pageId) {
            $this->query->where('parent', $pageId);
            $this->query->orWhere('id', $pageId);
        }
    }
}
