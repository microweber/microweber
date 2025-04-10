<?php

namespace Modules\Comments\Tests\Unit;

use PHPUnit\Framework\Attributes\Test;
use Livewire\Livewire;
use MicroweberPackages\Core\tests\TestCase;
use Faker\Factory;
use Modules\Comments\Livewire\UserCommentListComponent;
use Modules\Comments\Models\Comment;
use Modules\Content\Models\Content;

class UserCommentListComponentTest extends TestCase
{
    protected $faker;

    protected function setUp(): void
    {
        parent::setUp();
        $this->faker = Factory::create();
    }

    #[Test]
    public function testRendersCommentsForContent()
    {
        $content = Content::create([
            'title' => $this->faker->sentence,
            'content_type' => $this->faker->randomElement(['page', 'post', 'product'])
        ]);

        $testUserName = $this->faker->name;
$testComment = $this->faker->paragraph;

Comment::create([
    'comment_name' => $testUserName,
    'comment_body' => $testComment,
    'rel_type' => 'content',
    'rel_id' => $content->id
]);

$component = Livewire::test(UserCommentListComponent::class, [
    'relType' => 'content',
    'relId' => $content->id
]);

$component->assertSee($testUserName);
$component->assertSee($testComment);
    }

    #[Test] 
    public function testPaginatesComments()
    {
        $content = Content::create([
            'title' => $this->faker->sentence,
            'content_type' => $this->faker->randomElement(['page','post','product'])
        ]);
        
        // Create more comments than pagination limit
        $testComments = [];
for ($i = 0; $i < 25; $i++) {
    $testComments[] = Comment::create([
        'comment_name' => $this->faker->unique()->name,
        'comment_body' => $this->faker->paragraph,
        'rel_type' => 'content',
        'rel_id' => $content->id
    ]);
}

        $component = Livewire::test(UserCommentListComponent::class, [
            'relType' => 'content',
            'relId' => $content->id
        ]);

        // Verify test comments were created
$this->assertCount(25, Comment::where('rel_id', $content->id)->get());

// Verify component's data matches expected query
$expectedQuery = Comment::where('rel_id', $content->id)
    ->orderBy('created_at', 'desc')
    ->paginate(10, ['*'], 'commentsPage', 1);

$renderedComments = $component->viewData('comments');
$this->assertEquals(
    $expectedQuery->pluck('id')->toArray(),
    $renderedComments->pluck('id')->toArray()
);

// Verify pagination controls
$component->assertSee('Next &raquo;');
$component->assertDontSee('Previous &laquo;');
    }
}