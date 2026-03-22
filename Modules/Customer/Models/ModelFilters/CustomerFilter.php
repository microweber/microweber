<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 10/15/2020
 * Time: 3:25 PM
 */

namespace Modules\Customer\Models\ModelFilters;

use EloquentFilter\ModelFilter;
use Modules\Content\Models\ModelFilters\Traits\FilterByDateBetweenTrait;

class CustomerFilter extends ModelFilter
{

    use FilterByDateBetweenTrait;

    public function keyword($keyword)
    {
        $model = $this->getModel();
        $table = $model->getTable();

        $this->query->where($table.'.first_name', 'LIKE', '%' . $keyword . '%');
        $this->query->orWhere($table.'.last_name', 'LIKE', '%' . $keyword . '%');
        $this->query->orWhere($table.'.email', 'LIKE', '%' . $keyword . '%');
        $this->query->orWhere($table.'.phone', 'LIKE', '%' . $keyword . '%');

        return $this->query;
    }

    /**
     * Filter by tag IDs (customers must have ALL specified tags).
     *
     * @param array|string $tagIds
     * @return \Illuminate\Database\Query\Builder
     */
    public function tags($tagIds)
    {
        $tagIds = is_array($tagIds) ? $tagIds : [$tagIds];

        foreach ($tagIds as $tagId) {
            $this->query->whereHas('tags', function ($q) use ($tagId) {
                $q->where('tagging_tags.id', $tagId);
            });
        }

        return $this->query;
    }

    /**
     * Filter by tag IDs (customers must have ANY of the specified tags).
     *
     * @param array|string $tagIds
     * @return \Illuminate\Database\Query\Builder
     */
    public function tagsAny($tagIds)
    {
        $tagIds = is_array($tagIds) ? $tagIds : [$tagIds];

        $this->query->whereHas('tags', function ($q) use ($tagIds) {
            $q->whereIn('tagging_tags.id', $tagIds);
        });

        return $this->query;
    }

    /**
     * Filter customers without any tags.
     *
     * @param bool $withoutTags
     * @return \Illuminate\Database\Query\Builder
     */
    public function withoutTags($withoutTags = true)
    {
        if ($withoutTags) {
            $this->query->whereDoesntHave('tags');
        }

        return $this->query;
    }

    /**
     * Filter by customer status.
     *
     * @param string $status
     * @return \Illuminate\Database\Query\Builder
     */
    public function status($status)
    {
        $model = $this->getModel();
        $table = $model->getTable();

        $this->query->where($table.'.status', $status);

        return $this->query;
    }

    /**
     * Filter by customer creation date range.
     *
     * @param array $dates
     * @return \Illuminate\Database\Query\Builder
     */
    public function createdAt($dates)
    {
        if (is_array($dates) && count($dates) === 2) {
            $this->query->whereBetween('created_at', $dates);
        }

        return $this->query;
    }

}
