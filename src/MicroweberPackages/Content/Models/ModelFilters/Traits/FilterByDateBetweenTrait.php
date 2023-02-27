<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 10/15/2020
 * Time: 3:42 PM
 */

namespace MicroweberPackages\Content\Models\ModelFilters\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use MicroweberPackages\Helper\XSSClean;
use MicroweberPackages\Multilanguage\Models\MultilanguageTranslations;
use MicroweberPackages\Multilanguage\MultilanguageHelpers;

trait FilterByDateBetweenTrait
{
    public function createdAt($date)
    {
        $table = $this->getModel()->getTable();

        $params = [];
        $params['from'] = Carbon::parse(strtotime($date))->format('Y-m-d') . ' 00:00:01';
        $params['to'] = Carbon::parse(strtotime($date))->format('Y-m-d') . ' 23:59:59';
        if (!empty($params)) {
            $this->query->where($table . '.created_at', '>', $params['from']);
            $this->query->where($table . '.created_at', '<', $params['to']);
        }
    }

    public function updatedAt($date)
    {
        $table = $this->getModel()->getTable();

        $params = [];
        $params['from'] = Carbon::parse(strtotime($date))->format('Y-m-d') . ' 00:00:01';
        $params['to'] = Carbon::parse(strtotime($date))->format('Y-m-d') . ' 23:59:59';


        if (!empty($params)) {
            $this->query->where($table . '.updated_at', '>', $params['from']);
            $this->query->where($table . '.updated_at', '<', $params['to']);
        }

    }

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
        } else {
            // $this->query->where($table . '.created_at', '>', $minDate);
        }

    }
}
