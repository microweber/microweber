<?php

namespace Modules\Comments\Livewire;

use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\On;
use Livewire\Component;
use MicroweberPackages\Livewire\Auth\Access\AuthorizesRequests;
use Modules\Comments\Models\Comment;
use Modules\Comments\Services\CommentsManager;

class UserCommentReplyComponent extends Component
{
    use AuthorizesEditCommentsRequests;

    public $view = 'modules.comments::livewire.user-comment-reply-component';
    public $successMessage = false;
    public $allowReplies = true;
    public $relType;

    public $state = [
        'comment_name' => '',
        'comment_email' => '',
        'comment_body' => '',
    ];

    public $captcha = '';
    protected CommentsManager $commentsManager;

    public function boot()
    {
        $this->commentsManager = app(CommentsManager::class);
    }

    #[On('setCaptcha')]
    public function setCaptcha(string $value)
    {
        $this->captcha = $value;
    }

    public function mount($relId = null, $relType = null, $replyToCommentId = null, $allowReplies = true)
    {
        $this->state['rel_id'] = $relId;
        $this->state['rel_type'] = $relType;
        $this->state['reply_to_comment_id'] = $replyToCommentId;
        $this->allowReplies = $allowReplies;

        if (auth()->check()) {
            $this->state['comment_name'] = auth()->user()->name;
            $this->state['comment_email'] = auth()->user()->email;
        }
    }

    #[On('replyTo')]
    public function handleReply(array $params)
    {
        $this->state['reply_to_comment_id'] = (int)$params['id'];
        $this->state['comment_body'] = (string)$params['body'];
    }

    public function clearSuccessMessage()
    {
        $this->successMessage = false;
    }

    public function isEnabledCaptcha()
    {
        return module_option('comments', 'enable_captcha', config('modules.comments.enable_captcha'));
    }

    public function getViewData()
    {
        if (!$this->allowReplies && $this->state['reply_to_comment_id']) {
            return ['allowToComment' => false];
        }

        $allowToComment = false;
        if (!module_option('comments', 'require_login', config('modules.comments.require_login')) || auth()->check()) {
            $allowToComment = true;
        }

        $comment = null;
        if ($this->state['reply_to_comment_id']) {
            $comment = Comment::where('id', $this->state['reply_to_comment_id'])->first();
        }

        return [
            'enableCaptcha' => $this->isEnabledCaptcha(),
            'allowToComment' => $allowToComment,
            'allowAnonymousComments' => module_option('comments', 'allow_guest_comments', config('modules.comments.allow_guest_comments')),
            'comment' => $comment,
        ];
    }

    public function render()
    {
        $data = $this->getViewData();
        return view($this->view, $data);
    }

    #[On('validateCaptchaValueAndSave')]
    public function validateCaptchaValueAndSave(string $value)
    {
        $this->captcha = $value;
        $this->save();
    }

    public function validateCaptcha()
    {
        $validateCaptcha = Validator::make(['captcha' => $this->captcha], [
            'captcha' => 'required|captcha'
        ]);

        if ($validateCaptcha->fails()) {
            $this->dispatch('openModal', 'captcha-confirm-modal', [
                'action' => 'validateCaptchaValueAndSave'
            ]);
            return false;
        }

        $this->dispatch('closeCaptchaConfirmModal');
        return true;
    }

    public function save()
    {
        if (!$this->allowReplies && $this->state['reply_to_comment_id']) {
            $this->addError('state.comment_body', 'Replies are not allowed.');
            return;
        }

        $hasRateLimiterId = $this->state['rel_id'] . $this->state['reply_to_comment_id'] . user_ip();

        if (RateLimiter::tooManyAttempts('save-comment:' . $hasRateLimiterId, $perMinute = 1)) {
            $this->addError('state.comment_body', 'Only one comment is allowed per minute. You may try again after 1 minute.');
            return;
        }

        if ($this->isEnabledCaptcha()) {
            $validate = $this->validateCaptcha();
            if (!$validate) {
                return;
            }
        }


        $comment = $this->commentsManager->create([
            'rel_id' => $this->state['rel_id'],
            'rel_type' => $this->state['rel_type'],
            'reply_to' => $this->state['reply_to_comment_id'],
            'body' => $this->state['comment_body'],
            'name' => $this->state['comment_name'],
            'email' => $this->state['comment_email'],
        ]);

        RateLimiter::hit('save-comment:' . $hasRateLimiterId);

        if (module_option('comments', 'enable_moderation', config('modules.comments.enable_moderation')) && !is_admin()) {
            $this->successMessage = _e('Your comment has been added, Waiting moderation.', true);
        } else {
            $this->successMessage = _e('Your comment has been added', true);
        }

        $this->state['comment_body'] = '';
        $this->state['comment_name'] = '';
        $this->state['comment_email'] = '';
        $this->captcha = '';

        $this->dispatch('commentAdded', commentId: $comment->id);
        $this->dispatch('refreshCommentsList')->to('comments::user-comment-list');
        $this->dispatch('closeModal');

    }
}
