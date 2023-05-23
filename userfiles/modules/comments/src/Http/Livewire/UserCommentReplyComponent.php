<?php

namespace MicroweberPackages\Modules\Comments\Http\LiveWire;

use Livewire\Component;

class UserCommentReplyComponent extends Component
{

    public $relId;
    public $state = [];

    public function mount($relId = false)
    {
        $this->relId = $relId;
    }

    public function render()
    {
        return view('comments::livewire.user-comment-reply-component');
    }

    public function save()
    {
        $this->validate([
            'state.comment_name' => 'required|min:3',
            'state.comment_email' => 'required|email',
            'state.comment_body' => 'required|min:3',
        ]);

        $comment = new \MicroweberPackages\Comment\Models\Comment();
        $comment->rel_id = $this->relId;
        $comment->rel_type = 'content';
        $comment->comment_name = $this->state['comment_name'];
        $comment->comment_email = $this->state['comment_email'];
        $comment->comment_body = $this->state['comment_body'];
        $comment->save();

        $this->state = [];
        $this->emit('commentAdded', $comment->id);

    }
}
