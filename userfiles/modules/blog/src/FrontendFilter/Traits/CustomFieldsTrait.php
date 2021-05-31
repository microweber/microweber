<?php
namespace MicroweberPackages\Blog\FrontendFilter\Traits;

trait CustomFieldsTrait {

    public function applyQueryCustomFields()
    {
        $filters = $this->request->get('filters');

        if (!empty($filters)) {
            $this->queryParams['filters'] = $filters;
            $this->query->whereCustomField($filters);
        }
    }
}
