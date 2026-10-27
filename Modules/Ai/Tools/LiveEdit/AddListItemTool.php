<?php

declare(strict_types=1);

namespace Modules\Ai\Tools\LiveEdit;

use MicroweberPackages\AiTools\Base\BaseTool;
use Modules\Accordion\Models\Accordion;
use Modules\Faq\Models\Faq;
use Modules\Tabs\Models\Tab;
use Modules\Teamcard\Models\Teamcard;
use Modules\Testimonials\Models\Testimonial;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

/**
 * Add one item to a LIST module — faq, tabs, accordion, teamcard or testimonials.
 *
 * These modules do NOT have flat options you set with set_module_option; their
 * content is a LIST of rows (a FAQ Q&A, a tab panel, an accordion row, a team
 * member, a testimonial) stored in the module's own table, keyed to the module
 * instance by rel_type='module' + rel_id=<module id>. This is a real server-side
 * write (like add_menu_item): insert the module first, then call this once per
 * item, passing the module_id (the module instance's DOM id).
 *
 * Field meaning per module_type (pass what applies; title and/or text are enough):
 *   faq          → title=question, text=answer
 *   tabs         → title=tab label, text=panel HTML/content
 *   accordion    → title=row header, text=row body
 *   teamcard     → title=name, subtitle=role, text=bio, image_url=photo, link=website
 *   testimonials → title=name, subtitle=role, text=quote, image_url=avatar,
 *                  company=company, link=website
 */
class AddListItemTool extends BaseTool
{
    protected string $domain = 'liveedit';

    /** module_type => [model class, column map keyed by generic field]. */
    private const MAP = [
        'faq' => [
            'model' => Faq::class,
            'fields' => ['title' => 'question', 'text' => 'answer'],
        ],
        'tabs' => [
            'model' => Tab::class,
            'fields' => ['title' => 'title', 'text' => 'content'],
        ],
        'accordion' => [
            'model' => Accordion::class,
            'fields' => ['title' => 'title', 'text' => 'content'],
        ],
        'teamcard' => [
            'model' => Teamcard::class,
            'fields' => ['title' => 'name', 'subtitle' => 'role', 'text' => 'bio', 'image_url' => 'file', 'link' => 'website'],
        ],
        'testimonials' => [
            'model' => Testimonial::class,
            'fields' => ['title' => 'name', 'subtitle' => 'client_role', 'text' => 'content', 'image_url' => 'client_image', 'company' => 'client_company', 'link' => 'client_website'],
        ],
    ];

    /** Accept the common aliases the agent may use for the module type. */
    private const TYPE_ALIASES = [
        'faqs' => 'faq',
        'tab' => 'tabs',
        'accordions' => 'accordion',
        'team' => 'teamcard',
        'teamcards' => 'teamcard',
        'testimonial' => 'testimonials',
    ];

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'add_list_item',
            'Add ONE item to a list module (faq, tabs, accordion, teamcard, '
            . 'testimonials) — these store a LIST of rows, not flat options, so '
            . 'set_module_option does NOT work on them. Insert the module first, '
            . 'then call this once per item with module_id (the module\'s DOM id). '
            . 'Fields: title + text cover faq (question/answer), tabs & accordion '
            . '(label/content); for teamcard/testimonials also use subtitle (role), '
            . 'image_url, company and link.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'module_type',
                type: PropertyType::STRING,
                description: 'One of: faq, tabs, accordion, teamcard, testimonials.',
                required: true,
            ),
            new ToolProperty(
                name: 'module_id',
                type: PropertyType::STRING,
                description: 'The module instance id (its DOM id = rel_id). Use the id '
                    . 'of the module you inserted; read it from get_page_context if unsure.',
                required: true,
            ),
            new ToolProperty(
                name: 'title',
                type: PropertyType::STRING,
                description: 'The item heading: faq question, tab/accordion label, or '
                    . 'the person\'s name (teamcard/testimonials).',
                required: false,
            ),
            new ToolProperty(
                name: 'text',
                type: PropertyType::STRING,
                description: 'The item body: faq answer, tab/accordion content, a bio '
                    . '(teamcard) or the quote (testimonials).',
                required: false,
            ),
            new ToolProperty(
                name: 'subtitle',
                type: PropertyType::STRING,
                description: 'The role/title line for teamcard and testimonials.',
                required: false,
            ),
            new ToolProperty(
                name: 'image_url',
                type: PropertyType::STRING,
                description: 'Photo/avatar URL for teamcard (file) or testimonials (avatar).',
                required: false,
            ),
            new ToolProperty(
                name: 'company',
                type: PropertyType::STRING,
                description: 'Company name — testimonials only.',
                required: false,
            ),
            new ToolProperty(
                name: 'link',
                type: PropertyType::STRING,
                description: 'Website URL — teamcard or testimonials.',
                required: false,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        $type = strtolower(trim((string) ($args['module_type'] ?? '')));
        $type = self::TYPE_ALIASES[$type] ?? $type;
        $moduleId = trim((string) ($args['module_id'] ?? ''));

        if (!isset(self::MAP[$type])) {
            return $this->handleError(
                'Unknown list module "' . $type . '". Supported: ' . implode(', ', array_keys(self::MAP)) . '.'
            );
        }
        if ($moduleId === '') {
            return $this->handleError('Provide module_id — the id of the ' . $type . ' module instance (its DOM id).');
        }

        // Collect the generic fields the caller supplied and map them to columns.
        $generic = [
            'title' => trim((string) ($args['title'] ?? '')),
            'text' => (string) ($args['text'] ?? ''),
            'subtitle' => trim((string) ($args['subtitle'] ?? '')),
            'image_url' => trim((string) ($args['image_url'] ?? '')),
            'company' => trim((string) ($args['company'] ?? '')),
            'link' => trim((string) ($args['link'] ?? '')),
        ];
        if ($generic['title'] === '' && trim($generic['text']) === '') {
            return $this->handleError('Provide at least a title or text for the ' . $type . ' item.');
        }

        $spec = self::MAP[$type];
        /** @var class-string<\Illuminate\Database\Eloquent\Model> $model */
        $model = $spec['model'];

        try {
            $row = [
                'rel_type' => 'module',
                'rel_id' => $moduleId,
                'is_active' => 1,
            ];
            foreach ($spec['fields'] as $genericKey => $column) {
                $value = $generic[$genericKey] ?? '';
                if ($value !== '' && $value !== null) {
                    $row[$column] = $value;
                }
            }

            // Append at the end of this module's list.
            $maxPos = (int) $model::where('rel_type', 'module')->where('rel_id', $moduleId)->max('position');
            $row['position'] = $maxPos + 1;

            $item = $model::create($row);

            $label = $generic['title'] !== '' ? $generic['title'] : mb_substr(trim(strip_tags($generic['text'])), 0, 40);

            return "OK — added \"{$label}\" to the {$type} module {$moduleId} (item #{$item->getKey()}). "
                . 'The canvas will reload the module to show it; the user saves the page to keep it.';
        } catch (\Throwable $e) {
            return $this->handleError('Could not add the ' . $type . ' item: ' . $e->getMessage());
        }
    }
}
