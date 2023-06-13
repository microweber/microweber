<?php

namespace MicroweberPackages\Modules\Comments\Http\Livewire\Admin;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use MicroweberPackages\Modules\Comments\Http\Livewire\UserCommentReplyComponent;
use MicroweberPackages\Modules\Comments\Models\Comment;

class AdminCommentComponent extends UserCommentReplyComponent
{
    use AuthorizesRequests;

    public $comment;

    public $isEditing = false;
    public $commentBodyEdit = '';

    public $rules = [
        'comment.comment_body' => 'required',
    ];

    public $listeners = [
        'executeCommentDelete'=>'executeCommentDelete',
        'executeCommentMarkAsTrash'=>'executeCommentMarkAsTrash',
    ];

    public function preview()
    {

    }

    public function edit() {

        $comment = Comment::find($this->comment->id);
        if ($comment) {
            $this->authorize('update', $comment);
            $this->commentBodyEdit = $comment->comment_body_original;
            $this->isEditing = true;
        }
    }

    public function save()
    {
        $comment = Comment::find($this->comment->id);
        if ($comment) {
            $this->authorize('update', $comment);
            $comment->comment_body = $this->commentBodyEdit;
            $comment->save();

            $this->comment = $comment;
            $this->isEditing = false;

            $this->emit('commentUpdated');
        }
    }

    public function markAsModerated()
    {
        $comment = Comment::find($this->comment->id);
        if ($comment) {

            $this->authorize('update', $comment);

            $comment->is_new = 0;
            $comment->is_moderated = 1;
            $comment->save();

            $this->emit('commentUpdated');
        }
    }

    public function markAsUnmoderated()
    {
        $comment = Comment::find($this->comment->id);
        if ($comment) {

            $this->authorize('update', $comment);

            $comment->is_new = 1;
            $comment->is_moderated = 0;
            $comment->save();

            $this->emit('commentUpdated');
        }
    }

    public function markAsSpam()
    {
        $comment = Comment::find($this->comment->id);
        if ($comment) {

            $this->authorize('update', $comment);

            $comment->is_spam = 1;
            $comment->is_moderated = 0;
            $comment->save();

            $this->emit('commentUpdated');
        }
    }

    public function markAsNotSpam()
    {
        $comment = Comment::find($this->comment->id);
        if ($comment) {

            $this->authorize('update', $comment);

            $comment->is_spam = 0;
            $comment->is_moderated = 1;
            $comment->save();

            $this->emit('commentUpdated');
        }
    }

    public function executeCommentDelete() {

        $comment = Comment::withTrashed()->where('id',$this->comment->id)->first();
        if ($comment) {
            $this->authorize('delete', $comment);
            $comment->forceDelete();

            $this->emit('commentUpdated');
        }
    }

    public function executeCommentMarkAsTrash() {

        $comment = Comment::find($this->comment->id);
        if ($comment) {
            $this->authorize('delete', $comment);
            $comment->delete();

            $this->emit('commentUpdated');
        }
    }

    public function markAsNotTrash()
    {
        $comment = Comment::withTrashed()->find($this->comment->id);
        if ($comment) {

            $this->authorize('update', $comment);

            $comment->restore();

            $this->emit('commentUpdated');
        }
    }

    public function render()
    {
        return view('comments::admin.livewire.comment');
    }
}
