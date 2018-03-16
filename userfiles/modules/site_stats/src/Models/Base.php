<?php
namespace Microweber\SiteStats\Models;

use Carbon\Carbon;

use Illuminate\Database\Eloquent\Model as Eloquent;

class Base extends Eloquent
{
    public $timestamps = ["updated_at"]; // enable only to updated_at
    const CREATED_AT = null;


    public function scopePeriod($query, $period, $alias = '')
    {

        if (!$period) {
            return $query;
        }

        switch ($period) {
            case 'daily':
                $start_date = date('Y-m-d H:i:s', strtotime('-1 days'));
                $end_date = date('Y-m-d H:i:s');

                break;

            case 'weekly':
                $start_date = date('Y-m-d H:i:s', strtotime('-1 weeks'));
                $end_date = date('Y-m-d H:i:s');

                break;


            case 'monthly':
                $start_date = date('Y-m-d H:i:s', strtotime('-1 months'));
                $end_date = date('Y-m-d H:i:s');

                break;
            default:
                $start_date = date('Y-m-d H:i:s', strtotime($period));
                $end_date = date('Y-m-d H:i:s');

                break;
        }


        $alias = $alias ? "$alias." : '';
        return $query
            ->where($alias . 'updated_at', '>=', $start_date ? $start_date : 1)
            ->where($alias . 'updated_at', '<=', $end_date ? $end_date : 1);
    }
}



