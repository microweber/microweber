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


        $pregReplaceResponse = preg_replace_callback($pattern, function (array $matches) {

            $attributes = [];
         //    $getAttributes = $this->extractModuleTagAttributes($matches[0]);
             $getAttributes = $this->getAttributesFromAttributeString($matches[0]);

            foreach ($getAttributes as $attributeKey=>$attributeValue) {
              //  $attributes[$attributeKey] = "'".$this->compileAttributeEchos($attributeValue)."'";
                $attributes[$attributeKey] = $attributeValue;
            }

            $component = "'{$matches[0]}'";

            return $this->componentString($component, $attributes);

        }, $value);

        //dd($pregReplaceResponse);

        return $pregReplaceResponse;
    }

    protected function componentString(string $component, array $attributes)
    {
        $componentString = "@module([".$this->attributesToString($attributes, $escapeBound = false).'])';

        return $componentString;
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

    private function extractModuleTagAttributes($module_tag)
    {
        $value = $module_tag;
        $attrs = array();
        $attribute_pattern = '@(?P<name>[a-z-_A-Z]+)\s*=\s*((?P<quote>[\"\'])(?P<value_quoted>.*?)(?P=quote)|(?P<value_unquoted>[^\s"\']+?)(?:\s+|$))@xsi';
        $mw_attrs_key_value_seperator = "__MW_PARSER_ATTR_VAL__";
        if (preg_match_all($attribute_pattern, $value, $attrs1, PREG_SET_ORDER)) {
            foreach ($attrs1 as $item) {
                $m_tag = trim($item[0], "\x22\x27");
                $m_tag = trim($m_tag, "\x27\x22");
                $m_tag = preg_replace('/=/', $mw_attrs_key_value_seperator, $m_tag, 1);


                $m_tag = explode($mw_attrs_key_value_seperator, $m_tag);

                $a = trim($m_tag[0], "''");
                $a = trim($a, '""');
                $b = trim($m_tag[1], "''");
                $b = trim($b, '""');
                if (isset($m_tag[2])) {
                    $rest_pieces = $m_tag;
                    if (isset($rest_pieces[0])) {
                        unset($rest_pieces[0]);
                    }
                    if (isset($rest_pieces[1])) {
                        unset($rest_pieces[1]);
                    }
                    $rest_pieces = implode($mw_attrs_key_value_seperator, $rest_pieces);
                    $b = $b . $rest_pieces;
                }

                $attrs[$a] = $b;
            }
        }

        if ($attrs) {
            return $attrs;
        }
    }
}
