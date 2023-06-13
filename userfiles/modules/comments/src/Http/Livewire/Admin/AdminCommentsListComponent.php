<?php
namespace MicroweberPackages\Modules\Comments\Http\Livewire\Admin;

use Livewire\WithPagination;
use MicroweberPackages\Livewire\Auth\Access\AuthorizesRequests;
use MicroweberPackages\Modules\Comments\Models\Comment;
use function Clue\StreamFilter\fun;

class AdminCommentsListComponent extends \MicroweberPackages\Admin\Http\Livewire\AdminComponent
{
    use WithPagination;
    use AuthorizesRequests;

    protected $paginationTheme = 'bootstrap';

    public $listeners = [
        'commentAdded' => '$refresh',
        'executeCommentDelete'=>'executeCommentDelete',
        'executeCommentMarkAsTrash'=>'executeCommentMarkAsTrash',
    ];

    public $filter = [
        "keyword" => "",
        "orderField" => "id",
        "orderType" => "desc",
        "status"=>'all'
    ];

    public $orderBy = 'newest';

    public $queryString = [
        'filter',
        'itemsPerPage',
        'page',
        'orderBy'
    ];

    public $itemsPerPage = 10;

    public function filterByContentId($id)
    {
        $this->filter['content_id'] = $id;
    }

    public function preview()
    {

    }

    public function markAsModerated($id)
    {
        $comment = Comment::find($id);
        if ($comment) {

            $this->authorize('update', $comment);

            $comment->is_new = 0;
            $comment->is_moderated = 1;
            $comment->save();
        }
    }

    public function markAsUnmoderated($id)
    {
        $comment = Comment::find($id);
        if ($comment) {

            $this->authorize('update', $comment);

            $comment->is_new = 1;
            $comment->is_moderated = 0;
            $comment->save();
        }
    }

    public function markAsSpam($id)
    {
        $comment = Comment::find($id);
        if ($comment) {

            $this->authorize('update', $comment);

            $comment->is_spam = 1;
            $comment->is_moderated = 0;
            $comment->save();
        }
    }

    public function markAsNotSpam($id)
    {
        $comment = Comment::find($id);
        if ($comment) {

            $this->authorize('update', $comment);

            $comment->is_spam = 0;
            $comment->is_moderated = 1;
            $comment->save();
        }
    }

    public function executeCommentDelete($id) {

        $comment = Comment::withTrashed()->where('id',$id)->first();
        if ($comment) {
            $this->authorize('delete', $comment);
            $comment->forceDelete();
        }
    }

    public function executeCommentMarkAsTrash($id) {
        $comment = Comment::find($id);
        if ($comment) {
            $this->authorize('delete', $comment);
            $comment->delete();
        }
    }

    public function markAsNotTrash($id)
    {
        $comment = Comment::withTrashed()->find($id);
        if ($comment) {

            $this->authorize('update', $comment);

            $comment->restore();
        }
    }

    public function render()
    {

        $countAll = Comment::forAdminPreview()->count();
        $countPending = Comment::pending()->count();
        $countApproved = Comment::approved()->count();
        $countSpam = Comment::spam()->count();
        $countTrashed = Comment::onlyTrashed()->count();

        $getCommentsQuery = Comment::query();

        if (isset($this->filter['keyword'])) {
            $keyword = trim($this->filter['keyword']);
            if (!empty($keyword)) {
                $getCommentsQuery->where('comment_body', 'like', '%' . $keyword . '%');
            }
        }
        if (isset($this->filter['status'])) {
            if ($this->filter['status'] == 'pending') {
                $getCommentsQuery->pending();
            }
            elseif ($this->filter['status'] == 'approved') {
                $getCommentsQuery->approved();
            }
            elseif ($this->filter['status'] == 'spam') {
                $getCommentsQuery->spam();
            }
            elseif ($this->filter['status'] == 'trash') {
                $getCommentsQuery->onlyTrashed();
            }
            else {
                $getCommentsQuery->where(function ($query) {
                    $query->forAdminPreview();
                });
            }
        }

        if ($this->orderBy == 'oldest') {
            $getCommentsQuery->orderBy('created_at', 'asc');
        } else {
            $getCommentsQuery->orderBy('created_at', 'desc');
        }

        $getComments = $getCommentsQuery->paginate($this->itemsPerPage);

        return view('comments::admin.livewire.comments-list', [
            'comments' => $getComments,
            'countAll' => $countAll,
            'countPending' => $countPending,
            'countApproved' => $countApproved,
            'countSpam' => $countSpam,
            'countTrashed' => $countTrashed,
        ]);
    }
}
