<?php

/**
 * Matchbox Config class
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
 * @version   $Id: MY_Config.php 204 2008-02-24 01:30:00Z zacharias@dynaknudsen.dk $
 */

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Extends the CodeIgniter Config class
 *
 * All code not encapsulated in {{{ Matchbox }}} was made by EllisLab
 *
 * @package   Matchbox
 * @copyright 2007-2008 Zacharias Knudsen
 * @license   http://www.gnu.org/licenses/gpl.html
 */
class MY_Config extends CI_Config
{
    // {{{ Matchbox

    /**
     * Loads config file from module
     *
     * @param  string
     * @param  string
     * @param  bool
     * @param  bool
     * @return boolean
     * @access public
     */
    function module_load($module, $file = '', $use_sections = false, $fail_gracefully = false)
    {
        return $this->load($file, $use_sections, $fail_gracefully, $module);
    }

    // }}}


    /**
     * Load Config File
     *
     * @access    public
     * @param    string    the config file name
     * @return    boolean    if the file was loaded correctly
     */
    function load($file = '', $use_sections = FALSE, $fail_gracefully = FALSE)
    {
        $file = ($file == '') ? 'config' : str_replace(EXT, '', $file);

        if (in_array($file, $this->is_loaded, TRUE))
        {
            return TRUE;
        }

        // {{{ Matchbox

        $ci     = &get_instance();
        $module = $ci->matchbox->argument(3);

        if (!$filepath = $ci->matchbox->find('config/' . $file . EXT, $module)) {
            if ($fail_gracefully === true) {
                return false;
            }

            show_error('The configuration file ' . $file . EXT . ' does not exist.');
        }

        include($filepath);

        // }}}

        if ( ! isset($config) OR ! is_array($config))
        {
            if ($fail_gracefully === TRUE)
            {
                return FALSE;
            }
            show_error('Your '.$file.EXT.' file does not appear to contain a valid configuration array.');
        }

        if ($use_sections === TRUE)
        {
            if (isset($this->config[$file]))
            {
                $this->config[$file] = array_merge($this->config[$file], $config);
            }
            else
            {
                $this->config[$file] = $config;
            }
        }
        else
        {
            $this->config = array_merge($this->config, $config);
        }

        $this->is_loaded[] = $file;
        unset($config);

        log_message('debug', 'Config file loaded: config/'.$file.EXT);
        return TRUE;
    }

}

?>