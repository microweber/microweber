<?php

namespace MicroweberPackages\Blog\FrontendFilter\Traits;

use Illuminate\Support\Facades\URL;

trait DateRangeTrait
{
    public function appendFiltersActiveDateRange()
    {
        $dateRange = $this->request->get('date_range', false);

        if ($dateRange) {
            foreach ($dateRange as $customFieldNameKey=>$dateRangeValues) {
                $filter = new \stdClass();
                $filter->name = $dateRangeValues['from'] . ' - ' . $dateRangeValues['to'];
                $filter->link = '';
                $filter->value = $dateRangeValues['from'];
                $filter->key = 'date_range['.$customFieldNameKey.'][from], date_range['.$customFieldNameKey.'][to]';
                $this->filtersActive[] = $filter;
            }
        }
    }

    public function applyQueryDateRange()
    {
        $dateRange = $this->request->get('date_range', false);
        if ($dateRange && !empty($dateRange)) {
            foreach ($dateRange as $customFieldNameKey => $customFieldDateRange) {

                $fieldName = $customFieldNameKey;
                $from = $customFieldDateRange['from'];
                $to = $customFieldDateRange['to'];

                $this->query->whereHas('customField', function ($query) use ($fieldName, $from, $to) {
                    $query->where('name_key', \Str::slug($fieldName, '-'))->whereHas('fieldValue', function ($query) use ($from, $to) {
                        $query->where('value','>=', $from);
                        $query->where('value','<=', $to);
                    });
                });

            }
        }
    }
}
