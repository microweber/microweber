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

    public function preview()
    {

    }

    public function render()
    {

        $countAll = Comment::count();
        $countMine = Comment::where('created_by', user_id())->count();
        $countPending = Comment::where('is_new', 1)->count();
        $countApproved = Comment::where('is_new', 0)->where('is_spam', 0)->count();
        $countSpam = Comment::where('is_spam', 1)->count();
        $countTrashed = Comment::onlyTrashed()->count();

        $getComments = Comment::paginate($this->itemsPerPage);

        return view('comments::admin.livewire.comments-list', [
            'comments' => $getComments,
            'countAll' => $countAll,
            'countMine' => $countMine,
            'countPending' => $countPending,
            'countApproved' => $countApproved,
            'countSpam' => $countSpam,
            'countTrashed' => $countTrashed,
        ]);
    }
}
