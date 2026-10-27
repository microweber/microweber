<?php

declare(strict_types=1);

namespace Modules\Customer\Tools;

use MicroweberPackages\AiTools\Base\BaseTool;
use Modules\Customer\Models\Customer;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

/**
 * MCP write tool: create a customer record. Reuses an existing customer with the
 * same email rather than creating a duplicate. Like the other MCP write tools it
 * has no per-tool permission gate; the MCP server's client-token /
 * allowed-modules layer authorises it.
 */
class CustomerCreateTool extends BaseTool
{
    protected string $domain = 'customer';

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'customer_create',
            'Create a customer (email required; optional first/last name and phone). '
            . 'Reuses an existing customer with the same email instead of duplicating.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'email',
                type: PropertyType::STRING,
                description: 'The customer email address.',
                required: true,
            ),
            new ToolProperty(
                name: 'first_name',
                type: PropertyType::STRING,
                description: 'Customer first name.',
                required: false,
            ),
            new ToolProperty(
                name: 'last_name',
                type: PropertyType::STRING,
                description: 'Customer last name.',
                required: false,
            ),
            new ToolProperty(
                name: 'phone',
                type: PropertyType::STRING,
                description: 'Customer phone number.',
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

        $attributes = array_filter([
            'first_name' => trim((string) ($args['first_name'] ?? '')),
            'last_name' => trim((string) ($args['last_name'] ?? '')),
            'phone' => trim((string) ($args['phone'] ?? '')),
        ], static fn ($v) => $v !== '');

        try {
            $existing = Customer::where('email', $email)->first();
            if ($existing) {
                return $this->handleSuccess(
                    "Customer with email \"{$email}\" already exists (customer #{$existing->getKey()}); no duplicate created."
                );
            }

            $customer = Customer::create(array_merge(['email' => $email], $attributes));

            return $this->handleSuccess("Created customer \"{$email}\" (customer #{$customer->getKey()}).");
        } catch (\Throwable $e) {
            return $this->handleError('Error creating customer: ' . $e->getMessage());
        }
    }
}
