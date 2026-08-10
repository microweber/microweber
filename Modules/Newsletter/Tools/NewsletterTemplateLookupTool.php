<?php

declare(strict_types=1);

namespace Modules\Newsletter\Tools;

use MicroweberPackages\AiTools\Base\BaseTool;

use Illuminate\Support\Str;
use Modules\Newsletter\Models\NewsletterTemplate;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

class NewsletterTemplateLookupTool extends BaseTool
{
    protected string $domain = 'newsletter';

    protected array $requiredPermissions = ['view newsletters'];

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'newsletter_template_lookup',
            'Search newsletter templates and review how many campaigns use each template.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'template_id',
                type: PropertyType::INTEGER,
                description: 'Optional newsletter template ID for a single-template lookup.',
                required: false,
            ),
            new ToolProperty(
                name: 'search_term',
                type: PropertyType::STRING,
                description: 'Optional search term for newsletter template titles or content.',
                required: false,
            ),
            new ToolProperty(
                name: 'limit',
                type: PropertyType::INTEGER,
                description: 'Maximum number of templates to return (1-50). Default is 20.',
                required: false,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        $templateId = isset($args['template_id']) ? (int) $args['template_id'] : null;
        $searchTerm = trim((string) ($args['search_term'] ?? ''));
        $limit = max(1, min(50, (int) ($args['limit'] ?? 20)));

        if (! $this->authorize()) {
            return $this->handleError('You do not have permission to view newsletter templates.');
        }

        try {
            $query = NewsletterTemplate::query()->withCount('campaigns');

            if ($templateId !== null && $templateId > 0) {
                $query->where('id', $templateId);
            }

            if ($searchTerm !== '') {
                $query->where(function ($builder) use ($searchTerm): void {
                    $builder->where('title', 'like', '%' . $searchTerm . '%')
                        ->orWhere('text', 'like', '%' . $searchTerm . '%');
                });
            }

            $templates = $query
                ->orderByDesc('updated_at')
                ->limit($limit)
                ->get();

            if ($templates->isEmpty()) {
                return $this->formatAsHtmlTable(
                    [],
                    [
                        'template' => 'Template',
                        'usage' => 'Campaign usage',
                    ],
                    'No newsletter templates matched the current filters.',
                    'newsletter-template-lookup-empty'
                );
            }

            $tableRows = [];

            foreach ($templates as $template) {
                $tableRows[] = [
                    'template' => '#' . $template->id . ' ' . $template->title,
                    'preview' => Str::limit(trim(strip_tags((string) $template->text)), 80),
                    'usage' => (string) $template->campaigns_count . ' linked campaign(s)',
                    'updated' => $template->updated_at ? (string) $template->updated_at : 'Unknown',
                ];
            }

            return $this->formatAsHtmlTable(
                $tableRows,
                [
                    'template' => 'Template',
                    'preview' => 'Preview',
                    'usage' => 'Campaign usage',
                    'updated' => 'Updated at',
                ],
                '',
                'newsletter-template-lookup-results'
            );
        } catch (\Throwable $exception) {
            return $this->handleError('Error looking up newsletter templates: ' . $exception->getMessage());
        }
    }
}
