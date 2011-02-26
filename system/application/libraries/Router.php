<?php

/**
 * Matchbox Router class
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
 * @version   $Id: Router.php 204 2008-02-24 01:30:00Z zacharias@dynaknudsen.dk $
 */

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
 * Replaces the CodeIgniter Router class
 *
 * All code not encapsulated in {{{ Matchbox }}} was made by EllisLab
 *
 * @package   Matchbox
 * @copyright 2007-2008 Zacharias Knudsen
 * @license   http://www.gnu.org/licenses/gpl.html
 */
class CI_Router
{
    // {{{ Matchbox

    /**
     * Number of times fetch_directory() has been called
     *
     * @var    bool
     * @access private
     */
    var $_called = 0;

    /**
     * Whether segment validation should override 404 errors
     *
     * @var    bool
     * @access private
     */
    var $_fail_gracefully = false;

    /**
     * The Matchbox object
     *
     * @var    object
     * @access private
     */
    var $_matchbox;

    // }}}

    var $config;
    var $routes         = array();
    var $error_routes    = array();
    var $class            = '';
    var $method            = 'index';
    var $directory        = '';
    var $uri_protocol     = 'auto';
    var $default_controller;
    var $scaffolding_request = FALSE; // Must be set to FALSE

    /**
     * Constructor
     *
     * Runs the route mapping function.
     */
    function CI_Router()
    {
        // {{{ Matchbox

        $this->_matchbox = &load_class('Matchbox');

        // }}}

        $this->config =& load_class('Config');
        $this->uri =& load_class('URI');
        $this->_set_routing();
        log_message('debug', "Router Class Initialized");
    }

    // --------------------------------------------------------------------

    /**
     * Set the route mapping
     *
     * This function determines what should be served based on the URI request,
     * as well as any "routes" that have been set in the routing config file.
     *
     * @access    private
     * @return    void
     */
    function _set_routing()
    {
        // Are query strings enabled in the config file?
        // If so, we're done since segment based URIs are not used with query strings.
        if ($this->config->item('enable_query_strings') === TRUE AND isset($_GET[$this->config->item('controller_trigger')]))
        {
            $this->set_class(trim($this->uri->_filter_uri($_GET[$this->config->item('controller_trigger')])));

            if (isset($_GET[$this->config->item('function_trigger')]))
            {
                $this->set_method(trim($this->uri->_filter_uri($_GET[$this->config->item('function_trigger')])));
            }

            return;
        }

        // Load the routes.php file.
        @include(APPPATH.'config/routes'.EXT);
        $this->routes = ( ! isset($route) OR ! is_array($route)) ? array() : $route;
        unset($route);

        // Set the default controller so we can display it in the event
        // the URI doesn't correlated to a valid controller.
        $this->default_controller = ( ! isset($this->routes['default_controller']) OR $this->routes['default_controller'] == '') ? FALSE : strtolower($this->routes['default_controller']);

        // Fetch the complete URI string
        $this->uri->_fetch_uri_string();

        // Is there a URI string? If not, the default controller specified in the "routes" file will be shown.
        if ($this->uri->uri_string == '')
        {
            if ($this->default_controller === FALSE)
            {
                show_error("Unable to determine what should be displayed. A default route has not been specified in the routing file.");
            }

            // {{{ Matchbox

            $segments = explode('/', $this->default_controller);

            $this->_fail_gracefully = true;

            $this->_set_request($segments);
            $this->uri->_reindex_segments();

            // }}}

            log_message('debug', "No URI present. Default controller set.");
            return;
        }
        unset($this->routes['default_controller']);

        // Do we need to remove the URL suffix?
        $this->uri->_remove_url_suffix();

        // Compile the segments into an array
        $this->uri->_explode_segments();

        // Parse any custom routing that may exist
        $this->_parse_routes();

        // Re-index the segment array so that it starts with 1 rather than 0
        $this->uri->_reindex_segments();
    }

    // --------------------------------------------------------------------

    /**
     * Set the Route
     *
     * This function takes an array of URI segments as
     * input, and sets the current class/method
     *
     * @access    private
     * @param    array
     * @param    bool
     * @return    void
     */
    function _set_request($segments = array())
    {
        $segments = $this->_validate_request($segments);

        if (count($segments) == 0)
        {
            return;
        }

        $this->set_class($segments[0]);

        if (isset($segments[1]))
        {
            // A scaffolding request. No funny business with the URL
            if ($this->routes['scaffolding_trigger'] == $segments[1] AND $segments[1] != '_ci_scaffolding')
            {
                $this->scaffolding_request = TRUE;
                unset($this->routes['scaffolding_trigger']);
            }
            else
            {
                // A standard method request
                $this->set_method($segments[1]);
            }
        }
        else
        {
            // This lets the "routed" segment array identify that the default
            // index method is being used.
            $segments[1] = 'index';
        }

        // Update our "routed" segment array to contain the segments.
        // Note: If there is no custom routing, this array will be
        // identical to $this->uri->segments
        $this->uri->rsegments = $segments;
    }

    // --------------------------------------------------------------------

