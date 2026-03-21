<?php

namespace MicroweberPackages\LiveEdit\Tests\VisualEditor;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use MicroweberPackages\LiveEdit\Http\Livewire\VisualEditor\VisualEditorComponent;
use Modules\Content\Models\Content;
use Tests\TestCase;

/**
 * Visual Editor Component Tests
 *
 * Comprehensive test suite for the visual drag-and-drop editor functionality.
 *
 * @package MicroweberPackages\LiveEdit\Tests\VisualEditor
 */
class VisualEditorComponentTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Create an admin user for testing
        $this->actingAs(
            \MicroweberPackages\User\Models\User::factory()->create([
                'is_admin' => 1,
            ])
        );
    }

    /**
     * Test component renders successfully
     */
    public function test_component_renders(): void
    {
        $component = Livewire::test(VisualEditorComponent::class);

        $component->assertStatus(200);
        $component->assertSee('Visual Editor');
    }

    /**
     * Test component loads blocks from content
     */
    public function test_component_loads_blocks_from_content(): void
    {
        // Create a content with content_body
        $content = Content::factory()->create([
            'title' => 'Test Page',
            'content_type' => 'page',
            'content_body' => '<p data-mw-block-type="text" data-mw-block-id="block_1">Test content</p>',
        ]);

        $component = Livewire::test(VisualEditorComponent::class, ['contentId' => $content->id]);

        $component->assertStatus(200);
        $component->assertSet('contentId', $content->id);
        $component->assertSet('blocks', function ($blocks) {
            return count($blocks) > 0;
        });
    }

    /**
     * Test component creates default block when content is empty
     */
    public function test_component_creates_default_block_when_empty(): void
    {
        $content = Content::factory()->create([
            'title' => 'Empty Page',
            'content_type' => 'page',
            'content_body' => null,
        ]);

        $component = Livewire::test(VisualEditorComponent::class, ['contentId' => $content->id]);

        $component->assertSet('blocks', function ($blocks) {
            return count($blocks) === 1 && $blocks[0]['type'] === 'text';
        });
    }

    /**
     * Test block reordering functionality
     */
    public function test_blocks_can_be_reordered(): void
    {
        $content = Content::factory()->create([
            'title' => 'Test Page',
            'content_type' => 'page',
            'content_body' => '
                <p data-mw-block-type="text" data-mw-block-id="block_1">First block</p>
                <p data-mw-block-type="text" data-mw-block-id="block_2">Second block</p>
            ',
        ]);

        $component = Livewire::test(VisualEditorComponent::class, ['contentId' => $content->id]);

        // Reorder blocks
        $component->call('handleBlockReordered', ['block_2', 'block_1']);

        // Verify blocks are reordered
        $component->assertSet('blocks', function ($blocks) {
            return $blocks[0]['id'] === 'block_2' && $blocks[1]['id'] === 'block_1';
        });
    }

    /**
     * Test adding new blocks
     */
    public function test_new_blocks_can_be_added(): void
    {
        $content = Content::factory()->create([
            'title' => 'Test Page',
            'content_type' => 'page',
            'content_body' => '',
        ]);

        $component = Livewire::test(VisualEditorComponent::class, ['contentId' => $content->id]);

        // Add a text block
        $component->call('addBlock', 'text');

        // Verify block was added
        $component->assertSet('blocks', function ($blocks) {
            return count($blocks) >= 1;
        });
    }

    /**
     * Test deleting blocks
     */
    public function test_blocks_can_be_deleted(): void
    {
        $content = Content::factory()->create([
            'title' => 'Test Page',
            'content_type' => 'page',
            'content_body' => '<p data-mw-block-type="text" data-mw-block-id="block_1">Test content</p>',
        ]);

        $component = Livewire::test(VisualEditorComponent::class, ['contentId' => $content->id]);

        // Delete the block
        $component->call('handleBlockDeleted', 'block_1');

        // Verify block was removed
        $component->assertSet('blocks', function ($blocks) {
            foreach ($blocks as $block) {
                if ($block['id'] === 'block_1') {
                    return false;
                }
            }
            return true;
        });
    }

    /**
     * Test duplicating blocks
     */
    public function test_blocks_can_be_duplicated(): void
    {
        $content = Content::factory()->create([
            'title' => 'Test Page',
            'content_type' => 'page',
            'content_body' => '<p data-mw-block-type="text" data-mw-block-id="block_1">Test content</p>',
        ]);

        $component = Livewire::test(VisualEditorComponent::class, ['contentId' => $content->id]);

        $initialCount = count($component->get('blocks'));

        // Duplicate the block
        $component->call('handleBlockDuplicated', 'block_1');

        // Verify a new block was added
        $component->assertSet('blocks', function ($blocks) use ($initialCount) {
            return count($blocks) === $initialCount + 1;
        });
    }

    /**
     * Test block selection
     */
    public function test_blocks_can_be_selected(): void
    {
        $content = Content::factory()->create([
            'title' => 'Test Page',
            'content_type' => 'page',
            'content_body' => '<p data-mw-block-type="text" data-mw-block-id="block_1">Test content</p>',
        ]);

        $component = Livewire::test(VisualEditorComponent::class, ['contentId' => $content->id]);

        // Select the block
        $component->call('handleBlockSelected', 'block_1');

        // Verify block is selected
        $component->assertSet('selectedBlockId', 'block_1');
    }

    /**
     * Test moving blocks up
     */
    public function test_blocks_can_be_moved_up(): void
    {
        $content = Content::factory()->create([
            'title' => 'Test Page',
            'content_type' => 'page',
            'content_body' => '
                <p data-mw-block-type="text" data-mw-block-id="block_1">First</p>
                <p data-mw-block-type="text" data-mw-block-id="block_2">Second</p>
            ',
        ]);

        $component = Livewire::test(VisualEditorComponent::class, ['contentId' => $content->id]);

        // Move second block up
        $component->call('moveBlockUp', 'block_2');

        // Verify order changed
        $component->assertSet('blocks', function ($blocks) {
            return $blocks[0]['id'] === 'block_2';
        });
    }

    /**
     * Test moving blocks down
     */
    public function test_blocks_can_be_moved_down(): void
    {
        $content = Content::factory()->create([
            'title' => 'Test Page',
            'content_type' => 'page',
            'content_body' => '
                <p data-mw-block-type="text" data-mw-block-id="block_1">First</p>
                <p data-mw-block-type="text" data-mw-block-id="block_2">Second</p>
            ',
        ]);

        $component = Livewire::test(VisualEditorComponent::class, ['contentId' => $content->id]);

        // Move first block down
        $component->call('moveBlockDown', 'block_1');

        // Verify order changed
        $component->assertSet('blocks', function ($blocks) {
            return $blocks[0]['id'] === 'block_2';
        });
    }

    /**
     * Test updating block settings
     */
    public function test_block_settings_can_be_updated(): void
    {
        $content = Content::factory()->create([
            'title' => 'Test Page',
            'content_type' => 'page',
            'content_body' => '<p data-mw-block-type="text" data-mw-block-id="block_1">Test content</p>',
        ]);

        $component = Livewire::test(VisualEditorComponent::class, ['contentId' => $content->id]);

        // Update settings
        $component->call('updateBlockSettings', 'block_1', ['align' => 'center']);

        // Verify settings were updated
        $component->assertSet('blocks', function ($blocks) {
            foreach ($blocks as $block) {
                if ($block['id'] === 'block_1') {
                    return $block['settings']['align'] === 'center';
                }
            }
            return false;
        });
    }

    /**
     * Test saving content to database
     */
    public function test_content_is_saved_to_database(): void
    {
        $content = Content::factory()->create([
            'title' => 'Test Page',
            'content_type' => 'page',
            'content_body' => '<p data-mw-block-type="text" data-mw-block-id="block_1">Original content</p>',
        ]);

        $component = Livewire::test(VisualEditorComponent::class, ['contentId' => $content->id]);

        // Add a new block
        $component->call('addBlock', 'heading');

        // Verify content was saved in database
        $content->refresh();
        $this->assertStringContainsString('heading', $content->content_body);
    }

    /**
     * Test block library toggle
     */
    public function test_block_library_can_be_toggled(): void
    {
        $component = Livewire::test(VisualEditorComponent::class);

        // Toggle library
        $component->call('toggleBlockLibrary');

        // Verify library is shown
        $component->assertSet('showBlockLibrary', true);

        // Toggle again
        $component->call('toggleBlockLibrary');

        // Verify library is hidden
        $component->assertSet('showBlockLibrary', false);
    }

    /**
     * Test available block types are loaded
     */
    public function test_available_block_types_are_loaded(): void
    {
        $component = Livewire::test(VisualEditorComponent::class);

        // Verify block types are loaded
        $component->assertSet('availableBlockTypes', function ($types) {
            return count($types) > 0 && isset($types[0]['type']);
        });
    }

    /**
     * Test drag state management
     */
    public function test_drag_state_is_managed(): void
    {
        $component = Livewire::test(VisualEditorComponent::class);

        // Start drag
        $component->call('handleDragStarted', 'block_1');
        $component->assertSet('isDragging', true);

        // End drag
        $component->call('handleDragEnded');
        $component->assertSet('isDragging', false);
    }

    /**
     * Test content is parsed correctly from HTML
     */
    public function test_content_parses_html_blocks_correctly(): void
    {
        $html = '
            <h2 data-mw-block-type="heading" data-mw-block-id="heading_1">Title</h2>
            <p data-mw-block-type="text" data-mw-block-id="text_1">Paragraph 1</p>
            <p data-mw-block-type="text" data-mw-block-id="text_2">Paragraph 2</p>
        ';

        $content = Content::factory()->create([
            'title' => 'Test Page',
            'content_type' => 'page',
            'content_body' => $html,
        ]);

        $component = Livewire::test(VisualEditorComponent::class, ['contentId' => $content->id]);

        // Verify blocks were parsed
        $component->assertSet('blocks', function ($blocks) {
            return count($blocks) === 3;
        });
    }

    /**
     * Test unauthorized users cannot access
     */
    public function test_unauthorized_users_cannot_access(): void
    {
        // Logout
        auth()->logout();

        // Test that component rejects unauthorized access
        $this->expectException(\Illuminate\Auth\Access\AuthorizationException::class);
        
        Livewire::actingAs($this->user)->test(VisualEditorComponent::class);
    }
}
