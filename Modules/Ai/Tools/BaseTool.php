<?php

declare(strict_types=1);

namespace Modules\Ai\Tools;

use MicroweberPackages\AiTools\Base\BaseTool as BasePackageTool;

/**
 * Base Tool class - Backward Compatibility Layer
 *
 * This class extends the new microweber-packages/ai-tools BaseTool
 * to maintain backward compatibility with existing code.
 *
 * @deprecated Use MicroweberPackages\AiTools\Base\BaseTool instead
 */
abstract class BaseTool extends BasePackageTool
{
    /**
     * Legacy constructor for backward compatibility.
     *
     * @param string $name
     * @param string $description
     * @param array $dependencies
     */
    public function __construct(
        string $name = '',
        string $description = '',
        protected array $dependencies = []
    ) {
        // If name is empty, use class name (for subclasses that don't pass name)
        if (empty($name)) {
            $name = $this->getToolNameFromClass();
        }

        // If description is empty, use default
        if (empty($description)) {
            $description = $this->getDefaultDescription();
        }

        parent::__construct($name, $description, $dependencies);
    }

    /**
     * Get tool name from class name.
     *
     * @return string
     */
    protected function getToolNameFromClass(): string
    {
        $className = class_basename(static::class);
        // Remove "Tool" suffix and convert to snake_case
        $name = preg_replace('/Tool$/', '', $className);
        return strtolower(preg_replace('/(?<!^)[A-Z]/', '_$0', $name));
    }

    /**
     * Get default description from class docblock.
     *
     * @return string
     */
    protected function getDefaultDescription(): string
    {
        $reflection = new \ReflectionClass(static::class);
        $docComment = $reflection->getDocComment();

        if ($docComment) {
            // Extract first line of docblock
            $lines = explode("\n", $docComment);
            foreach ($lines as $line) {
                $line = trim($line, "\t /*\r\n");
                if (!empty($line) && !str_starts_with($line, '@')) {
                    return $line;
                }
            }
        }

        return 'AI Tool';
    }
}
