<?php

namespace MicroweberPackages\View;


use  Illuminate\View\Compilers\ComponentTagCompiler;
use Livewire\Exceptions\ComponentAttributeMissingOnDynamicComponentException;

class MicroweberModuleTagCompiler extends ComponentTagCompiler
{
    public function compile($value)
    {
        return $this->compileMicroweberModuleSelfClosingTags($value);
    }

    protected function compileMicroweberModuleSelfClosingTags($value)
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

        return preg_replace_callback($pattern, function (array $matches) {

            $attributes = $this->getAttributesFromAttributeString($matches['attributes']);

            // Convert all kebab-cased to camelCase.
            $attributes = collect($attributes)->mapWithKeys(function ($value, $key) {
                // Skip snake_cased attributes.
                if (str($key)->contains('_')) return [$key => $value];

                return [(string) str($key)->camel() => $value];
            })->toArray();

            // Convert all snake_cased attributes to camelCase, and merge with
            // existing attributes so both snake and camel are available.
            $attributes = collect($attributes)->mapWithKeys(function ($value, $key) {
                // Skip snake_cased attributes
                if (! str($key)->contains('_')) return [$key => false];

                return [(string) str($key)->camel() => $value];
            })->filter()->merge($attributes)->toArray();

            $component = $matches[1];
            $component = "'{$component}'";

            return $this->componentString($component, $attributes);
        }, $value);
    }

    protected function componentString(string $component, array $attributes)
    {
        return "@module([".$this->attributesToString($attributes, $escapeBound = false).'])';
    }

    protected function attributesToString(array $attributes, $escapeBound = true)
    {
        return collect($attributes)
            ->map(function (string $value, string $attribute) use ($escapeBound) {
                return $escapeBound && isset($this->boundAttributes[$attribute]) && $value !== 'true' && ! is_numeric($value)
                    ? "'{$attribute}' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute({$value})"
                    : "'{$attribute}' => {$value}";
            })
            ->implode(',');
    }
}
