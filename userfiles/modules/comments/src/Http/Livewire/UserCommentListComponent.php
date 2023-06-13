<?php

namespace MicroweberPackages\Modules\Comments\Http\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use MicroweberPackages\Livewire\Auth\Access\AuthorizesRequests;
use MicroweberPackages\Modules\Comments\Models\Comment;

class UserCommentListComponent extends Component {

    use AuthorizesRequests;
    use WithPagination;

    public $relId;
    public $state = [];

    protected $listeners = [
        'commentAdded' => '$refresh',
        'commentDeleted' => '$refresh',
        'commentUpdated' => '$refresh',
        'deleteComment' => 'delete',
    ];

    public $commentsPage = 0;

    public $queryString = [
        'commentsPage'
    ];

    public function mount($relId = null)
    {
        $this->relId = $relId;
    }

    public function delete($commentId = false)
    {
        $getComment = Comment::where('id', $commentId)->first();
        if ($getComment) {

            $this->authorize('delete', $getComment);

            $getComment->deleteWithReplies();

            $this->emit('commentDeleted', $commentId);
            $this->emit('$refresh');
        }
    }

    public function render()
    {
        $getComments = Comment::where(function ($query) {
                $query->where('reply_to_comment_id', 0);
                $query->orWhereNull('reply_to_comment_id');
            })
            ->where('rel_id', $this->relId)
            ->where('rel_type', 'content')
            ->published()
            ->orderBy('created_at', 'desc')
            ->paginate(3, ['*'], 'commentsPage');


        return view('comments::livewire.user-comment-list-component', [
            'comments' => $getComments,
        ]);
    }

}
