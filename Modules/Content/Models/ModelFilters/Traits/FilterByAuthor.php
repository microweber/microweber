<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 10/15/2020
 * Time: 3:42 PM
 */

namespace Modules\Content\Models\ModelFilters\Traits;

trait FilterByAuthor
{
    public function author($author = false)
    {
        if ($author) {
            $author = intval($author);
            $this->query->where('created_by', '=', $author);
        }
    }

    public function userId($userId)
    {
        return $this->author($userId);
    }
}
