<?php
namespace MicroweberPackages\App\Parser\Traits;

trait ParserHelperTrait {

    private function _pregMatchModuleTags($layout) {

        preg_match_all('/<module[^>]*>/Uis', $layout, $module_tags);

        if (empty($module_tags[0])) {
            return false;
        }

        return $module_tags[0];
    }

    private function _strReplaceFirst($search, $replace, $subject)
    {
        if ($search == false || $replace == false) {
            return $subject;
        }
        if (!is_string($search)) {
            return $subject;
        }

        $pos = strpos($subject, (string)$search);
        if ($pos !== false) {
            $subject = substr_replace($subject, $replace, $pos, strlen($search));
        }

        return $subject;
    }

    private function _extractTagAttributes($value)
    {
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
