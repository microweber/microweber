<?php

namespace Microweber\Utils\Adapters\Template\RenderHelpers;

use Arcanedev\SeoHelper\Entities\Webmasters;
use Arcanedev\SeoHelper\Entities\Analytics;


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
        if (!isset($stacks[$group])) {
            $stacks[$group] = array();
        }
        if ($key) {
            $stacks[$group][$key] = $src;
        } else {
            $stacks[$group][] = $src;

        }
    }


    public function display($group = 'default')
    {
        if (!isset($this->_stacks_for_display[$group]) and isset($this->stacks[$group])) {
            $this->_stacks_for_display[$group] = $this->stacks[$group];

            if (!isset($_printer[$group])) {
                $replace_later = '<!-- [template-stack-replace-' . $group . '] -->';
                $this->_printer[$group] = $replace_later;
                print $replace_later;
            }

        }

    }


    public function render($params)
    {

        if (isset($params['layout'])) {
            $layout = $params['layout'];

            if ($this->_printer) {
                foreach($this->_printer as $replace_key=>$stack){
                    $layout = str_replace_first($replace_key, $stack, $layout);
                }
            }

            return $layout;
        }

    }


}