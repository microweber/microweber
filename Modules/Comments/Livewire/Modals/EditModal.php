<?php

namespace Modules\Comments\Livewire\Modals;

use Livewire\Component;
use LivewireUI\Modal\Contracts\ModalComponent;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Validator;
use LivewireUI\Modal\Modal;
use MicroweberPackages\Livewire\Auth\Access\AuthorizesRequests;
use MicroweberPackages\Livewire\MwModal;
use Modules\Comments\Livewire\AuthorizesEditCommentsRequests;
use Modules\Comments\Models\Comment;
use Modules\Comments\Services\CommentsManager;

class EditModal extends \LivewireUI\Modal\ModalComponent
{
    use AuthorizesEditCommentsRequests;

    public $commentId;
    public $captcha = '';
    public $state = [
        'comment_body' => '',
    ];

    protected CommentsManager $commentsManager;

    public function boot()
    {
        $this->commentsManager = app(CommentsManager::class);
    }

    public function mount($commentId = null)
    {
        if ($commentId) {
            $this->commentId = $commentId;
            $comment = Comment::find($commentId);

            if ($comment && $this->authorizeCheck('update', $comment)) {
                $this->state['comment_body'] = $comment->comment_body;
            }
        }
    }

    public function isEnabledCaptcha()
    {
        return module_option('comments', 'enable_captcha', config('modules.comments.enable_captcha'));
    }

    public function validateCaptcha()
    {
        if (!$this->isEnabledCaptcha()) {
            return true;
        }

        $validateCaptcha = Validator::make(['captcha' => $this->captcha], [
            'captcha' => 'required|captcha'
        ]);

        if ($validateCaptcha->fails()) {
            $this->addError('captcha', _e('Invalid captcha code.', true));
            return false;
        }

        return true;
    }

    public function save()
    {
        $comment = Comment::find($this->commentId);

        if (!$comment || !$this->authorizeCheck('update', $comment)) {
            return;
        }

        $hasRateLimiterId = $comment->id . user_ip();

        if (RateLimiter::tooManyAttempts('edit-comment:' . $hasRateLimiterId, $perMinute = 1)) {
            $this->addError('state.comment_body', _e('Only one edit is allowed per minute. You may try again after 1 minute.', true));
            return;
        }

        if (!$this->validateCaptcha()) {
            return;
        }

        $comment->update([
            'comment_body' => $this->state['comment_body']
        ]);

        RateLimiter::hit('edit-comment:' . $hasRateLimiterId);

        $this->dispatch('commentUpdated', commentId: $comment->id);
        $this->dispatch('closeModal');
        $this->dispatch('refreshCommentsList')->to('comments::user-comment-list');
    }


    public function render(): \Illuminate\View\View
    {


        return view('modules.comments::livewire.modals.edit-modal', [
            'enableCaptcha' => $this->isEnabledCaptcha(),
        ]);
    }
}
