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
        $getComment = Comment::where('id', $this->commentId)->first();
        if ($getComment) {
            $getComment->delete();
            $this->emit('commentDeleted');
        }
    }

    public function render()
    {
        $getComment = Comment::where('id', $this->commentId)->first();

        return view('comments::livewire.user-comment-preview-component', [
            'comment' => $getComment,
        ]);
    }

}
