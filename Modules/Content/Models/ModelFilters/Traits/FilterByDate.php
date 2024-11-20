<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 10/15/2020
 * Time: 3:42 PM
 */

namespace Modules\Content\Models\ModelFilters\Traits;

trait FilterByDate
{
    public function dateBetween($date)
    {
        $minDate = $date;
        $maxDate = false;

        if (strpos($date, ',') !== false) {
            $date = explode(',', $date);
            $minDate = $date[0];
            $maxDate = $date[1];
        }

        $table = $this->getModel()->getTable();

        if (!empty($minDate)) {
            $this->query->where($table . '.created_at', '>', $minDate);
        }


        if (!empty($maxDate)) {
            $this->query->where($table . '.created_at', '<', $maxDate);
        }
    }

    public function createdAt($createdAt = false)
    {
        if ($createdAt) {
            $this->query->where('created_at', '>=', $createdAt);
        }
    }

    public function updatedAt($updatedAt = false)
    {
        if ($updatedAt) {
            $this->query->where('updated_at', '>=', $updatedAt);
        }
    }

}
