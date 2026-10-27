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
 * Add one item to a LIST module — faq, tabs, accordion, teamcard, testimonials
 * or skills.
 *
 * These modules do NOT have flat options you set with set_module_option; their
 * content is a LIST of rows (a FAQ Q&A, a tab panel, an accordion row, a team
 * member, a testimonial, a skill bar). Most store rows in the module's own table,
 * keyed to the module instance by rel_type='module' + rel_id=<module id>; skills
 * instead stores its rows as a JSON array in the module's "skills" option. Either
 * way this is a real server-side write (like add_menu_item): insert the module
 * first, then call this once per item, passing the module_id (the module
 * instance's DOM id).
 *
 * Field meaning per module_type (pass what applies; title and/or text are enough):
 *   faq          → title=question, text=answer
 *   tabs         → title=tab label, text=panel HTML/content
 *   accordion    → title=row header, text=row body
 *   teamcard     → title=name, subtitle=role, text=bio, image_url=photo, link=website
 *   testimonials → title=name, subtitle=role, text=quote, image_url=avatar,
 *                  company=company, link=website
 *   skills       → title=skill name, percent=0-100 bar value, subtitle=bar style
 *                  (primary|success|info|warning|danger)
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
        'skill' => 'skills',
    ];

    /** Valid Bootstrap progress-bar styles for a skills item. */
    private const SKILL_STYLES = ['primary', 'success', 'info', 'warning', 'danger'];

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'add_list_item',
            'Add ONE item to a list module (faq, tabs, accordion, teamcard, '
            . 'testimonials, skills) — these store a LIST of rows, not flat options, '
            . 'so set_module_option does NOT work on them. Insert the module first, '
            . 'then call this once per item with module_id (the module\'s DOM id). '
            . 'Fields: title + text cover faq (question/answer), tabs & accordion '
            . '(label/content); for teamcard/testimonials also use subtitle (role), '
            . 'image_url, company and link; for skills use title (skill name) and '
            . 'percent (0-100).'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'module_type',
                type: PropertyType::STRING,
                description: 'One of: faq, tabs, accordion, teamcard, testimonials, skills.',
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
                description: 'The role/title line for teamcard and testimonials; for '
                    . 'skills, the bar style (primary, success, info, warning or danger).',
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
            new ToolProperty(
                name: 'percent',
                type: PropertyType::STRING,
                description: 'Skills only: the bar value, 0-100 (defaults to 100).',
                required: false,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        $type = strtolower(trim((string) ($args['module_type'] ?? '')));
        $type = self::TYPE_ALIASES[$type] ?? $type;
        $moduleId = trim((string) ($args['module_id'] ?? ''));

        // skills stores its list as a JSON array in the "skills" option, not a
        // table — handle it on its own path.
        if ($type === 'skills') {
            return $this->addSkill($moduleId, $args);
        }

        if (!isset(self::MAP[$type])) {
            return $this->handleError(
                'Unknown list module "' . $type . '". Supported: ' . implode(', ', array_keys(self::MAP)) . ', skills.'
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

    /**
     * Append one skill bar to the module's "skills" option (a JSON array of
     * {skill, percent, style}). Mirrors SkillsModuleSettings' own storage so the
     * item renders through SkillsModule::render() exactly like a hand-added one.
     */
    private function addSkill(string $moduleId, array $args): string
    {
        if ($moduleId === '') {
            return $this->handleError('Provide module_id — the id of the skills module instance (its DOM id).');
        }

        $name = trim((string) ($args['title'] ?? ''));
        if ($name === '') {
            return $this->handleError('Provide a title (the skill name) for the skills item.');
        }

        $percent = (int) round((float) ($args['percent'] ?? 100));
        $percent = max(0, min(100, $percent));

        $style = strtolower(trim((string) ($args['subtitle'] ?? '')));
        if (!in_array($style, self::SKILL_STYLES, true)) {
            $style = 'primary';
        }

        try {
            $skills = json_decode((string) get_option('skills', $moduleId), true);
            if (!is_array($skills)) {
                $skills = [];
            }
            $skills[] = ['skill' => $name, 'percent' => $percent, 'style' => $style];
            save_option('skills', json_encode(array_values($skills)), $moduleId);

            return "OK — added skill \"{$name}\" ({$percent}%) to the skills module {$moduleId} "
                . '(item #' . count($skills) . '). The canvas will reload the module to show it; '
                . 'the user saves the page to keep it.';
        } catch (\Throwable $e) {
            return $this->handleError('Could not add the skills item: ' . $e->getMessage());
        }
    }
}
