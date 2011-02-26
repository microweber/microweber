<?php

/**
 * Matchbox Language class
 *
 * This file is part of Matchbox
 *
 * Matchbox is free software; you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation; either version 3 of the License, or
 * (at your option) any later version.
 *
 * Matchbox is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package   Matchbox
 * @copyright 2007-2008 Zacharias Knudsen
 * @license   http://www.gnu.org/licenses/gpl.html
 * @version   $Id: MY_Language.php 204 2008-02-24 01:30:00Z zacharias@dynaknudsen.dk $
 */

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Extends the CodeIgniter Language class
 *
 * All code not encapsulated in {{{ Matchbox }}} was made by EllisLab
 *
 * @package   Matchbox
 * @copyright 2007-2008 Zacharias Knudsen
 * @license   http://www.gnu.org/licenses/gpl.html
 */
class MY_Language extends CI_Language
{
    // {{{ Matchbox

    /**
     * Loads language file from module
     *
     * @param  string
     * @param  string
     * @param  string
     * @param  bool
     * @return void
     * @access public
     */
    function module_load($module, $langfile = '', $idiom = '', $return = false)
    {
        return $this->load($langfile, $idiom, $return, $module);
    }

    // }}}

    /**
     * Load a language file
     *
     * @access    public
     * @param    mixed    the name of the language file to be loaded. Can be an array
     * @param    string    the language (english, etc.)
     * @return    void
     */
    function load($langfile = '', $idiom = '', $return = FALSE)
    {
        $langfile = str_replace(EXT, '', str_replace('_lang.', '', $langfile)).'_lang'.EXT;

        if (in_array($langfile, $this->is_loaded, TRUE))
        {
            return;
        }

        // {{{ Matchbox

        $ci     = &get_instance();
        $module = $ci->matchbox->argument(3);

        if ($idiom == '') {
            $deft_lang = $ci->config->item('language');
            $idiom = ($deft_lang == '') ? 'english' : $deft_lang;
        }

        if (!$filepath = $ci->matchbox->find('language/' . $idiom . '/' . $langfile, $module, 2)) {
            show_error('Unable to load the requested language file: language/' . $langfile);
        }

        include($filepath);

        // }}}

        if ( ! isset($lang))
        {
            log_message('error', 'Language file contains no data: language/'.$idiom.'/'.$langfile);
            return;
        }

        if ($return == TRUE)
        {
            return $lang;
        }

        $this->is_loaded[] = $langfile;
        $this->language = array_merge($this->language, $lang);
        unset($lang);

        log_message('debug', 'Language file loaded: language/'.$idiom.'/'.$langfile);
        return TRUE;
    }

}

?>