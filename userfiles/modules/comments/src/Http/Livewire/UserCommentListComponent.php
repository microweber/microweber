<?php

namespace MicroweberPackages\Modules\Comments\Http\LiveWire;

use Livewire\Component;
use Livewire\WithPagination;
use MicroweberPackages\Modules\Comments\Models\Comment;

class UserCommentListComponent extends Component {

    use WithPagination;

    public $relId;
    public $state = [];

    protected $listeners = [
        'commentAdded' => '$refresh',
        'commentDeleted' => '$refresh',
        'commentUpdated' => '$refresh',
    ];

    public $commentsPage = 0;

    public $queryString = [
        'commentsPage'
    ];

    public function mount($relId = false)
    {
        $this->relId = $relId;
    }

    public function render()
    {
        $getComments = Comment::where('reply_to_comment_id', null)
            ->orderBy('created_at', 'desc')
            ->paginate(3, ['*'], 'commentsPage');

        return view('comments::livewire.user-comment-list-component', [
            'comments' => $getComments,
        ]);
    }

}
