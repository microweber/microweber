<?php

declare(strict_types=1);

namespace MicroweberPackages\View;

use Illuminate\View\Compilers\ComponentTagCompiler;

/**
 * Blade precompiler that turns <module ... /> tags into @module([...]) directives.
 *
 * Standalone-safe: no CMS dependencies. Module processing can be toggled at runtime.
 */
class MicroweberModuleTagCompiler extends ComponentTagCompiler
{
    public static bool $isEnabled = true;

    public static function enableModuleProcessing(): void
    {
        self::$isEnabled = true;
    }

    public static function disableModuleProcessing(): void
    {
        self::$isEnabled = false;
    }

    public function compile(string $value): string
    {
        if (!self::$isEnabled) {
            return $value;
        }

        return $this->compileMicroweberModuleSelfClosingTags($value);
    }

    protected function compileMicroweberModuleSelfClosingTags(string $value): string
    {
        $pattern = "/
            <
                \s*
               module
                \s*
                (?<attributes>
                    (?:
                        \s+
                        [\w\-:.@]+
                        (
                            =
                            (?:
                                \\\"[^\\\"]*\\\"
                                |
                                \'[^\']*\'
                                |
                                [^\'\\\"=<>]+
                            )
                        )?
                    )*
                    \s*
                )
            \/?>
        /x";

        $result = preg_replace_callback($pattern, function (array $matches): string {
            // Normalize the self-closing slash so empty attributes like
            // button_size="" /> parse correctly via Laravel's attribute parser.
            $moduleTagForAttrs = preg_replace('#\s*/>\s*$#', ' />', $matches[0]) ?? $matches[0];
            $getAttributes = $this->getAttributesFromAttributeString($moduleTagForAttrs);

            $attributes = [];
            foreach ($getAttributes as $attributeKey => $attributeValue) {
                $key = is_string($attributeKey) ? $attributeKey : (is_int($attributeKey) ? (string) $attributeKey : '');
                if (is_string($attributeValue)) {
                    $attributes[$key] = $attributeValue;
                } elseif (is_scalar($attributeValue) || $attributeValue === null) {
                    $attributes[$key] = (string) $attributeValue;
                } else {
                    $attributes[$key] = '';
                }
            }

            return $this->componentString('module', $attributes);
        }, $value);

        return is_string($result) ? $result : $value;
    }

    /**
     * @param  array<string, string>  $attributes
     * @return string
     */
    protected function componentString(string $component, array $attributes)
    {
        return '@module([' . $this->attributesToString($attributes, escapeBound: false) . '])';
    }

    /**
     * @param  array<string, string>  $attributes
     * @param  bool  $escapeBound
     * @return string
     */
    protected function attributesToString(array $attributes, $escapeBound = true)
    {
        return collect($attributes)
            ->map(function (string $value, string $attribute) use ($escapeBound): string {
                return $escapeBound
                    && isset($this->boundAttributes[$attribute])
                    && $value !== 'true'
                    && !is_numeric($value)
                    ? "'{$attribute}' => \\Illuminate\\View\\Compilers\\BladeCompiler::sanitizeComponentAttribute({$value})"
                    : "'{$attribute}' => {$value}";
            })
            ->implode(',');
    }
}
