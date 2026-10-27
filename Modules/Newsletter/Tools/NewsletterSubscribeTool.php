<?php

declare(strict_types=1);

namespace Modules\Newsletter\Tools;

use MicroweberPackages\AiTools\Base\BaseTool;
use Modules\Newsletter\Models\NewsletterSubscriber;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

/**
 * MCP write tool: subscribe an email to the newsletter (optionally to a specific
 * list). Idempotent — an existing subscriber is reused, not duplicated. Like the
 * other MCP write tools it has no per-tool permission gate; the MCP server's
 * client-token / allowed-modules layer authorises it.
 */
class NewsletterSubscribeTool extends BaseTool
{
    protected string $domain = 'newsletter';

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'newsletter_subscribe',
            'Subscribe an email address to the newsletter, optionally adding it to a '
            . 'specific list by id. Reuses an existing subscriber rather than '
            . 'creating a duplicate.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'email',
                type: PropertyType::STRING,
                description: 'The email address to subscribe.',
                required: true,
            ),
            new ToolProperty(
                name: 'name',
                type: PropertyType::STRING,
                description: 'Optional subscriber name.',
                required: false,
            ),
            new ToolProperty(
                name: 'list_id',
                type: PropertyType::INTEGER,
                description: 'Optional newsletter list id to add the subscriber to.',
                required: false,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        $email = trim((string) ($args['email'] ?? ''));
        if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return $this->handleError('A valid email is required.');
        }

        $name = trim((string) ($args['name'] ?? ''));
        $listId = (int) ($args['list_id'] ?? 0);

        try {
            $attributes = [];
            if ($name !== '') {
                $attributes['name'] = $name;
            }

            $subscriber = NewsletterSubscriber::firstOrCreate(['email' => $email], $attributes);

            $listNote = '';
            if ($listId > 0 && method_exists($subscriber, 'lists')) {
                $subscriber->lists()->syncWithoutDetaching([$listId]);
                $listNote = " and added to list #{$listId}";
            }

            return $this->handleSuccess(
                "Subscribed \"{$email}\"{$listNote} (subscriber #{$subscriber->getKey()})."
            );
        } catch (\Throwable $e) {
            return $this->handleError('Error subscribing email: ' . $e->getMessage());
        }
    }
}
