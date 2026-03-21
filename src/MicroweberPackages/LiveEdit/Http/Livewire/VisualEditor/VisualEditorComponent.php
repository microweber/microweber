<?php

namespace MicroweberPackages\LiveEdit\Http\Livewire\VisualEditor;

use Livewire\Attributes\On;
use MicroweberPackages\Admin\Http\Livewire\AdminComponent;
use Modules\Content\Models\Content;
use Modules\Content\Services\ContentManager;

/**
 * Visual Drag-and-Drop Editor Component
 *
 * Provides a visual interface for editing content blocks with drag-and-drop
 * reordering, component insertion, and live preview capabilities.
 *
 * @package MicroweberPackages\LiveEdit\Http\Livewire\VisualEditor
 */
class VisualEditorComponent extends AdminComponent
{
    /**
     * The content ID being edited
     */
    public ?int $contentId = null;

    /**
     * Array of content blocks
     */
    public array $blocks = [];

    /**
     * Currently selected block ID
     */
    public ?string $selectedBlockId = null;

    /**
     * Available block types for insertion
     */
    public array $availableBlockTypes = [];

    /**
     * Editor state
     */
    public bool $isDragging = false;
    public bool $showBlockLibrary = false;
    public ?string $dragSourceId = null;
    public ?string $dragTargetId = null;

    /**
     * Component listeners
     */
    protected $listeners = [
        'blockReordered' => 'handleBlockReordered',
        'blockSelected' => 'handleBlockSelected',
        'blockContentUpdated' => 'handleBlockContentUpdated',
        'blockDeleted' => 'handleBlockDeleted',
        'blockDuplicated' => 'handleBlockDuplicated',
        'dragStarted' => 'handleDragStarted',
        'dragEnded' => 'handleDragEnded',
        'refreshBlocks' => 'loadBlocks',
    ];

    /**
     * Mount the component
     */
    public function mount(?int $contentId = null): void
    {
        $this->contentId = $contentId;
        $this->loadBlocks();
        $this->loadAvailableBlockTypes();
    }

    /**
     * Load content blocks from the database
     */
    public function loadBlocks(): void
    {
        if (!$this->contentId) {
            $this->blocks = [];
            return;
        }

        $contentManager = app(ContentManager::class);
        $content = $contentManager->get(['id' => $this->contentId, 'single' => true]);

        if (!$content) {
            $this->blocks = [];
            return;
        }

        // Parse content body into blocks
        $this->blocks = $this->parseContentIntoBlocks($content);
    }

    /**
     * Parse content into visual blocks
     */
    protected function parseContentIntoBlocks($content): array
    {
        $blocks = [];
        $position = 0;

        // Parse content_body if available
        if (!empty($content['content_body'])) {
            $blocks = $this->extractBlocksFromHtml($content['content_body'], $position);
        }

        // If no blocks found, create a default text block
        if (empty($blocks)) {
            $blocks[] = [
                'id' => 'block_' . uniqid(),
                'type' => 'text',
                'title' => 'Text Block',
                'content' => $content['content_body'] ?? '',
                'position' => 0,
                'settings' => [],
            ];
        }

        return $blocks;
    }

    /**
     * Extract blocks from HTML content
     */
    protected function extractBlocksFromHtml(string $html, int &$position): array
    {
        $blocks = [];

        // Use DOMDocument to parse HTML
        $dom = new \DOMDocument();
        libxml_use_internal_errors(true);
        $dom->loadHTML('<?xml encoding="UTF-8">' . $html);
        libxml_clear_errors();

        $xpath = new \DOMXPath($dom);
        $elements = $xpath->query('//*[@data-mw-block-type]');

        if ($elements->length === 0) {
            // No existing blocks, treat entire content as one block
            $blocks[] = [
                'id' => 'block_' . uniqid(),
                'type' => 'text',
                'title' => 'Text Block',
                'content' => $html,
                'position' => $position++,
                'settings' => [],
            ];
        } else {
            foreach ($elements as $element) {
                $blockType = $element->getAttribute('data-mw-block-type');
                $blockId = $element->getAttribute('data-mw-block-id') ?: 'block_' . uniqid();

                $blocks[] = [
                    'id' => $blockId,
                    'type' => $blockType,
                    'title' => $this->getBlockTypeTitle($blockType),
                    'content' => $this->domNodeToString($element),
                    'position' => $position++,
                    'settings' => $this->extractBlockSettings($element),
                ];
            }
        }

        return $blocks;
    }

