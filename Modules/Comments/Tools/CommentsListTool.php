<?php

declare(strict_types=1);

namespace Modules\Comments\Tools;

use MicroweberPackages\AiTools\Base\BaseTool;
use Modules\Comments\Models\Comment;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

/**
 * MCP tool: read recent site comments with their moderation status.
 *
 * Exposes the Comments module over MCP so an agent can review moderation —
 * recent comments, whether they are moderated/spam/new, and what content they
 * are on. Read-only.
 */
class CommentsListTool extends BaseTool
{
    protected string $domain = 'comments';

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'comments_list',
            'List recent site comments with moderation status (name, excerpt, '
            . 'spam/moderated/new flags, and the content id they are on). '
            . 'Optionally filter to pending moderation or a specific content id.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'status',
                type: PropertyType::STRING,
                description: 'Optional filter: "new" (awaiting review), "spam", '
                    . '"moderated" (approved), or "all". Default "all".',
                required: false,
            ),
            new ToolProperty(
                name: 'rel_id',
                type: PropertyType::INTEGER,
                description: 'Optional content id to list comments for a single item.',
                required: false,
            ),
            new ToolProperty(
                name: 'limit',
                type: PropertyType::INTEGER,
                description: 'Maximum number of comments to return (1-100). Default 25.',
                required: false,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        try {
            $status = strtolower(trim((string) ($args['status'] ?? 'all')));
            $limit = (int) ($args['limit'] ?? 25);
            if ($limit < 1 || $limit > 100) {
                $limit = 25;
            }

            $query = Comment::query()
                ->when($status === 'new', fn ($q) => $q->where('is_new', 1)->where('is_spam', '!=', 1))
                ->when($status === 'spam', fn ($q) => $q->where('is_spam', 1))
                ->when($status === 'moderated', fn ($q) => $q->where('is_moderated', 1))
                ->when(array_key_exists('rel_id', $args) && $args['rel_id'],
                    fn ($q) => $q->where('rel_id', (int) $args['rel_id']))
                ->orderByDesc('created_at')
                ->limit($limit);

            $rows = $query->get([
                'id', 'comment_name', 'comment_body', 'rel_id',
                'is_moderated', 'is_spam', 'is_new', 'created_at',
            ])->map(function ($c) {
                return [
                    'id' => $c->id,
                    'name' => $c->comment_name,
                    'excerpt' => mb_substr(trim(strip_tags((string) $c->comment_body)), 0, 120),
                    'on_content_id' => (int) $c->rel_id,
                    'moderated' => (int) $c->is_moderated === 1,
                    'spam' => (int) $c->is_spam === 1,
                    'new' => (int) $c->is_new === 1,
                    'created_at' => (string) $c->created_at,
                ];
            })->all();

            return json_encode([
                'count' => count($rows),
                'status_filter' => $status,
                'comments' => $rows,
            ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        } catch (\Throwable $e) {
            return $this->handleError('Failed to read comments: ' . $e->getMessage());
        }
    }
}
