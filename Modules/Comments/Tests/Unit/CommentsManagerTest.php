<?php

namespace Modules\Comments\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use MicroweberPackages\Core\tests\TestCase;
use Modules\Comments\Services\CommentsManager;
use Modules\Content\Models\Content;
use Modules\Comments\Filament\CommentsModuleSettings;

class CommentsManagerTest extends TestCase
{
    protected $manager;

    protected function setUp(): void
    {
        parent::setUp();
        $this->manager = app('comments_manager');
        
        // Ensure comments are enabled for all tests
        config(['modules.comments.enable_comments' => true]);
        config(['modules.comments.allow_guest_comments' => true]);
    }

    #[Test]
    public function testCreateComment()
    {
        $content = Content::create([
            'title' => 'Test Content',
            'content_type' => 'page'
        ]);

        $manager = new CommentsManager();
        
        // Set and verify module settings
        config(['modules.comments' => [
            'enable_comments' => true,
            'allow_guest_comments' => true,
            'min_comment_length' => 1,
            'max_comment_length' => 5000
        ]]);
        
        $manager = new CommentsManager();
        $this->assertTrue($manager->getConfig('enable_comments'));
        $this->assertTrue($manager->getConfig('allow_guest_comments'));
        
        $comment = $manager->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'body' => 'Test comment',
            'rel_type' => 'content',
            'rel_id' => $content->id
        ]);

        $this->assertNotNull($comment->id);
        $this->assertEquals('Test User', $comment->comment_name);
    }

    #[Test]
    public function testGetCommentsForContent()
    {
        $content = Content::create([
            'title' => 'Test Content',
            'content_type' => 'page'
        ]);

        $manager = new CommentsManager();
        $manager->create([
            'name' => 'User 1',
            'email' => 'test1@example.com',
            'body' => 'Comment 1',
            'rel_type' => 'content',
            'rel_id' => $content->id
        ]);
        $manager->create([
            'name' => 'User 2',
            'email' => 'test2@example.com',
            'body' => 'Comment 2',
            'rel_type' => 'content',
            'rel_id' => $content->id
        ]);

        $comments = \Modules\Comments\Models\Comment::where('rel_id', $content->id)
            ->where('rel_type', 'content')
            ->get();
        $this->assertCount(2, $comments);
    }
}