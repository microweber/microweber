<?php

 
function template_header($script_src)
{
    return \mw\Content::template_header($script_src);
}

function template_headers_src()
{
    return \mw\Content::template_header(true);

}

/**
 * @desc  Get the template layouts info under the layouts subdir on your active template
 * @param $options
 * $options ['type'] - 'layout' is the default type if you dont define any. You can define your own types as post/form, etc in the layout.txt file
 * @return array
 * @author    Microweber Dev Team
 * @since Version 1.0
 */
function templates_list($options = false)
{
    return \mw\ContentUtils::templates_list($options);

}

function layout_link($options = false)
{
    return \mw\Layouts::get_link($options);
}


/**
 * Lists the layout files from a given directory
 *
 * You can use this function to get layouts from various folders in your web server.
 * It returns array of layouts with desctption, icon, etc
 *
 * This function caches the result in the 'templates' cache group
 *
 * @param bool|array|string $options
 * @return array|mixed
 *
 * @params $options['path'] if set i will look for layouts in this folder
 * @params $options['get_dynamic_layouts'] if set this function will scan for templates for the 'layout' module in all templates folders
 *
 *
 *
 *
 *
 */
function layouts_list($options = false)
{
    return \mw\Layouts::scan($options);
}