    /**
     * Convert DOMNode to string
     */
    protected function domNodeToString(\DOMNode $node): string
    {
        return $node->ownerDocument->saveHTML($node);
    }

    /**
     * Extract block settings from element
     */
    protected function extractBlockSettings(\DOMElement $element): array
    {
        $settings = [];

        // Extract data attributes as settings
        foreach ($element->attributes as $attr) {
            if (strpos($attr->name, 'data-mw-setting-') === 0) {
                $key = substr($attr->name, strlen('data-mw-setting-'));
                $settings[$key] = $attr->value;
            }
        }

        return $settings;
    }

    /**
     * Load available block types
     */
    protected function loadAvailableBlockTypes(): void
    {
        $this->availableBlockTypes = [
            [
                'type' => 'text',
                'title' => 'Text Block',
                'icon' => 'heroicon-o-document-text',
                'description' => 'Add rich text content',
            ],
            [
                'type' => 'image',
                'title' => 'Image Block',
                'icon' => 'heroicon-o-photo',
                'description' => 'Add an image',
            ],
            [
                'type' => 'video',
                'title' => 'Video Block',
                'icon' => 'heroicon-o-video-camera',
                'description' => 'Add a video',
            ],
            [
                'type' => 'heading',
                'title' => 'Heading Block',
                'icon' => 'heroicon-o-heading',
                'description' => 'Add a heading',
            ],
            [
                'type' => 'button',
                'title' => 'Button Block',
                'icon' => 'heroicon-o-cursor-arrow-rays',
                'description' => 'Add a button',
            ],
            [
                'type' => 'spacer',
                'title' => 'Spacer Block',
                'icon' => 'heroicon-o-arrows-up-down',
                'description' => 'Add vertical spacing',
            ],
            [
                'type' => 'divider',
                'title' => 'Divider Block',
                'icon' => 'heroicon-o-minus',
                'description' => 'Add a horizontal divider',
            ],
            [
                'type' => 'columns',
                'title' => 'Columns Block',
                'icon' => 'heroicon-o-table-cells',
                'description' => 'Add multi-column layout',
            ],
            [
                'type' => 'embed',
                'title' => 'Embed Block',
                'icon' => 'heroicon-o-code-bracket',
                'description' => 'Add embedded content',
            ],
            [
                'type' => 'gallery',
                'title' => 'Gallery Block',
                'icon' => 'heroicon-o-squares-2x2',
                'description' => 'Add an image gallery',
            ],
        ];
    }

    /**
     * Get human-readable title for block type
     */
    protected function getBlockTypeTitle(string $type): string
    {
        $titles = [
            'text' => 'Text Block',
            'image' => 'Image Block',
            'video' => 'Video Block',
            'heading' => 'Heading Block',
            'button' => 'Button Block',
            'spacer' => 'Spacer Block',
            'divider' => 'Divider Block',
            'columns' => 'Columns Block',
            'embed' => 'Embed Block',
            'gallery' => 'Gallery Block',
        ];

        return $titles[$type] ?? 'Unknown Block';
    }

    /**
     * Handle block reordering
     */
    public function handleBlockReordered(array $orderedIds): void
    {
        $reorderedBlocks = [];
        $position = 0;

        foreach ($orderedIds as $id) {
            foreach ($this->blocks as $block) {
                if ($block['id'] === $id) {
                    $block['position'] = $position++;
                    $reorderedBlocks[] = $block;
                    break;
                }
            }
        }

        $this->blocks = $reorderedBlocks;
        $this->saveBlocks();

        $this->dispatch('blocksReordered', [
            'message' => 'Blocks reordered successfully',
        ]);
    }

    /**
     * Handle block selection
     */
    public function handleBlockSelected(string $blockId): void
    {
        $this->selectedBlockId = $blockId;
        $this->dispatch('blockSelectionChanged', ['blockId' => $blockId]);
    }

