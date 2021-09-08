<?php
namespace MicroweberPackages\CustomField\Repositories;

use MicroweberPackages\CustomField\Models\CustomField;
use MicroweberPackages\Repository\Repositories\AbstractRepository;

/**
 * @mixin AbstractRepository
 */
class CustomFieldRepository extends AbstractRepository
{

    /**
     * Specify Model class name
     *
     * @return string
     */
    public $model = CustomField::class;

    public function get($params)
    {
        return $this->cacheCallback(__FUNCTION__, func_get_args(), function () use ($params) {

            $getCustomFields = CustomField::query();

            if (!empty($params['id'])) {
                $getCustomFields->where('id', $params['id']);
            }

            if (isset($params['rel_id'])) {
                $getCustomFields->where('rel_id', $params['rel_id']);
                $getCustomFields->where('rel_type', $params['rel_type']);
            }

            if (!empty($params['type'])) {
                $getCustomFields->where('type', $params['type']);
            }

            if (!empty($params['session_id'])) {
                $getCustomFields->where('session_id', $params['session_id']);
            }

            $getCustomFields->orderBy('position', 'asc');

            $getCustomFields = $getCustomFields->get();

            $customFields = [];

            if ($getCustomFields) {
                foreach ($getCustomFields as $customField) {

                    $readyCustomField = $customField->toArray();

                    $readyCustomField['value'] = '';
                    $readyCustomField['values'] = [];
                    $readyCustomField['values_plain'] = '';

                    $getCustomFieldValue = $customField->fieldValue()->get();
                    if (isset($getCustomFieldValue[0])) {
                        $readyCustomField['value'] = $getCustomFieldValue[0]->value;
                        foreach ($getCustomFieldValue as $customFieldValue) {
                            $readyCustomField['values'][] = $customFieldValue->value;
                        }
                    }

                    if (!empty($readyCustomField['values'])) {
                        $readyCustomField['values_plain'] = implode(',', $readyCustomField['values']);
                    }

                    $customFields[] = $readyCustomField;
                }
            }

            return $customFields;
        });
    }



}
