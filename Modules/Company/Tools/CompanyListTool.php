<?php

declare(strict_types=1);

namespace Modules\Company\Tools;

use MicroweberPackages\AiTools\Base\BaseTool;
use Modules\Company\Models\Company;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

/**
 * MCP tool: read the site's companies.
 *
 * Exposes the Company module over MCP — lists companies (name, contact, VAT,
 * location, website), optionally filtered by a search term. Read-only.
 */
class CompanyListTool extends BaseTool
{
    protected string $domain = 'company';

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'company_list',
            'List the site companies (name, email, phone, VAT number, city, country, '
            . 'website). Optionally filter by a search term.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'search_term',
                type: PropertyType::STRING,
                description: 'Optional term to match against the company name, email or VAT number.',
                required: false,
            ),
            new ToolProperty(
                name: 'limit',
                type: PropertyType::INTEGER,
                description: 'Maximum number of companies to return (1-100). Default 30.',
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

            $rows = Company::query()
                ->when($term !== '', function ($q) use ($term) {
                    $q->where(function ($w) use ($term) {
                        $w->where('name', 'like', "%{$term}%")
                            ->orWhere('email', 'like', "%{$term}%")
                            ->orWhere('vat_number', 'like', "%{$term}%");
                    });
                })
                ->orderBy('name')
                ->limit($limit)
                ->get(['id', 'name', 'email', 'phone', 'vat_number', 'city', 'country', 'website'])
                ->map(function ($c) {
                    return [
                        'id' => $c->id,
                        'name' => $c->name,
                        'email' => $c->email,
                        'phone' => $c->phone,
                        'vat_number' => $c->vat_number,
                        'location' => trim(($c->city ?: '') . ($c->country ? ', ' . $c->country : '')),
                        'website' => $c->website,
                    ];
                })->all();

            return json_encode([
                'count' => count($rows),
                'companies' => $rows,
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $e) {
            return $this->handleError('Failed to read companies: ' . $e->getMessage());
        }
    }
}
