<?php
namespace Microweber\SiteStats\Models;


use Illuminate\Database\Eloquent\Model as Eloquent;

class Base extends Eloquent
{

    public function scopePeriod($query, $minutes, $alias = '')
    {
        $alias = $alias ? "$alias." : '';
        return $query
            ->where($alias.'updated_at', '>=', $minutes->getStart() ? $minutes->getStart() : 1)
            ->where($alias.'updated_at', '<=', $minutes->getEnd() ? $minutes->getEnd() : 1);
    }
}



