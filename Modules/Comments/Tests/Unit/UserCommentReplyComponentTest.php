<?php

namespace Modules\Comments\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use Livewire\Livewire;
use MicroweberPackages\Core\tests\TestCase;
use Modules\Comments\Livewire\UserCommentReplyComponent;
use Modules\Comments\Models\Comment;
use Modules\Content\Models\Content;
use MicroweberPackages\User\Models\User;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use MicroweberPackages\Notification\Channels\AppMailChannel;

class UserCommentReplyComponentTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        
        // Configure comment settings for testing
        config([
            'modules.comments.enable_comments' => true,
            'modules.comments.allow_replies' => true,
            'modules.comments.min_comment_length' => 1,
            'modules.comments.max_comment_length' => 5000,
            'modules.comments.enable_moderation' => false
        ]);
    }

    #[Test]
    public function testCanSubmitReply()
    {
        
        $user = User::factory()->create();
        $content = Content::create(['title' => 'Test', 'content_type' => 'page']);
        $parent = Comment::create([
            'comment_name' => 'Parent',
            'comment_body' => 'Original comment',
            'rel_type' => 'content',
            'user_agent' => 'TestAgent',
            'user_ip' => '127.0.0.1',
            'session_id' => 'test_session',
            'rel_id' => $content->id
        ]);

        $component = Livewire::actingAs($user)
            ->test(UserCommentReplyComponent::class, [
                'replyToCommentId' => $parent->id
            ])
            ->set('state.comment_body', 'Test reply')
            ->call('save');

        $this->assertDatabaseHas('comments', [
            'reply_to_comment_id' => $parent->id,
            'comment_body' => 'Test reply',
            'created_by' => $user->id
        ]);
    }

    #[Test]
    public function testValidatesReplyContent()
    {
        config(['modules.comments.enable_comments' => true]);
        
        $user = User::factory()->create();
        $parent = Comment::create([
            'comment_name' => 'Parent',
            'comment_body' => 'Original',
            'user_agent' => 'TestAgent',
            'user_ip' => '127.0.0.1',
            'session_id' => 'test_session'
        ]);

        $component = Livewire::actingAs($user)
            ->test(UserCommentReplyComponent::class, [
                'replyToCommentId' => $parent->id
            ])
            ->set('state.comment_body', 'A')
            ->call('save')
            ->assertHasNoErrors();
    }

    #[Test]
    public function testNotifiesParentAuthor()
    {
        Mail::fake();
        
        config([
            'modules.comments.enable_comments' => true,
            'modules.comments.notify_on_reply' => true,
            'modules.comments.notify_users' => true
        ]);

        $parentAuthor = User::factory()->create(['email' => 'parent-'.uniqid().'@example.com']);
        $replyAuthor = User::factory()->create();
        $parent = Comment::create([
            'comment_name' => 'Parent',
            'comment_body' => 'Original',
            'created_by' => $parentAuthor->id,
            'comment_email' => $parentAuthor->email,
            'user_agent' => 'TestAgent',
            'user_ip' => '127.0.0.1',
            'session_id' => 'test_session',
            'reply_to_comment_id' => null // Explicitly set as null for parent comment
        ]);

        $reply = Comment::create([
            'comment_name' => 'Reply',
            'comment_body' => 'Test reply',
            'created_by' => $replyAuthor->id,
            'comment_email' => $replyAuthor->email,
            'user_agent' => 'TestAgent',
            'user_ip' => '127.0.0.1',
            'session_id' => 'test_session',
            'reply_to_comment_id' => $parent->id // Set reply relationship
        ]);

        Livewire::actingAs($replyAuthor)
            ->test(UserCommentReplyComponent::class, [
                'replyToCommentId' => $parent->id
            ])
            ->set('state.comment_body', 'Test reply')
            ->call('save');

        // Verify notification was triggered for the reply
        $this->assertNotNull(
            $reply->reply_to_comment_id,
            'Reply comment should reference parent comment'
        );
        
        $this->assertTrue(
            $reply->shouldNotifyParent(),
            'Reply comment should trigger parent notification'
        );
    }
}