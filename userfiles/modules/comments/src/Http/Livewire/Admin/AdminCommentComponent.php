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

    public $rules = [
        'comment.comment_body' => 'required',
    ];

    public function preview()
    {

    }

    public function edit() {

        $comment = Comment::find($this->comment->id);
        if ($comment) {
            $this->authorize('update', $comment);
            $this->isEditing = true;
        }
    }

    public function save()
    {
        $comment = Comment::find($this->comment->id);
        if ($comment) {
            $this->authorize('update', $comment);
            $comment->comment_body = $this->comment->comment_body;
            $comment->save();
            $this->isEditing = false;
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
        }
    }

    public function executeCommentDelete() {

        $comment = Comment::withTrashed()->where('id',$this->comment->id)->first();
        if ($comment) {
            $this->authorize('delete', $comment);
            $comment->forceDelete();
        }
    }

    public function executeCommentMarkAsTrash() {

        $comment = Comment::find($this->comment->id);
        if ($comment) {
            $this->authorize('delete', $comment);
            $comment->delete();
        }
    }

    public function markAsNotTrash()
    {
        $comment = Comment::withTrashed()->find($this->comment->id);
        if ($comment) {

            $this->authorize('update', $comment);

            $comment->restore();
        }
    }

    public function render()
    {
        return view('comments::admin.livewire.comment');
    }
}
