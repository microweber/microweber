<?php

namespace Modules\Comments\Livewire;

use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\Features\SupportEvents\HandlesEvents;
use Livewire\WithPagination;
use MicroweberPackages\Livewire\Auth\Access\AuthorizesRequests;
use Modules\Comments\Models\Comment;
use Modules\Comments\Services\CommentsManager;

class UserCommentListComponent extends Component
{
    use AuthorizesEditCommentsRequests;
    use WithPagination;
    use HandlesEvents;

    public $relId;
    public $relType;
    public $showUserAvatar = true;
    public $allowReplies = true;
    public $commentsPerPage = 10;
    public $sortOrder = 'newest';
    public $state = [];

    #[On('refreshCommentsList')]
    public function refreshCommentsList()
    {

         $this->dispatch('$refresh')->self();
    }

    #[On('commentAdded')]
    public function refreshOnCommentAdded()
    {
        $this->resetPage('commentsPage');
        $this->dispatch('$refresh')->self();
    }

    #[On('commentDeleted')]
    public function refreshOnCommentDeleted()
    {
        $this->resetPage('commentsPage');
        $this->dispatch('$refresh')->self();
    }

    #[On('commentUpdated')]
    public function refreshOnCommentUpdated()
    {
        $this->resetPage('commentsPage');
        $this->dispatch('$refresh')->self();
    }

    #[On('deleteComment')]
    public function delete(int $commentId)
    {
        $comment = Comment::where('id', $commentId)->first();
        if ($comment && $this->authorizeCheck('delete', $comment)) {
            $this->commentsManager->delete($comment->id);
            $this->dispatch('commentDeleted', commentId: $commentId);
            $this->dispatch('refreshCommentsList')->to('comments::user-comment-list');


        }
    }

    public $commentsPage = 1; // Start from page 1

    public $queryString = [
        'commentsPage'
    ];

    protected CommentsManager $commentsManager;

    public function boot()
    {
        $this->commentsManager = app(CommentsManager::class);
    }

    public function mount($relId = null, $relType = null, $showUserAvatar = true, $allowReplies = true, $commentsPerPage = 10, $sortOrder = 'newest')
    {
        $this->relId = $relId;
        $this->relType = $relType;
        $this->showUserAvatar = $showUserAvatar;
        $this->allowReplies = $allowReplies;
        $this->commentsPerPage = $commentsPerPage;
        $this->sortOrder = $sortOrder;
    }


    public function render()
    {
        $comments = $this->commentsManager->get([
            'rel_id' => $this->relId,
            'rel_type' => $this->relType,
            'comments_per_page' => $this->commentsPerPage,
            'sort_order' => $this->sortOrder,
            'page_name' => 'commentsPage',
            'page' => $this->commentsPage,
            'root_comments_only' => true
        ]);

        return view('modules.comments::livewire.user-comment-list-component', [
            'comments' => $comments,
            'showUserAvatar' => $this->showUserAvatar,
            'allowReplies' => $this->allowReplies
        ]);
    }
}
