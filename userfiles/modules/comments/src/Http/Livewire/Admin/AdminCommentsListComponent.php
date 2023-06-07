<?php
namespace MicroweberPackages\Modules\Comments\Http\Livewire\Admin;

use Livewire\WithPagination;
use MicroweberPackages\Modules\Comments\Models\Comment;

class AdminCommentsListComponent extends \MicroweberPackages\Admin\Http\Livewire\AdminComponent
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $filter = [
        "keyword" => "",
        "orderField" => "id",
        "orderType" => "desc",
    ];

    public $queryString = [
        'filter',
        'itemsPerPage',
        'page'
    ];

    public $itemsPerPage = 10;

    public function render()
    {
        $getComments = Comment::paginate($this->itemsPerPage);

        return view('comments::admin.livewire.comments-list', [
            'comments' => $getComments
        ]);
    }
}
