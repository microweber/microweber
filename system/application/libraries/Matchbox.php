<?php

/**
 * Matchbox class
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
 * @version   $Id: Matchbox.php 205 2008-02-24 01:43:55Z zacharias@dynaknudsen.dk $
 */

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Matchbox Version
 */
define('MATCHBOX_VERSION', '0.9.3');

/**
 * Provides modular functionality
 *
 * @package   Matchbox
 * @copyright 2007-2008 Zacharias Knudsen
 * @license   http://www.gnu.org/licenses/gpl.html
 */
class CI_Matchbox
{

    /**
     * The files that should be excluded from the caller search
     *
     * @var    array
     * @access private
     */
    var $_callers = array('Loader', 'Matchbox', 'MY_Config', 'MY_Language', 'Parser');

    /**
     * The directories that contain modules
     *
     * @var    array
     * @access public
     */
    var $_directories = array('modules');

    /**
     * The directory in which the current module is located
     *
     * @var    string
     * @access private
     */
    var $_directory = '';

    /**
     * The module in which the current controller is located
     *
     * @var    string
     * @access private
     */
    var $_module = '';

    /**
     * Fetches configuration file
     *
     * @return void
     * @access public
     */
    function CI_Matchbox()
    {
        @include(APPPATH . 'config/matchbox' . EXT);

        if (isset($config)) {
            $this->_callers     = array_merge($this->_callers, (!is_array($config['callers']) ? array() : $config['callers']));
            $this->_directories = (!is_array($config['directories'])) ? $this->_directories : $config['directories'];
        } else {
            log_message('error', 'Matchbox Config File Not Found');
        }

        log_message('debug', 'Matchbox Class Initialized');
    }

    /**
     * Locates resources
     *
     * @param  string
     * @param  string
     * @param  string
     * @param  string
     * $param  integer
     * @return mixed
     * @access public
     */
    function find($resource, $module = '', $search = 1)
    {
        log_message('debug', '---Matchbox---');
        log_message('debug', 'Finding: ' . $resource);

        $directories = array();

        if ($module !== '') {
            foreach ($this->directory_array() as $directory) {
                $directories[] = APPPATH . $directory . '/' . $module . '/';
            }
        } else {
            $caller = $this->detect_caller();

            foreach ($this->directory_array() as $directory) {
                $directories[] = APPPATH . $directory . '/' . $caller . '/';
            }

            if ($search == 3) {
                $directories[] = '';
            } else {
                $directories[] = APPPATH;

                if ($search == 2) {
                    $directories[] = BASEPATH;
                }
            }
        }

        foreach ($directories as $directory) {
            $filepath = $directory . $resource;
//var_dump($resource);
            log_message('debug', 'Looking in: ' . $filepath);

            if (file_exists($filepath)) {
                log_message('debug', 'Found');
                log_message('debug', '--------------');

                return $filepath;
            }
            
        if (file_exists($resource)) {
                log_message('debug', 'Found');
                log_message('debug', '--------------');

                return $resource;
            }
        }

        log_message('debug', 'Not found');
        log_message('debug', '--------------');

        return false;
    }

    /**
     * Detects calling module
     *
     * @return string
     * @access public
     */
    function detect_caller()
    {
        $callers     = array();
        $directories = array();
        $traces      = debug_backtrace();

        foreach ($this->caller_array() as $caller) {
            $callers[] = $this->_swap_separators($caller, true);
        }

        $search = '/(?:' . implode('|', $callers) . ')' . EXT . '$/i';

        foreach ($traces as $trace) {
            $filepath = $this->_swap_separators($trace['file']);

            if (!preg_match($search, $filepath)) {
                break;
            }
        }

        foreach ($this->directory_array() as $directory) {
            $directories[] = $this->_swap_separators(realpath(APPPATH . $directory), true);
        }

        $search = '/^(?:' . implode('|', $directories) . ')\/(.+?)\//i';

        if (preg_match($search, $filepath, $matches)) {
            log_message('debug', 'Calling module: ' . $matches[1]);

            return $matches[1];
        }

        log_message('debug', 'No valid caller');

        return '';
    }

    /**
     * Returns the nth argument
     *
     * @access public
     */
    function argument($argument)
    {
        $traces = debug_backtrace();

        if (isset($traces[1]['args'][$argument])) {
            return $traces[1]['args'][$argument];
        }

        return '';
    }

    /**
     * Returns an array of callers
     *
     * @access public
     */
    function caller_array()
    {
        return $this->_callers;
    }

    /**
     * Returns an array of module directories
     *
     * @access public
     */
    function directory_array()
    {
        return $this->_directories;
    }

    /**
     * Sets the directory
     *
     * @return string
     * @access public
     */
    function set_directory($directory)
    {
        $this->_directory = $directory . '/';
    }

    /**
     * Fetches the current directory (if any)
     *
     * @return string
     * @access public
     */
    function fetch_directory()
    {
        return $this->_directory;
    }

    /**
     * Sets the module
     *
     * @return string
     * @access public
     */
    function set_module($module)
    {
        $this->_module = $module . '/';
    }

    /**
     * Fetches the current module (if any)
     *
     * @return string
     * @access public
     */
    function fetch_module()
    {
        return $this->_module;
    }

    /**
     * Swaps directory separators to Unix style for consistency
     *
     * @return string
     * @access private
     */
    function _swap_separators($path, $search = false)
    {
        $path = strtr($path, '\\', '/');

        if ($search) {
            $path = str_replace(array('/', '|'), array('\/', '\|'), $path);
        }

        return $path;
    }

}

?>