    /**
     * Handle block content update
     */
    public function handleBlockContentUpdated(string $blockId, array $data): void
    {
        foreach ($this->blocks as $key => $block) {
            if ($block['id'] === $blockId) {
                $this->blocks[$key]['content'] = $data['content'] ?? $block['content'];
                $this->blocks[$key]['settings'] = array_merge(
                    $block['settings'],
                    $data['settings'] ?? []
                );
                break;
            }
        }

        $this->saveBlocks();
    }

    /**
     * Handle block deletion
     */
    public function handleBlockDeleted(string $blockId): void
    {
        $this->blocks = array_filter($this->blocks, function ($block) use ($blockId) {
            return $block['id'] !== $blockId;
        });

        $this->blocks = array_values($this->blocks);

        if ($this->selectedBlockId === $blockId) {
            $this->selectedBlockId = null;
        }

        $this->saveBlocks();

        $this->dispatch('blockDeleted', [
            'blockId' => $blockId,
            'message' => 'Block deleted successfully',
        ]);
    }

    /**
     * Handle block duplication
     */
    public function handleBlockDuplicated(string $blockId): void
    {
        $blockToDuplicate = null;
        $blockIndex = null;

        foreach ($this->blocks as $index => $block) {
            if ($block['id'] === $blockId) {
                $blockToDuplicate = $block;
                $blockIndex = $index;
                break;
            }
        }

        if ($blockToDuplicate) {
            $newBlock = $blockToDuplicate;
            $newBlock['id'] = 'block_' . uniqid();
            $newBlock['position'] = $blockToDuplicate['position'] + 1;

            // Insert after the original block
            array_splice($this->blocks, $blockIndex + 1, 0, [$newBlock]);

            // Reassign positions
            foreach ($this->blocks as $key => $block) {
                $this->blocks[$key]['position'] = $key;
            }

            $this->saveBlocks();

            $this->dispatch('blockDuplicated', [
                'originalBlockId' => $blockId,
                'newBlockId' => $newBlock['id'],
                'message' => 'Block duplicated successfully',
            ]);
        }
    }

    /**
     * Add new block
     */
    public function addBlock(string $type, ?string $afterBlockId = null): void
    {
        $newBlock = [
            'id' => 'block_' . uniqid(),
            'type' => $type,
            'title' => $this->getBlockTypeTitle($type),
            'content' => $this->getDefaultBlockContent($type),
            'position' => count($this->blocks),
            'settings' => $this->getDefaultBlockSettings($type),
        ];

        if ($afterBlockId) {
            $insertIndex = 0;
            foreach ($this->blocks as $index => $block) {
                if ($block['id'] === $afterBlockId) {
                    $insertIndex = $index + 1;
                    break;
                }
            }
            array_splice($this->blocks, $insertIndex, 0, [$newBlock]);

            // Reassign positions
            foreach ($this->blocks as $key => $block) {
                $this->blocks[$key]['position'] = $key;
            }
        } else {
            $this->blocks[] = $newBlock;
        }

        $this->saveBlocks();
        $this->selectedBlockId = $newBlock['id'];

        $this->dispatch('blockAdded', [
            'blockId' => $newBlock['id'],
            'type' => $type,
            'message' => 'New block added',
        ]);
    }

    /**
     * Get default content for block type
     */
    protected function getDefaultBlockContent(string $type): string
    {
        $defaults = [
            'text' => '<p data-mw-block-type="text" data-mw-block-id="">Enter your text here...</p>',
            'image' => '<div data-mw-block-type="image" data-mw-block-id=""><img src="" alt="Image" /></div>',
            'video' => '<div data-mw-block-type="video" data-mw-block-id=""><div class="mw-video-placeholder">Video placeholder</div></div>',
            'heading' => '<h2 data-mw-block-type="heading" data-mw-block-id="">Heading</h2>',
            'button' => '<div data-mw-block-type="button" data-mw-block-id=""><a href="#" class="btn btn-primary">Click me</a></div>',
            'spacer' => '<div data-mw-block-type="spacer" data-mw-block-id="" style="height: 50px;"></div>',
            'divider' => '<hr data-mw-block-type="divider" data-mw-block-id="" />',
            'columns' => '<div data-mw-block-type="columns" data-mw-block-id=""><div class="row"><div class="col">Column 1</div><div class="col">Column 2</div></div></div>',
            'embed' => '<div data-mw-block-type="embed" data-mw-block-id=""><!-- Embedded content --></div>',
            'gallery' => '<div data-mw-block-type="gallery" data-mw-block-id=""><div class="gallery-placeholder">Gallery placeholder</div></div>',
        ];

        $content = $defaults[$type] ?? '<div data-mw-block-type="' . $type . '" data-mw-block-id=""></div>';

        // Inject unique ID
        return preg_replace('/data-mw-block-id=""/', 'data-mw-block-id="block_' . uniqid() . '"', $content);
    }

