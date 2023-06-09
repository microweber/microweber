<?php

namespace MicroweberPackages\Modules\Comments\Http\LiveWire;

use Livewire\Component;
use MicroweberPackages\Content\Models\Content;
use MicroweberPackages\Modules\Comments\Events\NewComment;
use MicroweberPackages\Modules\Comments\Models\Comment;
use MicroweberPackages\Modules\Comments\Notifications\NewCommentNotification;
use MicroweberPackages\User\Models\User;

class UserCommentReplyComponent extends Component
{
    public $state = [
        'comment_name' => '',
        'comment_email' => '',
        'comment_body' => '',
    ];

    public function mount($relId = null, $replyToCommentId = null)
    {
        $this->state['rel_id'] = $relId;
        $this->state['reply_to_comment_id'] = $replyToCommentId;
    }

    public function render()
    {
        $enableCaptcha = true;
        $enableCaptchaOption = get_option('enable_captcha','comments');
        if ($enableCaptchaOption == 'n') {
            $enableCaptcha = false;
        }

        $allowAnonymousComments = true;
        $allowAnonymousCommentsOption = get_option('allow_anonymous_comments','comments');
        if ($allowAnonymousCommentsOption == 'n') {
            $allowAnonymousComments = false;
        }

        $allowToComment = false;
        if (user_id() || $allowAnonymousComments) {
            $allowToComment = true;
        }

        return view('comments::livewire.user-comment-reply-component',[
            'enableCaptcha' => $enableCaptcha,
            'allowAnonymousComments' => $allowAnonymousComments,
            'allowToComment' => $allowToComment,
        ]);
    }

    public function save()
    {
        $validate = [
            'state.rel_id' => 'required|min:1',
            'state.comment_body' => 'required|min:3',
        ];
        if (!user_id()) {
            $validate['state.comment_name'] = 'required|min:3';
            $validate['state.comment_email'] = 'required|email';
        }

        $this->validate($validate);

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
        $comment->session_id = session_id();

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

        if ($needsApproval) {
            $comment->is_new = 1;
            $comment->is_moderated = 0;
        } else {
            $comment->is_new = 0;
            $comment->is_moderated = 1;
        }

        $comment->comment_body = $this->state['comment_body'];
        $comment->save();

       // event(new NewComment($comment));

      //  Notification::send(User::whereIsAdmin(1)->get(), new NewCommentNotification($comment));

        $this->state['comment_body'] = '';
        $this->state['comment_name'] = '';
        $this->state['comment_email'] = '';

        $this->emit('commentAdded', $comment->id);

    }
}

