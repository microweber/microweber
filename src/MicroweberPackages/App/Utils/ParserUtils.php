<?php


namespace MicroweberPackages\App\Utils;


class ParserUtils
{


    public function parseAttributes($html_tag)
    {

        $attribute_pattern = '@(?P<name>[a-z-_A-Z]+)\s*=\s*((?P<quote>[\"\'])(?P<value_quoted>.*?)(?P=quote)|(?P<value_unquoted>[^\s"\']+?)(?:\s+|$))@xsi';

        $attrs = array();
        if (preg_match_all($attribute_pattern, $html_tag, $attrs1, PREG_SET_ORDER)) {
            $mw_attrs_key_value_seperator = "__MW_PARSER_ATTR_VAL__";

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

        return $attrs;

    }

    public function getModuleTypeFromParsedAttributes($attrs)
    {
        if (isset($attrs['type'])) {
            return $attrs['type'];
        } else if (isset($attrs['data-type'])) {
            return $attrs['data-type'];
        }
    }


    public function getEditFieldAttributesFromParsedAttributes($attrs)
    {
        $field = false;
        $rel = false;
        $rel_id = false;


        if (isset($attrs['field'])) {
            $field = $attrs['field'];
        } else if (isset($attrs['data-field'])) {
            $field = $attrs['data-field'];
        }


        if (isset($attrs['rel'])) {
            $rel = $attrs['rel'];
        } else if (isset($attrs['data-rel'])) {
            $rel = $attrs['data-rel'];
        }

        if (isset($attrs['rel_id'])) {
            $rel_id = $attrs['rel_id'];
        } else if (isset($attrs['rel-id'])) {
            $rel_id = $attrs['rel-id'];
        } else if (isset($attrs['data-rel-id'])) {
            $rel_id = $attrs['data-rel-id'];
        } else if (isset($attrs['data-id'])) {
            $rel_id = $attrs['data-id'];
        }


        return ['field'=>$field,'rel'=>$rel,'rel_id'=>$rel_id];
    }


}