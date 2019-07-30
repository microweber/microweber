<?php

namespace Microweber\Utils\Adapters\Template;


class TemplateStackRenderer
{
    /** @var \Microweber\Application */
    public $app;
    public $stacks = array();
    private $_stacks_for_display = array();
    private $_printer = array();


    public function __construct($app = null)
    {
        $this->app = $app;
    }

    public function add($src, $group = 'default', $key = null)
    {
        if (!isset($this->stacks[$group])) {
            $this->stacks[$group] = array();
        }
        if ($key) {
            $this->stacks[$group][$key] = $src;
        } else {
            $this->stacks[$group][] = $src;
        }
    }


    public function display($group = 'default')
    {
        if (!isset($this->_stacks_for_display[$group])) {
            $this->_stacks_for_display[$group] = true;

            if (!isset($this->_printer[$group])) {
                $replace_later = '<!-- [template-stack-display-' . $group . '] -->';
                $this->_printer[$group] = $replace_later;
                print $replace_later;
            }
        }
    }


    public function render($layout)
    {


        if ($this->_printer) {
            foreach ($this->_printer as $stack_name => $replace_key) {
                if (isset($this->_stacks_for_display[$stack_name])) {
                    $stack = $this->stacks[$stack_name];
                    if ($stack) {
                        $stack_html = '';
                        $stack_html = $this->__buildHTML($stack);

                       $layout = str_replace_first($replace_key, $stack_html, $layout);

                    }
                }


            }
        }

        return $layout;
    }


    private function __buildHTML($stack_items)
    {
        $html_out = '';

        $internals_js = array(
            mw()->template->get_apijs_settings_url(),
            mw()->template->get_apijs_url()
        );

        $css = array();
        $js = array();
        foreach ($stack_items as $stack_item) {
            $url = strtok($stack_item, '?');
            $ext = get_file_extension($url);
            $ext = strtolower($ext);

            if ($ext == 'js' or in_array($url, $internals_js) or in_array($stack_item, $internals_js)) {
                $js[] = $url;
            } else {
                $css[] = $url;
            }
        }


        if ($js) {
            $js = array_unique($js);
            foreach ($js as $url) {
                $html_out .= '<script type="text/javascript" src="' . $url . '"></script>' . "\n";
            }
        }
        if ($css) {
            $css = array_unique($css);

            foreach ($css as $url) {

                $html_out .= '<link rel="stylesheet" media="all" type="text/css" href="' . $url . '">' . "\n";
            }
        }


        return $html_out;
    }

}