<?php

declare(strict_types=1);

namespace MicroweberPackages\View\Support;

use MicroweberPackages\View\Contracts\ModuleProcessorInterface;

/**
 * Adapts the CMS app()->parser object to ModuleProcessorInterface.
 */
class CmsModuleProcessorAdapter implements ModuleProcessorInterface
{
    public function __construct(
        private readonly object $parser,
    ) {
    }

    /**
     * {@inheritdoc}
     */
    public function process(string $html, array $options = []): string
    {
        if (!method_exists($this->parser, 'process')) {
            return $html;
        }

        /** @var mixed $result */
        $result = $options === []
            ? $this->parser->process($html)
            : $this->parser->process($html, $options);

        if (is_string($result)) {
            return $result;
        }

        if (is_scalar($result) || $result === null) {
            return (string) $result;
        }

        return $html;
    }
}
