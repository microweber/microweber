<?php

namespace MicroweberPackages\Modules\Comments\Http\LiveWire;

use Livewire\Component;

class UserCommentReplyComponent extends Component
{

    public $state = [
        'comment_name' => 'aaaa',
        'comment_email' => 'aaaa@abv.bg',
        'comment_body' => 'aaaaaaaa',
    ];

    public function mount($relId = null, $replyToCommentId = null)
    {
        $this->state['rel_id'] = $relId;
        $this->state['reply_to_comment_id'] = $replyToCommentId;
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

        if (isset($this->state['rel_id'])) {
            $comment->rel_id = $this->state['rel_id'];
            $comment->rel_type = 'content';
        }

        if (isset($this->state['reply_to_comment_id'])) {
            $comment->reply_to_comment_id = $this->state['reply_to_comment_id'];
        }

        $comment->comment_name = $this->state['comment_name'];
        $comment->comment_email = $this->state['comment_email'];
        $comment->comment_body = $this->state['comment_body'];
        $comment->save();

        $this->state = [];
        $this->emit('commentAdded', $comment->id);

    }
}

