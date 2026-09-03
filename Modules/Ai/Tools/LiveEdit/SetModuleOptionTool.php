<?php

declare(strict_types=1);

namespace Modules\Ai\Tools\LiveEdit;

use MicroweberPackages\AiTools\Base\BaseTool;
use NeuronAI\Tools\PropertyType;
use NeuronAI\Tools\ToolProperty;

/**
 * Live-Edit frontend tool: set a configuration option on a module.
 *
 * Configures a module the agent inserted — e.g. a contact form's recipient
 * email, a gallery's column count. Frontend tool: the browser persists the
 * option via mw.options.saveOption(...) (see mw-ai.js frontendTools.
 * set_module_option). If module_id is omitted it targets the most recently
 * inserted module. Backend is a thin side-effect-free declaration.
 */
class SetModuleOptionTool extends BaseTool
{
    protected string $domain = 'liveedit';

    public function __construct(protected array $dependencies = [])
    {
        parent::__construct(
            'set_module_option',
            'Set a configuration option on a module you inserted (for example a '
            . 'contact form\'s recipient email). If you just inserted a module you '
            . 'can omit module_id and it targets that module. The change is saved '
            . 'through the module options; the user still saves the page with the '
            . 'Live-Edit Save button.'
        );
    }

    protected function properties(): array
    {
        return [
            new ToolProperty(
                name: 'key',
                type: PropertyType::STRING,
                description: 'The option key to set, e.g. "email" for a contact '
                    . 'form\'s recipient address, or "data-..." option keys.',
                required: true,
            ),
            new ToolProperty(
                name: 'value',
                type: PropertyType::STRING,
                description: 'The value to set for the option.',
                required: true,
            ),
            new ToolProperty(
                name: 'module_id',
                type: PropertyType::STRING,
                description: 'The id of the module to configure. Omit to use the most '
                    . 'recently inserted module.',
                required: false,
            ),
        ];
    }

    public function __invoke(...$args): string
    {
        $key = trim((string) ($args['key'] ?? ''));
        if ($key === '') {
            return $this->handleError('No option key was provided.');
        }

        return "OK — setting \"{$key}\" on the module (live). The user saves the page "
            . "with the Live-Edit Save button.";
    }
}
