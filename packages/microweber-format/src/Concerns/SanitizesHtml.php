<?php

namespace MicroweberPackages\Format\Concerns;

use HTMLPurifier_Config;
use MicroweberPackages\Security\XSSClean;

/**
 * Extracted from FormatService to break up the god class. Composed back into
 * FormatService, so inter-method calls and shared state still work.
 */
trait SanitizesHtml
{
    /**
     * Clean a value of XSS content.
     *
     * @param string|array $var
     * @return string|array
     */
    public function clean_xss($var, bool $do_not_strip_tags = false)
    {
        $xss = new XSSClean();

        if (is_array($var)) {
            $output = [];
            foreach ($var as $key => $val) {
                $output[$key] = $this->clean_xss($val, $do_not_strip_tags);
            }
            return $output;
        }

        $var = $xss->clean($var);

        $var = str_ireplace('<script>', '', $var);
        $var = str_ireplace('</script>', '', $var);
        $var = str_replace('<?', '&lt;?', $var);
        $var = str_replace('?>', '?&gt;', $var);
        $var = str_ireplace('javascript:', '', $var);
        $var = str_ireplace('vbscript:', '', $var);
        $var = str_ireplace('livescript:', '', $var);
        $var = str_ireplace('HTTP-EQUIV=', '', $var);
        $var = str_ireplace("\0075\0072\\", '', $var);

        if (!$do_not_strip_tags) {
            $var = strip_tags(trim($var));
        }

        return $var;
    }

    /**
     * Strip script tags from a string or array.
     *
     * @param string|array $input
     * @return string|array
     */
    public function clean_scripts($input)
    {
        if (is_array($input)) {
            $output = [];
            foreach ($input as $var => $val) {
                $output[$var] = $this->clean_scripts($val);
            }
            return $output;
        }

        if (is_string($input)) {
            return preg_replace(
                [
                    '@<script[^>]*?>.*?</script>@si',
                    '@<![\s\S]*?--[ \t\n\r]*>@',
                ],
                '',
                $input
            );
        }

        return $input;
    }

    /**
     * Clean HTML via HTMLPurifier.
     *
     * @param string|array $var
     * @return string|array|null
     */
    public function clean_html($var, bool $do_not_strip_tags = false)
    {
        if (is_array($var)) {
            $output = [];
            foreach ($var as $key => $val) {
                $output[$key] = $this->clean_html($val, $do_not_strip_tags);
            }
            return $output;
        }

        $path = null;
        if (function_exists('storage_path')) {
            $path = storage_path() . '/html_purifier';
        } elseif (function_exists('mw_cache_path')) {
            $path = mw_cache_path() . '/html_purifier';
        }
        if (!$path) {
            $path = sys_get_temp_dir() . '/html_purifier';
        }

        $var = $this->strip_unsafe($var);
        $config = \HTMLPurifier_Config::createDefault();

        if ($path) {
            $config->set('Cache.SerializerPath', $path);
            if (!is_dir($path)) {
                @mkdir($path, 0755, true);
            }
        }

        $purifier = new \HTMLPurifier($config);
        $var = $purifier->purify($var);

        $var = str_ireplace('<script>', '', $var);
        $var = str_ireplace('</script>', '', $var);
        $var = str_replace('<?', '&lt;?', $var);
        $var = str_replace('?>', '?&gt;', $var);
        $var = str_ireplace("\0075\0072\\", '', $var);

        if (!$do_not_strip_tags) {
            $var = strip_tags(trim($var));
        }

        return $var;
    }

