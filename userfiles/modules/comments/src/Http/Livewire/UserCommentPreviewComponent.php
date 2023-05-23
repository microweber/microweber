<?php

namespace MicroweberPackages\Modules\Comments\Http\LiveWire;

use Livewire\Component;
use MicroweberPackages\Modules\Comments\Models\Comment;

class UserCommentPreviewComponent extends Component {

    public $commentId;
    public $state = [];

    public function mount($commentId = false)
    {
        $this->commentId = $commentId;
    }

    public function delete()
    {
       $this->emit('deleteComment', $this->commentId);
    }

    public function render()
    {
        $getComment = Comment::where('id', $this->commentId)->first();

        return view('comments::livewire.user-comment-preview-component', [
            'comment' => $getComment,
        ]);

    }

}
