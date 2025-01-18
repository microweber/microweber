<?php

namespace Modules\Comments\Tests\Unit;

use MicroweberPackages\Core\tests\TestCase;
use Modules\Content\Models\Content;


class CommentModelTest extends TestCase
{
    public function testContentTitleRelationship()
    {
        // Create a content item
        $content = Content::create([
            'title' => 'Test Content Title',
            'content' => 'Test Content Body',
            'content_type' => 'page'
        ]);

        // Create a comment linked to the content
        $comment = \Modules\Comments\Models\Comment::create([
            'comment_name' => 'Test User',
            'comment_email' => 'test@example.com',
            'comment_body' => 'Test comment body',
            'rel_type' => morph_name(Content::class),
            'rel_id' => $content->id
        ]);

        // Test that contentTitle returns a relationship instance
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasOne::class, $comment->contentTitle());

        // Test that we can get the content title through the relationship
        $this->assertEquals('Test Content Title', $comment->content->title);

        // Test with non-existent content
        $commentWithoutContent = \Modules\Comments\Models\Comment::create([
            'comment_name' => 'Test User 2',
            'comment_email' => 'test2@example.com',
            'comment_body' => 'Test comment body 2',
            'rel_type' => morph_name(Content::class),
            'rel_id' => 9999999999 // Non-existent content ID
        ]);

        // Should still return a relationship instance even if content doesn't exist
        $this->assertInstanceOf(\Illuminate\Database\Eloquent\Relations\HasOne::class, $commentWithoutContent->contentTitle());

        // Content should be null when not found
        $this->assertNull($commentWithoutContent->content);
    }
}
