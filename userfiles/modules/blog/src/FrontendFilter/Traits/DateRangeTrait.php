<?php

namespace MicroweberPackages\Blog\FrontendFilter\Traits;

use Illuminate\Support\Facades\URL;

trait DateRangeTrait
{
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
