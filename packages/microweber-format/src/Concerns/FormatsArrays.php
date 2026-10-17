<?php

namespace MicroweberPackages\Format\Concerns;

/**
 * Extracted from FormatService to break up the god class. Composed back into
 * FormatService, so inter-method calls and shared state still work.
 */
trait FormatsArrays
{
    /**
     * Prints an array as an unordered HTML list (<ul>).
     */
    public function array_to_ul(array $arr, string $ul_tag = 'ul', string $li_tag = 'li'): string
    {
        $has_items = false;
        $retStr = '<' . $ul_tag . '>';

        foreach ($arr as $key => $val) {
            if (!is_array($key) && $val) {
                $key = str_replace('_', ' ', (string) $key);
                $key = ucwords($key);

                if (is_array($val)) {
                    if (!empty($val)) {
                        $has_items = true;
                        if (is_numeric($key)) {
                            $retStr .= '<' . $ul_tag . '>';
                            $retStr .= '<' . $li_tag . '>' . $this->array_to_ul($val, $ul_tag, $li_tag) . '</' . $li_tag . '>';
                            $retStr .= '</' . $ul_tag . '>';
                        } else {
                            $retStr .= '<' . $li_tag . '><span>' . $key . ':</span> ' . $this->array_to_ul($val, $ul_tag, $li_tag) . '</' . $li_tag . '>';
                        }
                    }
                } else {
                    if (is_string($val) && trim($val) !== '') {
                        $has_items = true;
                        if (is_numeric($key)) {
                            $retStr .= '<' . $li_tag . '><span></span> ' . $val . '</' . $li_tag . '>';
                        } else {
                            $retStr .= '<' . $li_tag . '><span>' . $key . ':</span> ' . $val . '</' . $li_tag . '>';
                        }
                    }
                }
            } else {
                if (!empty($val)) {
                    $has_items = true;
                    $retStr .= $this->array_to_ul((array) $val, $ul_tag, $li_tag);
                }
            }
        }

        $retStr .= '</' . $ul_tag . '>';

        return $has_items ? $retStr : '';
    }

    /**
     * Convert a 2-D array to an HTML table.
     */
    public function array_to_table(array $array, bool $table = true): string
    {
        $out = '';
        $tableHeader = '';

        foreach ($array as $key => $value) {
            if (is_array($value)) {
                if ($tableHeader === '') {
                    $tableHeader = '<th>' . implode('</th><th>', array_keys($value)) . '</th>';
                }
                $out .= '<tr>' . $this->array_to_table($value, false) . '</tr>';
            } else {
                $out .= "<td>{$value}</td>";
            }
        }

        return $table ? '<table>' . $tableHeader . $out . '</table>' : $out;
    }

    /**
     * Trim all values in an array.
     */
    public function array_trim(array $variable): array
    {
        return array_map('trim', $variable);
    }

    /**
     * Recursively flatten array values (scalar only).
     */
    public function array_values(array $ary): array
    {
        $lst = [];
        foreach ($ary as $v) {
            if (is_scalar($v)) {
                $lst[] = $v;
            } elseif (is_array($v)) {
                $lst = array_merge($lst, $this->array_values($v));
            }
        }
        return $lst;
    }
}
