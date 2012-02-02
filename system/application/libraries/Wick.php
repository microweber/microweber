<?php

/**
 * Wick Library
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package   Incendiary
 * @copyright 2007-2008 Zacharias Knudsen
 * @license   http://www.gnu.org/licenses/lgpl.html
 * @version   $Id: Wick.php 32 2008-08-22 15:13:43Z zdknudsen $
 */

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Provides a method for loading uris
 *
 * @package   Incendiary
 * @copyright 2007-2008 Zacharias Knudsen
 * @license   http://www.gnu.org/licenses/lgpl.html
 */
class Wick
{

    /**
     * Queue of running controllers
     *
     * @var    array
     * @access private
     */
    var $_queue = array();

    /**
     * Registry of loaded controllers
     *
     * @var    array
     * @access private
     */
    var $_registry = array();

    /**
     * Registrates current instance
     *
     * @return void
     * @access public
     */
    function Wick()
    {
        $ci                      = get_instance();
        $class                   = strtolower(get_class($ci));
        $this->_registry[$class] = &$ci;
        $this->_queue[]          = &$this->_registry[$class];

        // Logs initialization message for debugging
        log_message('debug', 'Wick Class Initialized');
    }

    /**
     * Lights the wick of a given uri
     *
     * @param  mixed
     * @return void
     * @access public
     */
    function light($segments = array())
    {
        // Arrayify whatever needs arrayifying
        if (!is_array($segments)) {
            $segments = explode('/', $segments);
        }

        // We need stock libraries for native routing
        $router = &load_class('Router');
        $uri    = &load_class('URI');

        $router->_set_request($segments);

        // Setting a few variables for later (and immediate) use
        $class     = $router->fetch_class();
        $directory = $router->fetch_directory();
        $method    = $router->fetch_method();
        $path      = APPPATH . 'controllers/' . $directory . $class . EXT;

        // No need to load the file if it's already in memory
        if (!class_exists($class)) {
            include_once($path);
        }

        // I disapprove!
        if (!class_exists($class) || $method == 'controller' || strncmp($method, '_', 1) == 0 || in_array($method, get_class_methods('Controller'), true)) {
            show_error('Wick could not light ' . $class . '/' . $method . ', either the class does not exist or the method is protected.');
        }

        // Fetches controller from registry or creates new one
        if (isset($this->_registry[$class])) {
            $controller = &$this->_registry[$class];
        } else {
            $controller              = &new $class();
            $this->_registry[$class] = &$controller;
        }
        $this->_queue[] = &$controller;

        // Throws error if method doesn't exist
        if (!in_array(strtolower($method), array_map('strtolower', get_class_methods($controller)))) {
            show_error('Wick could not light "' . $class . '/' . $method . '". The method doesn\'t exist.');
        }

        // Tranfers data into the controller, calls it
        // and transfers data back into the previous one
        $ci     = &$this->_transfer($controller);
        $return = call_user_func_array(array(&$ci, $method), array_slice($uri->rsegments, 2));
        $this->_transfer(&$this->_queue[count($this->_queue)-1]);

        // Pops the controller off the queue
        array_pop($this->_queue);

        // Returns whatever the mighty controller fancies
        return $return;
    }

    /**
     * Transfers variables from one object to another
     *
     * @param  object
     * @param  object
     * @return void
     * @access private
     */
    function &_transfer(&$to)
    {
        $ci = &get_instance();
        foreach (array_keys(get_object_vars($ci)) as $variable) {
            $to->$variable = &$ci->$variable;
        }

        $ci = $to;

        return $ci;
    }

}

/* End of file Wick.php */
/* Location: ./system/application/libraries/Wick.php */