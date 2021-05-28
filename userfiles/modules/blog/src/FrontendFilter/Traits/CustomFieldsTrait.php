<?php
namespace MicroweberPackages\Blog\FrontendFilter\Traits;

trait CustomFieldsTrait {

    public function applyQueryCustomFields()
    {
        $filters = $this->request->get('filters');

        // Except keys
        if (isset($filters['from_price'])) {
            unset($filters['from_price']);
        }
        if (isset($filters['to_price'])) {
            unset($filters['to_price']);
        }

        if (!empty($filters)) {
            $this->queryParams['filters'] = $filters;
            $this->query->whereCustomField($filters);
        }
    }
}
