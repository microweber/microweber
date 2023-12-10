<?php

namespace MicroweberPackages\Helper;

class HTMLClean
{
    public $purifierPath;

    public function __construct(){
        $path = storage_path() . '/html_purifier';
        if (!is_dir($path)) {
            mkdir_recursive($path);
        }
        $this->purifierPath = $path;
    }

    public function cleanArray($array) {

        if (is_array($array)) {

            $cleanedArray = [];
            foreach ($array as $key=>$value) {
                if (is_array($value)) {
                    $cleanedArray[$key] = $this->cleanArray($value);
                } else {
                    $cleanedArray[$key] = $this->clean($value);
                }
            }

            return $cleanedArray;
        }
    }

    public function clean($html,$options = []) {



        $xssClean = new XSSClean();
        $html = $xssClean->clean($html);

        $config = \HTMLPurifier_Config::createDefault();

        if ($this->purifierPath) {
            $config->set('Cache.SerializerPath', $this->purifierPath);
        }


        if ($this->purifierPath) {
            $config->set('Cache.SerializerPath', $this->purifierPath);
        }
        if(isset($options['disable_external_resources']) and $options['disable_external_resources'] == true){
            $config->set('URI.DisableExternal', true);
            $config->set('URI.DisableExternalResources', true);
        }
     //   $config->set('URI.DisableExternal', true);
       // $config->set('URI.DisableExternalResources', true);
    //    $config->set('URI.DisableResources', true);
        $config->set('URI.Host', site_hostname());

        if(isset($options['admin_mode']) and $options['admin_mode']){

            $config->set('HTML.Allowed', 'div,module,section,*[style|class|field|rel|type|data-type|id|data-background-image|data-src],img[src],p,b,a[href],i,ul,ol,li,img[src],br,div[style],span[style],table,td,tr,th,tbody,thead,iframe[src|width|height|frameborder],h1,h2,h3,h4,h5,h6,hr,blockquote,pre,code,small');
            $config->set('CSS.AllowedProperties', 'font,font-size,font-weight,font-style,font-family,text-decoration,padding-left,color,background-color,text-align,margin-left,margin-right,margin-top,margin-bottom,width,height,margin,margin-left,margin-right,margin-top,margin-bottom,padding,padding-left,padding-right,padding-top,padding-bottom,border,border-left,border-right,border-top,border-bottom,border-color,border-width,border-style,display,vertical-align,white-space,box-sizing,clear,float,position,top,left,right,bottom,overflow,overflow-x,overflow-y,visibility,z-index');
            $config->set('CSS.AllowTricky', true);
            $config->set('CSS.Proprietary', true);
            $config->set('HTML.Proprietary', true);
            $config->set('HTML.TidyLevel', 'none');
            $config->set('URI.AllowedSchemes',  array(
                'http' => true,
                'https' => true,
            ));
                $config->set('URI.DisableExternal', false);
             $config->set('URI.DisableExternalResources', false);
                $config->set('URI.DisableResources', false);

            $allowedAttrs = [
                'style',
                'src',
                'class',
                'id',
                'type',
                'action',
                'text',
                'template',
                'height',
                'width',
//                'data-parent-module',
//                'data-parent-module-id',
//                'data-parent-module-original-id',
//                'data-parent-module-type',

                'data-background-image',
                'data-type',
            //    'data-template',
                'data-module',
                'data-filter',
                'data-x',
                'button_style',
                'button_size',
                'text',
                'data-background-image',
//                'data-type',
//                'data-module-name',
//                'data-module-id',
//                'data-module-original-id',
//                'data-module-type',
//                'data-module-category',


            ];

            $config->set('HTML.AllowedAttributes',  implode(',', $allowedAttrs));

            $config->set('AutoFormat.AutoParagraph', false);
            $config->set('AutoFormat.RemoveEmpty', false);
            $config->set('Attr.EnableID', true);
            $config->set('HTML.SafeIframe', true);
            $config->set('HTML.Trusted', true);
            $config->set('CSS.Trusted', true);
            $config->set('HTML.SafeObject', true);
            $config->set('Output.FlashCompat', true);
            $config->set('HTML.SafeEmbed', true);
            $config->set('HTML.FlashAllowFullScreen', true);
            $config->set('Filter.YouTube', true);


            $config->set('URI.SafeIframeRegexp', '%^https://(www.youtube.com/embed/|player.vimeo.com/video/)%');

            // Set some HTML5 properties
        //    $config->set('HTML.DefinitionID', 'html5-definitions'); // unqiue id
         //   $config->set('HTML.DefinitionRev', 2);



            $def = $config->getHTMLDefinition(true);
            $def->addAttribute('a', 'target', 'Enum#_blank,_self,_target,_top');
            $def->addAttribute('div', 'data-background-image', 'CDATA');
            $def->addAttribute('div', 'data-type',  'CDATA');
            $def->addAttribute('div', 'data-template',  'CDATA');
            $def->addAttribute('div', 'template',  'CDATA');
            $def->addAttribute('div', 'height',  'CDATA');
            $def->addAttribute('div', 'width',  'CDATA');
            $def->addAttribute('div', 'data-module',  'CDATA');
            $def->addAttribute('div', 'module',  'CDATA');
            $def->addAttribute('div', 'parent-module',  'CDATA');
            $def->addAttribute('div', 'parent-module-id',  'CDATA');
            $def->addAttribute('div', 'data-parent-module',  'CDATA');
            $def->addAttribute('div', 'data-parent-module-id',  'CDATA');
            $def->addAttribute('div', 'data-filter',  'CDATA');
            $def->addAttribute('div', 'data-layout-container',  'CDATA');
            $def->addElement('mark', 'Inline', 'Inline', 'Common');
            $def->addElement('figure', 'Block', 'Optional: (figcaption, Flow) | (Flow, figcaption) | Flow', 'Common');
            $def->addElement('figcaption', 'Inline', 'Flow', 'Common');
            $def->addElement('iframe', 'Block', 'Flow', 'Common');
            //button_style="btn-primary   " button_size="btn-lg px-5" text="Call to action"

            $def->addElement('module', 'Block', 'Flow', 'Common',array(
                'type' => 'Text',
                'id' => 'Text',
                'template' => 'Text',
                'data-type' => 'Text',
                'data-module' => 'Text',
                'data-filter' => 'Text',
                'data-layout-container' => 'Text',
                'data-x' => 'Text',
                'button_style' => 'Text',
                'button_size' => 'Text',
                'text' => 'Text',
                'data-background-image' => 'Text',
            ));

            $def->addElement('video', 'Block', 'Optional: (source, Flow) | (Flow, source) | Flow', 'Common', array(
                'src'      => 'URI',
                'type'     => 'Text',
                'width'    => 'Length',
                'height'   => 'Length',
                'poster'   => 'URI',
                'preload'  => 'Enum#auto,metadata,none',
                'controls' => 'Bool',
            ));






        }

        $purifier = new \HTMLPurifier($config);
        $html = $purifier->purify($html);

        return $html;
    }

    public function onlyTags($html, $tags = ['i','a','strong','code','pre','blockquote','em','strike','p','span','caption','cite']) {

        $config = \HTMLPurifier_Config::createDefault();

        if ($this->purifierPath) {
            $config->set('Cache.SerializerPath', $this->purifierPath);
        }

        $config->set('HTML.AllowedElements', $tags);
        $config->set('URI.Host', '*');
        $config->set('URI.DisableExternal', false);
        $config->set('URI.DisableExternalResources', false);
        $config->set('HTML.Allowed', 'p,b,a[href],i');
        $config->set('HTML.AllowedAttributes', 'a.href');

        $purifier = new \HTMLPurifier($config);
        $html = $purifier->purify($html);

        return $html;
    }
}
