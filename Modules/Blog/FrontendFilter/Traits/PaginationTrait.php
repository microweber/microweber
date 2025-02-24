<?php
namespace Modules\Blog\FrontendFilter\Traits;

trait PaginationTrait {

    protected $pagination;

    public function pagination($theme = 'pagination::bootstrap-4-flex')
    {
        $disablePagination = get_option('disable_pagination', $this->params['moduleId']);
        if ($disablePagination) {
            return false;
        }

        return $this->pagination->links($theme);
    }

    public function total()
    {
        return $this->pagination->total();
    }

    public function count()
    {
        return $this->pagination->count();
    }

    public function items()
    {
        return $this->pagination->items();
    }

    public function results()
    {
        return $this->pagination->items();
    }

    public function applyQueryPage()
    {
        $page = $this->request->get('page', false);
        if ($page) {
            $this->queryParams['page'] = $page;
        }
    }
}
