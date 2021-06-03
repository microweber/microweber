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
                // dd($customFieldNameKey);

                $fieldName = $customFieldNameKey;
                $fieldValue = '1';

                $this->query->whereHas('customField', function ($query) use ($fieldName, $fieldValue) {
                    $query->where('name_key', \Str::slug($fieldName, '-'))->whereHas('fieldValue', function ($query) use ($fieldValue) {
                        if (is_array($fieldValue)) {
                            $query->whereIn('value', $fieldValue);
                        } else {
                            $query->where('value', $fieldValue);
                        }
                    });
                });

            }
        }
    }
}
