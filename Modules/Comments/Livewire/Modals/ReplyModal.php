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

class ReplyModal extends \LivewireUI\Modal\ModalComponent
{
    use AuthorizesEditCommentsRequests;

    public $relId;
    public $relType;
    public $replyToCommentId;
    public $comment;
    public $captcha = '';
    public $state = [
        'comment_name' => '',
        'comment_email' => '',
        'comment_body' => '',
    ];

    protected CommentsManager $commentsManager;

    public function boot()
    {
        $this->commentsManager = app(CommentsManager::class);
    }

    public function mount($relId = null, $relType = null, $replyToCommentId = null)
    {
        if ($relId && $relType && $replyToCommentId) {
            $this->relId = $relId;
            $this->relType = $relType;
            $this->replyToCommentId = $replyToCommentId;

            $this->comment = Comment::find($replyToCommentId);

            if (auth()->check()) {
                $this->state['comment_name'] = auth()->user()->name;
                $this->state['comment_email'] = auth()->user()->email;
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
        $hasRateLimiterId = $this->relId . $this->replyToCommentId . user_ip();

        if (RateLimiter::tooManyAttempts('save-comment:' . $hasRateLimiterId, $perMinute = 1)) {
            $this->addError('state.comment_body', _e('Only one comment is allowed per minute. You may try again after 1 minute.', true));
            return;
        }

        if (!$this->validateCaptcha()) {
            return;
        }

        $comment = $this->commentsManager->create([
            'rel_id' => $this->relId,
            'rel_type' => $this->relType,
            'reply_to' => $this->replyToCommentId,
            'body' => $this->state['comment_body'],
            'name' => $this->state['comment_name'],
            'email' => $this->state['comment_email'],
        ]);

        RateLimiter::hit('save-comment:' . $hasRateLimiterId);

        $this->dispatch('commentAdded', commentId: $comment->id);
        $this->dispatch('closeModal');
        $this->dispatch('refreshCommentsList')->to('comments::user-comment-list');
    }


    public function render(): \Illuminate\View\View
    {
        return view('modules.comments::livewire.modals.reply-modal', [
            'enableCaptcha' => $this->isEnabledCaptcha(),
            'comment' => $this->comment,
        ]);
    }
}
