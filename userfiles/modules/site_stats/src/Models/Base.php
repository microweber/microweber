<?php
namespace Microweber\SiteStats\Models;


use Illuminate\Database\Eloquent\Model as Eloquent;

class Base extends Eloquent
{
    public $timestamps = [ "updated_at" ]; // enable only to created_at
    const CREATED_AT = null;


    public function scopePeriod($query, $minutes, $alias = '')
    {
        $alias = $alias ? "$alias." : '';
        return $query
            ->where($alias.'updated_at', '>=', $minutes->getStart() ? $minutes->getStart() : 1)
            ->where($alias.'updated_at', '<=', $minutes->getEnd() ? $minutes->getEnd() : 1);
    }
}



