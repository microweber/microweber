<?php

namespace Modules\Comments\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use MicroweberPackages\Core\tests\TestCase;
use Modules\Comments\Models\Comment;
use Modules\Content\Models\Content;

class CommentModelTest extends TestCase
{
    #[Test]
    public function testContentTitleRelationship()
    {
        $content = Content::create([
            'title' => 'Test Content Title',
            'content' => 'Test Content Body',
            'content_type' => 'page'
        ]);

        $comment = Comment::create([
            'comment_name' => 'Test User',
            'comment_email' => 'test@example.com',
            'comment_body' => 'Test comment body',
            'rel_type' => morph_name(Content::class),
            'rel_id' => $content->id
        ]);

        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasOne::class, $comment->contentTitle());
        $this->assertEquals('Test Content Title', $comment->content->title);
    }

    #[Test]
    public function testCommentApprovalScopes()
    {
        // Clear existing comments
        Comment::query()->delete();

        $approved = Comment::create([
            'comment_name' => 'Approved',
            'comment_body' => 'Test',
            'is_moderated' => 1,
            'is_new' => 0
        ]);
        
        $pending = Comment::create([
            'comment_name' => 'Pending',
            'comment_body' => 'Test',
            'is_moderated' => 0,
            'is_new' => 1
        ]);

        // Verify immediate attributes
        $this->assertEquals(1, $approved->getAttributes()['is_moderated'], 'Approved comment should have is_moderated=1 in attributes');
        $this->assertEquals(0, $approved->getAttributes()['is_new'], 'Approved comment should have is_new=0 in attributes');
        
        // Verify fresh database values
        $freshApproved = Comment::find($approved->id);
        $this->assertEquals(1, $freshApproved->is_moderated, 'Approved comment should have is_moderated=1 in DB');
        $this->assertEquals(0, $freshApproved->is_new, 'Approved comment should have is_new=0 in DB');

        // Verify scopes on individual instances
        $this->assertTrue($approved->is(Comment::approved()->first()), 'Approved comment should match approved scope');
        $this->assertTrue($pending->is(Comment::pending()->first()), 'Pending comment should match pending scope');

        // Verify counts
        $this->assertEquals(1, Comment::where('is_moderated', 1)->count(), 'Should find 1 moderated comment');
        $this->assertEquals(1, Comment::where('is_new', 1)->where('is_moderated', 0)->count(), 'Should find 1 pending comment');

        // Verify instance methods
        $this->assertFalse($approved->isPending(), 'Approved comment should not be pending');
        $this->assertTrue($pending->isPending(), 'Pending comment should be pending');
    }

    #[Test]
    public function testCommentSpamDetection()
    {
        $comment = new Comment([
            'comment_body' => 'Buy cheap viagra'
        ]);

        $this->assertTrue($comment->isSpam());
    }

    #[Test]
    public function testCommentReplyRelationship()
    {
        $parent = Comment::create([
            'comment_name' => 'Parent',
            'comment_body' => 'Test'
        ]);

        $reply = Comment::create([
            'comment_name' => 'Reply',
            'comment_body' => 'Test',
            'reply_to_comment_id' => $parent->id  // Using correct field name
        ]);

        // Debug output
        $freshParent = Comment::with('replies')->find($parent->id);
        $freshReply = Comment::with('parent')->find($reply->id);

        $this->assertCount(1, $freshParent->replies, 'Parent should have 1 reply');
        $this->assertEquals($parent->id, $freshReply->parent->id, 'Reply should reference parent');
    }
}