    /**
     * Get default settings for block type
     */
    protected function getDefaultBlockSettings(string $type): array
    {
        $defaults = [
            'text' => ['align' => 'left', 'color' => ''],
            'image' => ['align' => 'center', 'width' => '100%'],
            'video' => ['autoplay' => false, 'controls' => true],
            'heading' => ['level' => 'h2', 'align' => 'left'],
            'button' => ['style' => 'primary', 'align' => 'left'],
            'spacer' => ['height' => '50px'],
            'divider' => ['style' => 'solid'],
            'columns' => ['columns' => 2],
            'embed' => ['responsive' => true],
            'gallery' => ['columns' => 3, 'lightbox' => true],
        ];

        return $defaults[$type] ?? [];
    }

    /**
     * Handle drag started
     */
    public function handleDragStarted(string $blockId): void
    {
        $this->isDragging = true;
        $this->dragSourceId = $blockId;
    }

    /**
     * Handle drag ended
     */
    public function handleDragEnded(): void
    {
        $this->isDragging = false;
        $this->dragSourceId = null;
        $this->dragTargetId = null;
    }

    /**
     * Save blocks to database
     */
    protected function saveBlocks(): void
    {
        if (!$this->contentId) {
            return;
        }

        $contentBody = $this->compileBlocksToHtml();

        try {
            $contentManager = app(ContentManager::class);
            $contentManager->save([
                'id' => $this->contentId,
                'content_body' => $contentBody,
            ]);

            $this->dispatch('blocksSaved', [
                'message' => 'Content saved successfully',
            ]);
        } catch (\Exception $e) {
            $this->dispatch('saveError', [
                'message' => 'Failed to save content: ' . $e->getMessage(),
            ]);
        }
    }

    /**
     * Compile blocks to HTML
     */
    protected function compileBlocksToHtml(): string
    {
        $html = '';

        foreach ($this->blocks as $block) {
            $html .= $block['content'];
        }

        return $html;
    }

    /**
     * Toggle block library visibility
     */
    public function toggleBlockLibrary(): void
    {
        $this->showBlockLibrary = !$this->showBlockLibrary;
    }

    /**
     * Move block up
     */
    public function moveBlockUp(string $blockId): void
    {
        $index = null;
        foreach ($this->blocks as $i => $block) {
            if ($block['id'] === $blockId) {
                $index = $i;
                break;
            }
        }

        if ($index !== null && $index > 0) {
            $temp = $this->blocks[$index];
            $this->blocks[$index] = $this->blocks[$index - 1];
            $this->blocks[$index - 1] = $temp;

            // Update positions
            foreach ($this->blocks as $key => $block) {
                $this->blocks[$key]['position'] = $key;
            }

            $this->saveBlocks();
        }
    }

    /**
     * Move block down
     */
    public function moveBlockDown(string $blockId): void
    {
        $index = null;
        foreach ($this->blocks as $i => $block) {
            if ($block['id'] === $blockId) {
                $index = $i;
                break;
            }
        }

        if ($index !== null && $index < count($this->blocks) - 1) {
            $temp = $this->blocks[$index];
            $this->blocks[$index] = $this->blocks[$index + 1];
            $this->blocks[$index + 1] = $temp;

            // Update positions
            foreach ($this->blocks as $key => $block) {
                $this->blocks[$key]['position'] = $key;
            }

            $this->saveBlocks();
        }
    }

    /**
     * Update block settings
     */
    public function updateBlockSettings(string $blockId, array $settings): void
    {
        foreach ($this->blocks as $key => $block) {
            if ($block['id'] === $blockId) {
                $this->blocks[$key]['settings'] = array_merge(
                    $this->blocks[$key]['settings'],
                    $settings
                );
                break;
            }
        }

        $this->saveBlocks();
    }

    /**
     * Render the component
     */
    public function render()
    {
        return view('microweber-live-edit::visual-editor.visual-editor-component');
    }
}
