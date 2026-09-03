<?php

declare(strict_types=1);

namespace Modules\MailTemplate\Tools;

use MicroweberPackages\AiTools\Base\BaseTool;
use Modules\MailTemplate\Models\MailTemplate;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

/**
 * MCP tool: read the site's email/mail templates.
 *
 * Exposes the MailTemplate module over MCP — lists mail templates (name, type,
 * subject, from, active), optionally filtered by a search term. Read-only.
 */
class MailTemplateListTool extends BaseTool
{
    protected string $domain = 'mailtemplate';

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'mail_template_list',
            'List the site email/mail templates (name, type, subject, from address, '
            . 'active state). Optionally filter by a search term.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'search_term',
                type: PropertyType::STRING,
                description: 'Optional term to match against the template name, type or subject.',
                required: false,
            ),
            new ToolProperty(
                name: 'limit',
                type: PropertyType::INTEGER,
                description: 'Maximum number of templates to return (1-100). Default 30.',
                required: false,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        try {
            $term = trim((string) ($args['search_term'] ?? ''));
            $limit = (int) ($args['limit'] ?? 30);
            if ($limit < 1 || $limit > 100) {
                $limit = 30;
            }

            $rows = MailTemplate::query()
                ->when($term !== '', function ($q) use ($term) {
                    $q->where(function ($w) use ($term) {
                        $w->where('name', 'like', "%{$term}%")
                            ->orWhere('type', 'like', "%{$term}%")
                            ->orWhere('subject', 'like', "%{$term}%");
                    });
                })
                ->orderBy('name')
                ->limit($limit)
                ->get(['id', 'name', 'type', 'subject', 'from_name', 'from_email', 'is_active'])
                ->map(function ($t) {
                    return [
                        'id' => $t->id,
                        'name' => $t->name,
                        'type' => $t->type,
                        'subject' => $t->subject,
                        'from' => trim(($t->from_name ?: '') . ' <' . ($t->from_email ?: '') . '>'),
                        'active' => (int) $t->is_active === 1,
                    ];
                })->all();

            return json_encode([
                'count' => count($rows),
                'templates' => $rows,
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $e) {
            return $this->handleError('Failed to read mail templates: ' . $e->getMessage());
        }
    }
}