    /**
     * Strip unsafe HTML tags (iframes, scripts, event handlers, etc.).
     *
     * @param string|array $string
     * @return string|array
     */
    public function strip_unsafe($string, bool $img = false)
    {
        if (is_array($string)) {
            foreach ($string as $key => $val) {
                $string[$key] = $this->strip_unsafe($val, $img);
            }
            return $string;
        }

        $unsafe = [
            '/<iframe(.*?)<\/iframe>/is',
            '/<frame(.*?)<\/frame>/is',
            '/<frameset(.*?)<\/frameset>/is',
            '/<object(.*?)<\/object>/is',
            '/<script(.*?)<\/script>/is',
            '/<embed(.*?)<\/embed>/is',
            '/<applet(.*?)<\/applet>/is',
            '/<meta(.*?)>/is',
            '/<!doctype(.*?)>/is',
            '/<link(.*?)>/is',
            '/<style(.*?)<\/style>/is',
            '/<body(.*?)>/is',
            '/<\/body>/is',
            '/<\/head>/is',
            '/onload="(.*?)"/is',
            '/onunload="(.*?)"/is',
            '/onafterprint="(.*?)"/is',
            '/onbeforeprint="(.*?)"/is',
            '/onbeforeunload="(.*?)"/is',
            '/onerrorNew="(.*?)"/is',
            '/onhaschange="(.*?)"/is',
            '/onoffline="(.*?)"/is',
            '/ononline="(.*?)"/is',
            '/onpagehide="(.*?)"/is',
            '/onpageshow="(.*?)"/is',
            '/onpopstate="(.*?)"/is',
            '/onredo="(.*?)"/is',
            '/onresize="(.*?)"/is',
            '/onstorage="(.*?)"/is',
            '/onundo="(.*?)"/is',
            '/onblur="(.*?)"/is',
            '/onchange="(.*?)"/is',
            '/oncontextmenu="(.*?)"/is',
            '/onfocus="(.*?)"/is',
            '/onformchange="(.*?)"/is',
            '/onforminput="(.*?)"/is',
            '/oninput="(.*?)"/is',
            '/oninvalid="(.*?)"/is',
            '/onreset="(.*?)"/is',
            '/onselect="(.*?)"/is',
            '/onsubmit="(.*?)"/is',
            '/onkeydown="(.*?)"/is',
            '/onkeypress="(.*?)"/is',
            '/onkeyup="(.*?)"/is',
            '/onclick="(.*?)"/is',
            '/ondblclick="(.*?)"/is',
            '/ondrag="(.*?)"/is',
            '/ondragend="(.*?)"/is',
            '/ondragenter="(.*?)"/is',
            '/ondragleave="(.*?)"/is',
            '/ondragover="(.*?)"/is',
            '/ondragstart="(.*?)"/is',
            '/ondrop="(.*?)"/is',
            '/onmousedown="(.*?)"/is',
            '/onmousemove="(.*?)"/is',
            '/onmouseout="(.*?)"/is',
            '/onmouseover="(.*?)"/is',
            '/onmousewheel="(.*?)"/is',
            '/onmouseup="(.*?)"/is',
            '/onabort="(.*?)"/is',
            '/oncanplay="(.*?)"/is',
            '/oncanplaythrough="(.*?)"/is',
            '/ondurationchange="(.*?)"/is',
            '/onended="(.*?)"/is',
            '/onerror="(.*?)"/is',
            '/onloadedmetadata="(.*?)"/is',
            '/onloadstart="(.*?)"/is',
            '/onpause="(.*?)"/is',
            '/onplay="(.*?)"/is',
            '/onplaying="(.*?)"/is',
            '/onprogress="(.*?)"/is',
            '/onratechange="(.*?)"/is',
            '/onreadystatechange="(.*?)"/is',
            '/onseeked="(.*?)"/is',
            '/onseeking="(.*?)"/is',
            '/onstalled="(.*?)"/is',
            '/onsuspend="(.*?)"/is',
            '/ontimeupdate="(.*?)"/is',
            '/onvolumechange="(.*?)"/is',
            '/onwaiting="(.*?)"/is',
            '/href="javascript:[^"]+"/',
            '/href=javascript:/is',
            '/<html(.*?)>/is',
            '/<iframe(.*?)>/is',
            '/<iframe(.*?)/is',
            '/<\/html>/is',
        ];

        if ($img) {
            $unsafe[] = '/<img(.*?)>/is';
        }

        return preg_replace($unsafe, '', (string) $string);
    }
}