    /**
     * Validates the supplied segments.  Attempts to determine the path to
     * the controller.
     *
     * @access    private
     * @param    array
     * @return    array
     */
    function _validate_request($segments)
    {
        // {{{ Matchbox

        foreach($this->_matchbox->directory_array() as $directory) {
            if (count($segments) > 1 && $segments[0] !== $segments[1] && file_exists(APPPATH . $directory . '/' . $segments[0] . '/controllers/' . $segments[1] . EXT)) {
                $this->_matchbox->set_directory($directory);
                $this->_matchbox->set_module($segments[0]);

                $segments = array_slice($segments, 1);

                return $segments;
            }

            if (count($segments) > 2 && file_exists(APPPATH . $directory . '/' . $segments[0] . '/controllers/' . $segments[1] . '/' . $segments[2] . EXT)) {
                $this->_matchbox->set_directory($directory);
                $this->_matchbox->set_module($segments[0]);
                $this->set_directory($segments[1]);

                $segments = array_slice($segments, 2);

                return $segments;
            }

            if (count($segments) > 1 && file_exists(APPPATH . $directory . '/' . $segments[0] . '/controllers/' . $segments[1] . '/' . $segments[1] . EXT)) {
                $this->_matchbox->set_directory($directory);
                $this->_matchbox->set_module($segments[0]);
                $this->set_directory($segments[1]);

                $segments = array_slice($segments, 1);

                return $segments;
            }

            if (file_exists(APPPATH . $directory . '/' . $segments[0] . '/controllers/' . $segments[0] . EXT)) {
                $this->_matchbox->set_directory($directory);
                $this->_matchbox->set_module($segments[0]);

                return $segments;
            }
        }

        // }}}

        // Does the requested controller exist in the root folder?
        if (file_exists(APPPATH.'controllers/'.$segments[0].EXT))
        {
            return $segments;
        }

        // Is the controller in a sub-folder?
        if (is_dir(APPPATH.'controllers/'.$segments[0]))
        {
            // Set the directory and remove it from the segment array
            $this->set_directory($segments[0]);
            $segments = array_slice($segments, 1);

            if (count($segments) > 0)
            {
                // Does the requested controller exist in the sub-folder?
                if ( ! file_exists(APPPATH.'controllers/'.$this->fetch_directory().$segments[0].EXT))
                {
                    show_404();
                }
            }
            else
            {
                $this->set_class($this->default_controller);
                $this->set_method('index');

                // Does the default controller exist in the sub-folder?
                if ( ! file_exists(APPPATH.'controllers/'.$this->fetch_directory().$this->default_controller.EXT))
                {
                    $this->directory = '';
                    return array();
                }

            }

            return $segments;
        }

        // {{{ Matchbox

        if ($this->_fail_gracefully) {
            return array();
        }

        // }}}

        // Can't find the requested controller...
        show_404();
    }

    // --------------------------------------------------------------------

    /**
     *  Parse Routes
     *
     * This function matches any routes that may exist in
     * the config/routes.php file against the URI to
     * determine if the class/method need to be remapped.
     *
     * @access    private
     * @return    void
     */
    function _parse_routes()
    {
        // Do we even have any custom routing to deal with?
        // There is a default scaffolding trigger, so we'll look just for 1
        if (count($this->routes) == 1)
        {
            $this->_set_request($this->uri->segments);
            return;
        }

        // Turn the segment array into a URI string
        $uri = implode('/', $this->uri->segments);
        $num = count($this->uri->segments);

        // Is there a literal match?  If so we're done
        if (isset($this->routes[$uri]))
        {
            $this->_set_request(explode('/', $this->routes[$uri]));
            return;
        }

        // Loop through the route array looking for wild-cards
        foreach ($this->routes as $key => $val)
        {
            // Convert wild-cards to RegEx
            $key = str_replace(':any', '.+', str_replace(':num', '[0-9]+', $key));

            // Does the RegEx match?
            if (preg_match('#^'.$key.'$#', $uri))
            {
                // Do we have a back-reference?
                if (strpos($val, '$') !== FALSE AND strpos($key, '(') !== FALSE)
                {
                    $val = preg_replace('#^'.$key.'$#', $val, $uri);
                }

                $this->_set_request(explode('/', $val));
                return;
            }
        }

        // If we got this far it means we didn't encounter a
        // matching route so we'll set the site default route
        $this->_set_request($this->uri->segments);
    }

    // --------------------------------------------------------------------

    /**
     * Set the class name
     *
     * @access    public
     * @param    string
     * @return    void
     */
    function set_class($class)
    {
        $this->class = $class;
    }

    // --------------------------------------------------------------------

    /**
     * Fetch the current class
     *
     * @access    public
     * @return    string
     */
    function fetch_class()
    {
        return $this->class;
    }

    // --------------------------------------------------------------------

    /**
     *  Set the method name
     *
     * @access    public
     * @param    string
     * @return    void
     */
    function set_method($method)
    {
        $this->method = $method;
    }

    // --------------------------------------------------------------------

    /**
     *  Fetch the current method
     *
     * @access    public
     * @return    string
     */
    function fetch_method()
    {
        if ($this->method == $this->fetch_class())
        {
            return 'index';
        }

        return $this->method;
    }

    // --------------------------------------------------------------------

    /**
     *  Set the directory name
     *
     * @access    public
     * @param    string
     * @return    void
     */
    function set_directory($dir)
    {
        $this->directory = $dir.'/';
    }

    // --------------------------------------------------------------------

    /**
     *  Fetch the sub-directory (if any) that contains the requested controller class
     *
     * @access    public
     * @return    string
     */
    function fetch_directory()
    {
        // {{{ Matchbox

        if ($this->_called < 2) {
            $this->_called += 1;

            return '../' . $this->_matchbox->fetch_directory() . $this->_matchbox->fetch_module() . 'controllers/' . $this->directory;
        }

        // }}}

        return $this->directory;
    }

}

?>