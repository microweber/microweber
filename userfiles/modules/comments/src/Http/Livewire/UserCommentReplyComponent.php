<?php

namespace MicroweberPackages\Modules\Comments\Http\Livewire;

use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Livewire\Component;
use MicroweberPackages\Content\Models\Content;
use MicroweberPackages\Livewire\Auth\Access\AuthorizesRequests;
use MicroweberPackages\Modules\Comments\Events\NewComment;
use MicroweberPackages\Modules\Comments\Models\Comment;
use MicroweberPackages\Modules\Comments\Notifications\NewCommentNotification;
use MicroweberPackages\User\Models\User;

class UserCommentReplyComponent extends Component
{
    use AuthorizesRequests;

    public $view = 'comments::livewire.user-comment-reply-component';
    public $successMessage = false;

    public $state = [
        'comment_name' => '',
        'comment_email' => '',
        'comment_body' => '',
    ];

    public $captcha = '';

    public function getListeners()
    {
        return [
            "setCaptcha".md5($this->id) => 'setCaptcha',
        ];
    }

    public function mount($relId = null, $replyToCommentId = null)
    {
        $this->state['rel_id'] = $relId;
        $this->state['reply_to_comment_id'] = $replyToCommentId;
    }

    public function clearSuccessMessage()
    {
        $this->successMessage = false;
    }

    public function isEnabledCaptcha()
    {
        $enableCaptcha = true;
        $enableCaptchaOption = get_option('enable_captcha','comments');
        if ($enableCaptchaOption == 'n') {
            $enableCaptcha = false;
        }

        return $enableCaptcha;
    }

    public function getViewData()
    {

        $allowAnonymousComments = true;
        $allowAnonymousCommentsOption = get_option('allow_anonymous_comments','comments');
        if ($allowAnonymousCommentsOption == 'n') {
            $allowAnonymousComments = false;
        }

        $allowToComment = false;
        if (user_id() || $allowAnonymousComments) {
            $allowToComment = true;
        }

        $comment = Comment::where('id', $this->state['reply_to_comment_id'])->first();

        return [
            'enableCaptcha' => $this->isEnabledCaptcha(),
            'allowAnonymousComments' => $allowAnonymousComments,
            'allowToComment' => $allowToComment,
            'comment' => $comment,
        ];
    }

    public function render()
    {
        $data = $this->getViewData();

        return view($this->view,$data);
    }

    public function setCaptcha($value)
    {
        $this->captcha = $value;
        $this->save();
    }

    public function validateCaptcha()
    {
        $validateCaptcha = Validator::make([ 'captcha'=>$this->captcha ],  [
            'captcha' => 'required|captcha'
        ]);

        if ($validateCaptcha->fails()) {
            $this->emit('openModal', 'captcha-confirm-modal', [
                'action'=>'setCaptcha' . md5($this->id)
            ]);
            return false;
        }

        $this->emit('closeModal', 'captcha-confirm-modal');

        return true;
    }

    public function save()
    {
        $hasRateLimiterId = $this->state['rel_id'] . $this->state['reply_to_comment_id'] . user_ip();

        if (RateLimiter::tooManyAttempts('save-comment:'.$hasRateLimiterId, $perMinute = 1)) {
            $this->addError('state.comment_body', 'Only one comment is allowed per minute. You may try again after 1 minute.');
            return;
        }

        if ($this->isEnabledCaptcha()) {
            $validate = $this->validateCaptcha();
            if (!$validate) {
                return;
            }
        }

        $validate = [
            'state.rel_id' => 'required|min:1',
            'state.comment_body' => 'required|min:3|max:1000',
        ];

        if (!user_id()) {
            $validate['state.comment_name'] = 'required|min:3|max:300';
            $validate['state.comment_email'] = 'required|email|min:3|max:300';
        }

        $this->validate($validate, array(
            'required' => _e('The field is required.', true),
        ));

        $countContent = Content::where('id', $this->state['rel_id'])->active()->count();
        if ($countContent == 0) {
            $this->addError('state.rel_id', 'Content not found');
            return;
        }

        $comment = new Comment();
        $comment->rel_id = $this->state['rel_id'];
        $comment->rel_type = 'content';

        if (isset($this->state['reply_to_comment_id'])) {
            $comment->reply_to_comment_id = $this->state['reply_to_comment_id'];
        }

        $comment->user_ip = user_ip();
        $comment->session_id = Session::getId();

        if (user_id()) {
            $comment->created_by = user_id();
        } else {
            $comment->comment_name = $this->state['comment_name'];
            $comment->comment_email = $this->state['comment_email'];
        }

        $needsApproval = true;
        $requiresApproval = get_option('requires_approval','comments');
        if ($requiresApproval == 'n') {
            $needsApproval = false;
        }
        if (is_admin()) {
            $needsApproval = false;
        }

        if ($needsApproval) {
            $comment->is_new = 1;
            $comment->is_moderated = 0;
        } else {
            $comment->is_new = 0;
            $comment->is_moderated = 1;
        }

        $comment->comment_body = $this->state['comment_body'];
        $comment->save();

        RateLimiter::hit('save-comment:'.$hasRateLimiterId);

       // event(new NewComment($comment));
      //  Notification::sendNow(User::whereIsAdmin(1)->get(), new NewCommentNotification($comment));

        if ($needsApproval) {
            $this->successMessage = _e('Your comment has been added, Waiting moderation.', true);
        } else {
            $this->successMessage = _e('Your comment has been added', true);
        }

        $this->state['comment_body'] = '';
        $this->state['comment_name'] = '';
        $this->state['comment_email'] = '';
        $this->captcha = '';

        $this->emit('commentAdded', $comment->id);

    }
}

