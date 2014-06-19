<?php

    /**
     *
     * Idiorm
     *
     * http://github.com/j4mie/idiorm/
     *
     * A single-class super-simple database abstraction layer for PHP.
     * Provides (nearly) zero-configuration object-relational mapping
     * and a fluent interface for building basic, commonly-used queries.
     *
     * BSD Licensed.
     *
     * Copyright (c) 2010, Jamie Matthews
     * All rights reserved.
     *
     * Redistribution and use in source and binary forms, with or without
     * modification, are permitted provided that the following conditions are met:
     *
     * * Redistributions of source code must retain the above copyright notice, this
     *   list of conditions and the following disclaimer.
     *
     * * Redistributions in binary form must reproduce the above copyright notice,
     *   this list of conditions and the following disclaimer in the documentation
     *   and/or other materials provided with the distribution.
     *
     * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS"
     * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE
     * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE
     * DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE
     * FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL
     * DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR
     * SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER
     * CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY,
     * OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
     * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
     *
     */

    class ORM implements ArrayAccess {

        // ----------------------- //
        // --- CLASS CONSTANTS --- //
        // ----------------------- //

        // WHERE and HAVING condition array keys
        const CONDITION_FRAGMENT = 0;
        const CONDITION_VALUES = 1;

        const DEFAULT_CONNECTION = 'default';

        // Limit clause style
        const LIMIT_STYLE_TOP_N = "top";
        const LIMIT_STYLE_LIMIT = "limit";

        // ------------------------ //
        // --- CLASS PROPERTIES --- //
        // ------------------------ //

        // Class configuration
        protected static $_default_config = array(
            'connection_string' => 'sqlite::memory:',
            'id_column' => 'id',
            'id_column_overrides' => array(),
            'error_mode' => PDO::ERRMODE_EXCEPTION,
            'username' => null,
            'password' => null,
            'driver_options' => null,
            'identifier_quote_character' => null, // if this is null, will be autodetected
            'limit_clause_style' => null, // if this is null, will be autodetected
            'logging' => false,
            'logger' => null,
            'caching' => false,
            'return_result_sets' => false,
        );

        // Map of configuration settings
        protected static $_config = array();

        // Map of database connections, instances of the PDO class
        protected static $_db = array();

        // Last query run, only populated if logging is enabled
        protected static $_last_query;

        // Log of all queries run, mapped by connection key, only populated if logging is enabled
        protected static $_query_log = array();

        // Query cache, only used if query caching is enabled
        protected static $_query_cache = array();

        // Reference to previously used PDOStatement object to enable low-level access, if needed
        protected static $_last_statement = null;

        // --------------------------- //
        // --- INSTANCE PROPERTIES --- //
        // --------------------------- //

        // Key name of the connections in self::$_db used by this instance
        protected $_connection_name;

        // The name of the table the current ORM instance is associated with
        protected $_table_name;

        // Alias for the table to be used in SELECT queries
        protected $_table_alias = null;

        // Values to be bound to the query
        protected $_values = array();

        // Columns to select in the result
        protected $_result_columns = array('*');

        // Are we using the default result column or have these been manually changed?
        protected $_using_default_result_columns = true;

        // Join sources
        protected $_join_sources = array();

        // Should the query include a DISTINCT keyword?
        protected $_distinct = false;

        // Is this a raw query?
        protected $_is_raw_query = false;

        // The raw query
        protected $_raw_query = '';

        // The raw query parameters
        protected $_raw_parameters = array();

        // Array of WHERE clauses
        protected $_where_conditions = array();

        // LIMIT
        protected $_limit = null;

        // OFFSET
        protected $_offset = null;

        // ORDER BY
        protected $_order_by = array();

        // GROUP BY
        protected $_group_by = array();

        // HAVING
        protected $_having_conditions = array();

        // The data for a hydrated instance of the class
        protected $_data = array();

        // Fields that have been modified during the
        // lifetime of the object
        protected $_dirty_fields = array();

        // Fields that are to be inserted in the DB raw
        protected $_expr_fields = array();

        // Is this a new object (has create() been called)?
        protected $_is_new = false;

        // Name of the column to use as the primary key for
        // this instance only. Overrides the config settings.
        protected $_instance_id_column = null;

        // ---------------------- //
        // --- STATIC METHODS --- //
        // ---------------------- //

        /**
         * Pass configuration settings to the class in the form of
         * key/value pairs. As a shortcut, if the second argument
         * is omitted and the key is a string, the setting is
         * assumed to be the DSN string used by PDO to connect
         * to the database (often, this will be the only configuration
         * required to use Idiorm). If you have more than one setting
         * you wish to configure, another shortcut is to pass an array
         * of settings (and omit the second argument).
         * @param string $key
         * @param mixed $value
         * @param string $connection_name Which connection to use
         */
        public static function configure($key, $value = null, $connection_name = self::DEFAULT_CONNECTION) {
            self::_setup_db_config($connection_name); //ensures at least default config is set

            if (is_array($key)) {
                // Shortcut: If only one array argument is passed,
                // assume it's an array of configuration settings
                foreach ($key as $conf_key => $conf_value) {
                    self::configure($conf_key, $conf_value, $connection_name);
                }
            } else {
                if (is_null($value)) {
                    // Shortcut: If only one string argument is passed, 
                    // assume it's a connection string
                    $value = $key;
                    $key = 'connection_string';
                }
                self::$_config[$connection_name][$key] = $value;
            }
        }

        /**
         * Retrieve configuration options by key, or as whole array.
         * @param string $key
         * @param string $connection_name Which connection to use
         */
        public static function get_config($key = null, $connection_name = self::DEFAULT_CONNECTION) {
            if ($key) {
                return self::$_config[$connection_name][$key];
            } else {
                return self::$_config[$connection_name];
            }
        }

        /**
         * Delete all configs in _config array.
         */
        public static function reset_config() {
            self::$_config = array();
        }
        
        /**
         * Despite its slightly odd name, this is actually the factory
         * method used to acquire instances of the class. It is named
         * this way for the sake of a readable interface, ie
         * ORM::for_table('table_name')->find_one()-> etc. As such,
         * this will normally be the first method called in a chain.
         * @param string $table_name
         * @param string $connection_name Which connection to use
         * @return ORM
         */
        public static function for_table($table_name, $connection_name = self::DEFAULT_CONNECTION) {
            self::_setup_db($connection_name);
            return new self($table_name, array(), $connection_name);
        }

        /**
         * Set up the database connection used by the class
         * @param string $connection_name Which connection to use
         */
        protected static function _setup_db($connection_name = self::DEFAULT_CONNECTION) {
            if (!array_key_exists($connection_name, self::$_db) ||
                !is_object(self::$_db[$connection_name])) {
                self::_setup_db_config($connection_name);

                $db = new PDO(
                    self::$_config[$connection_name]['connection_string'],
                    self::$_config[$connection_name]['username'],
                    self::$_config[$connection_name]['password'],
                    self::$_config[$connection_name]['driver_options']
                );

                $db->setAttribute(PDO::ATTR_ERRMODE, self::$_config[$connection_name]['error_mode']);
                self::set_db($db, $connection_name);
            }
        }

       /**
        * Ensures configuration (mulitple connections) is at least set to default.
        * @param string $connection_name Which connection to use
        */
        protected static function _setup_db_config($connection_name) {
            if (!array_key_exists($connection_name, self::$_config)) {
                self::$_config[$connection_name] = self::$_default_config;
            }
        }

        /**
         * Set the PDO object used by Idiorm to communicate with the database.
         * This is public in case the ORM should use a ready-instantiated
         * PDO object as its database connection. Accepts an optional string key
         * to identify the connection if multiple connections are used.
         * @param PDO $db
         * @param string $connection_name Which connection to use
         */
        public static function set_db($db, $connection_name = self::DEFAULT_CONNECTION) {
            self::_setup_db_config($connection_name);
            self::$_db[$connection_name] = $db;
            self::_setup_identifier_quote_character($connection_name);
            self::_setup_limit_clause_style($connection_name);
        }

        /**
         * Delete all registered PDO objects in _db array.
         */
        public static function reset_db() {
            self::$_db = array();
        }

        /**
         * Detect and initialise the character used to quote identifiers
         * (table names, column names etc). If this has been specified
         * manually using ORM::configure('identifier_quote_character', 'some-char'),
         * this will do nothing.
         * @param string $connection_name Which connection to use
         */
        protected static function _setup_identifier_quote_character($connection_name) {
            if (is_null(self::$_config[$connection_name]['identifier_quote_character'])) {
                self::$_config[$connection_name]['identifier_quote_character'] =
                    self::_detect_identifier_quote_character($connection_name);
            }
        }

        /**
         * Detect and initialise the limit clause style ("SELECT TOP 5" /
         * "... LIMIT 5"). If this has been specified manually using 
         * ORM::configure('limit_clause_style', 'top'), this will do nothing.
         * @param string $connection_name Which connection to use
         */
        public static function _setup_limit_clause_style($connection_name) {
            if (is_null(self::$_config[$connection_name]['limit_clause_style'])) {
                self::$_config[$connection_name]['limit_clause_style'] =
                    self::_detect_limit_clause_style($connection_name);
            }
        }

        /**
         * Return the correct character used to quote identifiers (table
         * names, column names etc) by looking at the driver being used by PDO.
         * @param string $connection_name Which connection to use
         * @return string
         */
        protected static function _detect_identifier_quote_character($connection_name) {
            switch(self::$_db[$connection_name]->getAttribute(PDO::ATTR_DRIVER_NAME)) {
                case 'pgsql':
                case 'sqlsrv':
                case 'dblib':
                case 'mssql':
                case 'sybase':
                case 'firebird':
                    return '"';
                case 'mysql':
                case 'sqlite':
                case 'sqlite2':
                default:
                    return '`';
            }
        }

        /**
         * Returns a constant after determining the appropriate limit clause
         * style
         * @param string $connection_name Which connection to use
         * @return string Limit clause style keyword/constant
         */
        protected static function _detect_limit_clause_style($connection_name) {
            switch(self::$_db[$connection_name]->getAttribute(PDO::ATTR_DRIVER_NAME)) {
                case 'sqlsrv':
                case 'dblib':
                case 'mssql':
                    return ORM::LIMIT_STYLE_TOP_N;
                default:
                    return ORM::LIMIT_STYLE_LIMIT;
            }
        }

        /**
         * Returns the PDO instance used by the the ORM to communicate with
         * the database. This can be called if any low-level DB access is
         * required outside the class. If multiple connections are used,
         * accepts an optional key name for the connection.
         * @param string $connection_name Which connection to use
         * @return PDO
         */
        public static function get_db($connection_name = self::DEFAULT_CONNECTION) {
            self::_setup_db($connection_name); // required in case this is called before Idiorm is instantiated
            return self::$_db[$connection_name];
        }

        /**
         * Executes a raw query as a wrapper for PDOStatement::execute.
         * Useful for queries that can't be accomplished through Idiorm,
         * particularly those using engine-specific features.
         * @example raw_execute('SELECT `name`, AVG(`order`) FROM `customer` GROUP BY `name` HAVING AVG(`order`) > 10')
         * @example raw_execute('INSERT OR REPLACE INTO `widget` (`id`, `name`) SELECT `id`, `name` FROM `other_table`')
         * @param string $query The raw SQL query
         * @param array  $parameters Optional bound parameters
         * @param string $connection_name Which connection to use
         * @return bool Success
         */
        public static function raw_execute($query, $parameters = array(), $connection_name = self::DEFAULT_CONNECTION) {
            self::_setup_db($connection_name);
            return self::_execute($query, $parameters, $connection_name);
        }

        /**
         * Returns the PDOStatement instance last used by any connection wrapped by the ORM.
         * Useful for access to PDOStatement::rowCount() or error information
         * @return PDOStatement
         */
        public static function get_last_statement() {
            return self::$_last_statement;
        }

       /**
        * Internal helper method for executing statments. Logs queries, and
        * stores statement object in ::_last_statment, accessible publicly
        * through ::get_last_statement()
        * @param string $query
        * @param array $parameters An array of parameters to be bound in to the query
        * @param string $connection_name Which connection to use
        * @return bool Response of PDOStatement::execute()
        */
        protected static function _execute($query, $parameters = array(), $connection_name = self::DEFAULT_CONNECTION) {
            self::_log_query($query, $parameters, $connection_name);
            $statement = self::$_db[$connection_name]->prepare($query);

            self::$_last_statement = $statement;

            return $statement->execute($parameters);
        }

        /**
         * Add a query to the internal query log. Only works if the
         * 'logging' config option is set to true.
         *
         * This works by manually binding the parameters to the query - the
         * query isn't executed like this (PDO normally passes the query and
         * parameters to the database which takes care of the binding) but
         * doing it this way makes the logged queries more readable.
         * @param string $query
         * @param array $parameters An array of parameters to be bound in to the query
         * @param string $connection_name Which connection to use
         * @return bool
         */
        protected static function _log_query($query, $parameters, $connection_name) {
            // If logging is not enabled, do nothing
            if (!self::$_config[$connection_name]['logging']) {
                return false;
            }

            if (!isset(self::$_query_log[$connection_name])) {
                self::$_query_log[$connection_name] = array();
            }

            if (count($parameters) > 0) {
                // Escape the parameters
                $parameters = array_map(array(self::$_db[$connection_name], 'quote'), $parameters);

                // Avoid %format collision for vsprintf
                $query = str_replace("%", "%%", $query);

                // Replace placeholders in the query for vsprintf
                if(false !== strpos($query, "'") || false !== strpos($query, '"')) {
                    $query = IdiormString::str_replace_outside_quotes("?", "%s", $query);
                } else {
                    $query = str_replace("?", "%s", $query);
                }

                // Replace the question marks in the query with the parameters
                $bound_query = vsprintf($query, $parameters);
            } else {
                $bound_query = $query;
            }

            self::$_last_query = $bound_query;
            self::$_query_log[$connection_name][] = $bound_query;
            
            
            if(is_callable(self::$_config[$connection_name]['logger'])){
                $logger = self::$_config[$connection_name]['logger'];
                $logger($bound_query);
            }
            
            return true;
        }

        /**
         * Get the last query executed. Only works if the
         * 'logging' config option is set to true. Otherwise
         * this will return null. Returns last query from all connections if
         * no connection_name is specified
         * @param null|string $connection_name Which connection to use
         * @return string
         */
        public static function get_last_query($connection_name = null) {
            if ($connection_name === null) {
                return self::$_last_query;
            }
            if (!isset(self::$_query_log[$connection_name])) {
                return '';
            }

            return end(self::$_query_log[$connection_name]);
        }

        /**
         * Get an array containing all the queries run on a
         * specified connection up to now.
         * Only works if the 'logging' config option is
         * set to true. Otherwise, returned array will be empty.
         * @param string $connection_name Which connection to use
         */
        public static function get_query_log($connection_name = self::DEFAULT_CONNECTION) {
            if (isset(self::$_query_log[$connection_name])) {
                return self::$_query_log[$connection_name];
            }
            return array();
        }

        /**
         * Get a list of the available connection names
         * @return array
         */
        public static function get_connection_names() {
            return array_keys(self::$_db);
        }

        // ------------------------ //
        // --- INSTANCE METHODS --- //
        // ------------------------ //

        /**
         * "Private" constructor; shouldn't be called directly.
         * Use the ORM::for_table factory method instead.
         */
        protected function __construct($table_name, $data = array(), $connection_name = self::DEFAULT_CONNECTION) {
            $this->_table_name = $table_name;
            $this->_data = $data;

            $this->_connection_name = $connection_name;
            self::_setup_db_config($connection_name);
        }

        /**
         * Create a new, empty instance of the class. Used
         * to add a new row to your database. May optionally
         * be passed an associative array of data to populate
         * the instance. If so, all fields will be flagged as
         * dirty so all will be saved to the database when
         * save() is called.
         */
        public function create($data=null) {
            $this->_is_new = true;
            if (!is_null($data)) {
                return $this->hydrate($data)->force_all_dirty();
            }
            return $this;
        }

        /**
         * Specify the ID column to use for this instance or array of instances only.
         * This overrides the id_column and id_column_overrides settings.
         *
         * This is mostly useful for libraries built on top of Idiorm, and will
         * not normally be used in manually built queries. If you don't know why
         * you would want to use this, you should probably just ignore it.
         */
        public function use_id_column($id_column) {
            $this->_instance_id_column = $id_column;
            return $this;
        }

        /**
         * Create an ORM instance from the given row (an associative
         * array of data fetched from the database)
         */
        protected function _create_instance_from_row($row) {
            $instance = self::for_table($this->_table_name, $this->_connection_name);
            $instance->use_id_column($this->_instance_id_column);
            $instance->hydrate($row);
            return $instance;
        }

        /**
         * Tell the ORM that you are expecting a single result
         * back from your query, and execute it. Will return
         * a single instance of the ORM class, or false if no
         * rows were returned.
         * As a shortcut, you may supply an ID as a parameter
         * to this method. This will perform a primary key
         * lookup on the table.
         */
        public function find_one($id=null) {
            if (!is_null($id)) {
                $this->where_id_is($id);
            }
            $this->limit(1);
            $rows = $this->_run();

            if (empty($rows)) {
                return false;
            }

            return $this->_create_instance_from_row($rows[0]);
        }

        /**
         * Tell the ORM that you are expecting multiple results
         * from your query, and execute it. Will return an array
         * of instances of the ORM class, or an empty array if
         * no rows were returned.
         * @return array|\IdiormResultSet
         */
        public function find_many() {
            if(self::$_config[$this->_connection_name]['return_result_sets']) {
                return $this->find_result_set();
            }
            return $this->_find_many();
        }

        /**
         * Tell the ORM that you are expecting multiple results
         * from your query, and execute it. Will return an array
         * of instances of the ORM class, or an empty array if
         * no rows were returned.
         * @return array
         */
        protected function _find_many() {
            $rows = $this->_run();
            return array_map(array($this, '_create_instance_from_row'), $rows);
        }

        /**
         * Tell the ORM that you are expecting multiple results
         * from your query, and execute it. Will return a result set object
         * containing instances of the ORM class.
         * @return \IdiormResultSet
         */
        public function find_result_set() {
            return new IdiormResultSet($this->_find_many());
        }

        /**
         * Tell the ORM that you are expecting multiple results
         * from your query, and execute it. Will return an array,
         * or an empty array if no rows were returned.
         * @return array
         */
        public function find_array() {
            return $this->_run(); 
        }

        /**
         * Tell the ORM that you wish to execute a COUNT query.
         * Will return an integer representing the number of
         * rows returned.
         */
        public function count($column = '*') {
            return $this->_call_aggregate_db_function(__FUNCTION__, $column);
        }

        /**
         * Tell the ORM that you wish to execute a MAX query.
         * Will return the max value of the choosen column.
         */
        public function max($column)  {
            return $this->_call_aggregate_db_function(__FUNCTION__, $column);
        }

        /**
         * Tell the ORM that you wish to execute a MIN query.
         * Will return the min value of the choosen column.
         */
        public function min($column)  {
            return $this->_call_aggregate_db_function(__FUNCTION__, $column);
        }

        /**
         * Tell the ORM that you wish to execute a AVG query.
         * Will return the average value of the choosen column.
         */
        public function avg($column)  {
            return $this->_call_aggregate_db_function(__FUNCTION__, $column);
        }

        /**
         * Tell the ORM that you wish to execute a SUM query.
         * Will return the sum of the choosen column.
         */
        public function sum($column)  {
            return $this->_call_aggregate_db_function(__FUNCTION__, $column);
        }

        /**
         * Execute an aggregate query on the current connection.
         * @param string $sql_function The aggregate function to call eg. MIN, COUNT, etc
         * @param string $column The column to execute the aggregate query against
         * @return int
         */
        protected function _call_aggregate_db_function($sql_function, $column) {
            $alias = strtolower($sql_function);
            $sql_function = strtoupper($sql_function);
            if('*' != $column) {
                $column = $this->_quote_identifier($column);
            }
            $result_columns = $this->_result_columns;
            $this->_result_columns = array();
            $this->select_expr("$sql_function($column)", $alias);
            $result = $this->find_one();
            $this->_result_columns = $result_columns;

            $return_value = 0;
            if($result !== false && isset($result->$alias)) {
                if((int) $result->$alias == (float) $result->$alias) {
                    $return_value = (int) $result->$alias;
                } else {
                    $return_value = (float) $result->$alias;
                }
            }
            return $return_value;
        }

         /**
         * This method can be called to hydrate (populate) this
         * instance of the class from an associative array of data.
         * This will usually be called only from inside the class,
         * but it's public in case you need to call it directly.
         */
        public function hydrate($data=array()) {
            $this->_data = $data;
            return $this;
        }

        /**
         * Force the ORM to flag all the fields in the $data array
         * as "dirty" and therefore update them when save() is called.
         */
        public function force_all_dirty() {
            $this->_dirty_fields = $this->_data;
            return $this;
        }

        /**
         * Perform a raw query. The query can contain placeholders in
         * either named or question mark style. If placeholders are
         * used, the parameters should be an array of values which will
         * be bound to the placeholders in the query. If this method
         * is called, all other query building methods will be ignored.
         */
        public function raw_query($query, $parameters = array()) {
            $this->_is_raw_query = true;
            $this->_raw_query = $query;
            $this->_raw_parameters = $parameters;
            return $this;
        }

        /**
         * Add an alias for the main table to be used in SELECT queries
         */
        public function table_alias($alias) {
            $this->_table_alias = $alias;
            return $this;
        }

        /**
         * Internal method to add an unquoted expression to the set
         * of columns returned by the SELECT query. The second optional
         * argument is the alias to return the expression as.
         */
        protected function _add_result_column($expr, $alias=null) {
            if (!is_null($alias)) {
                $expr .= " AS " . $this->_quote_identifier($alias);
            }

            if ($this->_using_default_result_columns) {
                $this->_result_columns = array($expr);
                $this->_using_default_result_columns = false;
            } else {
                $this->_result_columns[] = $expr;
            }
            return $this;
        }

        /**
         * Add a column to the list of columns returned by the SELECT
         * query. This defaults to '*'. The second optional argument is
         * the alias to return the column as.
         */
        public function select($column, $alias=null) {
            $column = $this->_quote_identifier($column);
            return $this->_add_result_column($column, $alias);
        }

        /**
         * Add an unquoted expression to the list of columns returned
         * by the SELECT query. The second optional argument is
         * the alias to return the column as.
         */
        public function select_expr($expr, $alias=null) {
            return $this->_add_result_column($expr, $alias);
        }

        /**
         * Add columns to the list of columns returned by the SELECT
         * query. This defaults to '*'. Many columns can be supplied
         * as either an array or as a list of parameters to the method.
         * 
         * Note that the alias must not be numeric - if you want a
         * numeric alias then prepend it with some alpha chars. eg. a1
         * 
         * @example select_many(array('alias' => 'column', 'column2', 'alias2' => 'column3'), 'column4', 'column5');
         * @example select_many('column', 'column2', 'column3');
         * @example select_many(array('column', 'column2', 'column3'), 'column4', 'column5');
         * 
         * @return \ORM
         */
        public function select_many() {
            $columns = func_get_args();
            if(!empty($columns)) {
                $columns = $this->_normalise_select_many_columns($columns);
                foreach($columns as $alias => $column) {
                    if(is_numeric($alias)) {
                        $alias = null;
                    }
                    $this->select($column, $alias);
                }
            }
            return $this;
        }

        /**
         * Add an unquoted expression to the list of columns returned
         * by the SELECT query. Many columns can be supplied as either 
         * an array or as a list of parameters to the method.
         * 
         * Note that the alias must not be numeric - if you want a
         * numeric alias then prepend it with some alpha chars. eg. a1
         * 
         * @example select_many_expr(array('alias' => 'column', 'column2', 'alias2' => 'column3'), 'column4', 'column5')
         * @example select_many_expr('column', 'column2', 'column3')
         * @example select_many_expr(array('column', 'column2', 'column3'), 'column4', 'column5')
         * 
         * @return \ORM
         */
        public function select_many_expr() {
            $columns = func_get_args();
            if(!empty($columns)) {
                $columns = $this->_normalise_select_many_columns($columns);
                foreach($columns as $alias => $column) {
                    if(is_numeric($alias)) {
                        $alias = null;
                    }
                    $this->select_expr($column, $alias);
                }
            }
            return $this;
        }

        /**
         * Take a column specification for the select many methods and convert it
         * into a normalised array of columns and aliases.
         * 
         * It is designed to turn the following styles into a normalised array:
         * 
         * array(array('alias' => 'column', 'column2', 'alias2' => 'column3'), 'column4', 'column5'))
         * 
         * @param array $columns
         * @return array
         */
        protected function _normalise_select_many_columns($columns) {
            $return = array();
            foreach($columns as $column) {
                if(is_array($column)) {
                    foreach($column as $key => $value) {
                        if(!is_numeric($key)) {
                            $return[$key] = $value;
                        } else {
                            $return[] = $value;
                        }
                    }
                } else {
                    $return[] = $column;
                }
            }
            return $return;
        }

        /**
         * Add a DISTINCT keyword before the list of columns in the SELECT query
         */
        public function distinct() {
            $this->_distinct = true;
            return $this;
        }

        /**
         * Internal method to add a JOIN source to the query.
         *
         * The join_operator should be one of INNER, LEFT OUTER, CROSS etc - this
         * will be prepended to JOIN.
         *
         * The table should be the name of the table to join to.
         *
         * The constraint may be either a string or an array with three elements. If it
         * is a string, it will be compiled into the query as-is, with no escaping. The
         * recommended way to supply the constraint is as an array with three elements:
         *
         * first_column, operator, second_column
         *
         * Example: array('user.id', '=', 'profile.user_id')
         *
         * will compile to
         *
         * ON `user`.`id` = `profile`.`user_id`
         *
         * The final (optional) argument specifies an alias for the joined table.
         */
        protected function _add_join_source($join_operator, $table, $constraint, $table_alias=null) {

            $join_operator = trim("{$join_operator} JOIN");

            $table = $this->_quote_identifier($table);

            // Add table alias if present
            if (!is_null($table_alias)) {
                $table_alias = $this->_quote_identifier($table_alias);
                $table .= " {$table_alias}";
            }

            // Build the constraint
            if (is_array($constraint)) {
                list($first_column, $operator, $second_column) = $constraint;
                $first_column = $this->_quote_identifier($first_column);
                $second_column = $this->_quote_identifier($second_column);
                $constraint = "{$first_column} {$operator} {$second_column}";
            }

            $this->_join_sources[] = "{$join_operator} {$table} ON {$constraint}";
            return $this;
        }

        /**
         * Add a simple JOIN source to the query
         */
        public function join($table, $constraint, $table_alias=null) {
            return $this->_add_join_source("", $table, $constraint, $table_alias);
        }

        /**
         * Add an INNER JOIN souce to the query
         */
        public function inner_join($table, $constraint, $table_alias=null) {
            return $this->_add_join_source("INNER", $table, $constraint, $table_alias);
        }

        /**
         * Add a LEFT OUTER JOIN souce to the query
         */
        public function left_outer_join($table, $constraint, $table_alias=null) {
            return $this->_add_join_source("LEFT OUTER", $table, $constraint, $table_alias);
        }

        /**
         * Add an RIGHT OUTER JOIN souce to the query
         */
        public function right_outer_join($table, $constraint, $table_alias=null) {
            return $this->_add_join_source("RIGHT OUTER", $table, $constraint, $table_alias);
        }

        /**
         * Add an FULL OUTER JOIN souce to the query
         */
        public function full_outer_join($table, $constraint, $table_alias=null) {
            return $this->_add_join_source("FULL OUTER", $table, $constraint, $table_alias);
        }

        /**
         * Internal method to add a HAVING condition to the query
         */
        protected function _add_having($fragment, $values=array()) {
            return $this->_add_condition('having', $fragment, $values);
        }

        /**
         * Internal method to add a HAVING condition to the query
         */
        protected function _add_simple_having($column_name, $separator, $value) {
            return $this->_add_simple_condition('having', $column_name, $separator, $value);
        }

        /**
         * Internal method to add a WHERE condition to the query
         */
        protected function _add_where($fragment, $values=array()) {
            return $this->_add_condition('where', $fragment, $values);
        }

        /**
         * Internal method to add a WHERE condition to the query
         */
        protected function _add_simple_where($column_name, $separator, $value) {
            return $this->_add_simple_condition('where', $column_name, $separator, $value);
        }

        /**
         * Internal method to add a HAVING or WHERE condition to the query
         */
        protected function _add_condition($type, $fragment, $values=array()) {
            $conditions_class_property_name = "_{$type}_conditions";
            if (!is_array($values)) {
                $values = array($values);
            }
            array_push($this->$conditions_class_property_name, array(
                self::CONDITION_FRAGMENT => $fragment,
                self::CONDITION_VALUES => $values,
            ));
            return $this;
        }

       /**
         * Helper method to compile a simple COLUMN SEPARATOR VALUE
         * style HAVING or WHERE condition into a string and value ready to
         * be passed to the _add_condition method. Avoids duplication
         * of the call to _quote_identifier
         */
        protected function _add_simple_condition($type, $column_name, $separator, $value) {
            // Add the table name in case of ambiguous columns
            if (count($this->_join_sources) > 0 && strpos($column_name, '.') === false) {
                $table = $this->_table_name;
                if (!is_null($this->_table_alias)) {
                    $table = $this->_table_alias;
                }

                $column_name = "{$table}.{$column_name}";
            }
            $column_name = $this->_quote_identifier($column_name);
            return $this->_add_condition($type, "{$column_name} {$separator} ?", $value);
        } 

        /**
         * Return a string containing the given number of question marks,
         * separated by commas. Eg "?, ?, ?"
         */
        protected function _create_placeholders($fields) {
            if(!empty($fields)) {
                $db_fields = array();
                foreach($fields as $key => $value) {
                    // Process expression fields directly into the query
                    if(array_key_exists($key, $this->_expr_fields)) {
                        $db_fields[] = $value;
                    } else {
                        $db_fields[] = '?';
                    }
                }
                return implode(', ', $db_fields);
            }
        }

        /**
         * Add a WHERE column = value clause to your query. Each time
         * this is called in the chain, an additional WHERE will be
         * added, and these will be ANDed together when the final query
         * is built.
         */
        public function where($column_name, $value) {
            return $this->where_equal($column_name, $value);
        }

        /**
         * More explicitly named version of for the where() method.
         * Can be used if preferred.
         */
        public function where_equal($column_name, $value) {
            return $this->_add_simple_where($column_name, '=', $value);
        }

        /**
         * Add a WHERE column != value clause to your query.
         */
        public function where_not_equal($column_name, $value) {
            return $this->_add_simple_where($column_name, '!=', $value);
        }

        /**
         * Special method to query the table by its primary key
         */
        public function where_id_is($id) {
            return $this->where($this->_get_id_column_name(), $id);
        }

        /**
         * Add a WHERE ... LIKE clause to your query.
         */
        public function where_like($column_name, $value) {
            return $this->_add_simple_where($column_name, 'LIKE', $value);
        }

        /**
         * Add where WHERE ... NOT LIKE clause to your query.
         */
        public function where_not_like($column_name, $value) {
            return $this->_add_simple_where($column_name, 'NOT LIKE', $value);
        }

        /**
         * Add a WHERE ... > clause to your query
         */
        public function where_gt($column_name, $value) {
            return $this->_add_simple_where($column_name, '>', $value);
        }

        /**
         * Add a WHERE ... < clause to your query
         */
        public function where_lt($column_name, $value) {
            return $this->_add_simple_where($column_name, '<', $value);
        }

        /**
         * Add a WHERE ... >= clause to your query
         */
        public function where_gte($column_name, $value) {
            return $this->_add_simple_where($column_name, '>=', $value);
        }

        /**
         * Add a WHERE ... <= clause to your query
         */
        public function where_lte($column_name, $value) {
            return $this->_add_simple_where($column_name, '<=', $value);
        }

        /**
         * Add a WHERE ... IN clause to your query
         */
        public function where_in($column_name, $values) {
            $column_name = $this->_quote_identifier($column_name);
            $placeholders = $this->_create_placeholders($values);
            return $this->_add_where("{$column_name} IN ({$placeholders})", $values);
        }

        /**
         * Add a WHERE ... NOT IN clause to your query
         */
        public function where_not_in($column_name, $values) {
            $column_name = $this->_quote_identifier($column_name);
            $placeholders = $this->_create_placeholders($values);
            return $this->_add_where("{$column_name} NOT IN ({$placeholders})", $values);
        }

        /**
         * Add a WHERE column IS NULL clause to your query
         */
        public function where_null($column_name) {
            $column_name = $this->_quote_identifier($column_name);
            return $this->_add_where("{$column_name} IS NULL");
        }

        /**
         * Add a WHERE column IS NOT NULL clause to your query
         */
        public function where_not_null($column_name) {
            $column_name = $this->_quote_identifier($column_name);
            return $this->_add_where("{$column_name} IS NOT NULL");
        }

        /**
         * Add a raw WHERE clause to the query. The clause should
         * contain question mark placeholders, which will be bound
         * to the parameters supplied in the second argument.
         */
        public function where_raw($clause, $parameters=array()) {
            return $this->_add_where($clause, $parameters);
        }

        /**
         * Add a LIMIT to the query
         */
        public function limit($limit) {
            $this->_limit = $limit;
            return $this;
        }

        /**
         * Add an OFFSET to the query
         */
        public function offset($offset) {
            $this->_offset = $offset;
            return $this;
        }

        /**
         * Add an ORDER BY clause to the query
         */
        protected function _add_order_by($column_name, $ordering) {
            $column_name = $this->_quote_identifier($column_name);
            $this->_order_by[] = "{$column_name} {$ordering}";
            return $this;
        }

        /**
         * Add an ORDER BY column DESC clause
         */
        public function order_by_desc($column_name) {
            return $this->_add_order_by($column_name, 'DESC');
        }

        /**
         * Add an ORDER BY column ASC clause
         */
        public function order_by_asc($column_name) {
            return $this->_add_order_by($column_name, 'ASC');
        }

        /**
         * Add an unquoted expression as an ORDER BY clause
         */
        public function order_by_expr($clause) {
            $this->_order_by[] = $clause;
            return $this;
        }

        /**
         * Add a column to the list of columns to GROUP BY
         */
        public function group_by($column_name) {
            $column_name = $this->_quote_identifier($column_name);
            $this->_group_by[] = $column_name;
            return $this;
        }

        /**
         * Add an unquoted expression to the list of columns to GROUP BY 
         */
        public function group_by_expr($expr) {
            $this->_group_by[] = $expr;
            return $this;
        }

        /**
         * Add a HAVING column = value clause to your query. Each time
         * this is called in the chain, an additional HAVING will be
         * added, and these will be ANDed together when the final query
         * is built.
         */
        public function having($column_name, $value) {
            return $this->having_equal($column_name, $value);
        }

        /**
         * More explicitly named version of for the having() method.
         * Can be used if preferred.
         */
        public function having_equal($column_name, $value) {
            return $this->_add_simple_having($column_name, '=', $value);
        }

        /**
         * Add a HAVING column != value clause to your query.
         */
        public function having_not_equal($column_name, $value) {
            return $this->_add_simple_having($column_name, '!=', $value);
        }

        /**
         * Special method to query the table by its primary key
         */
        public function having_id_is($id) {
            return $this->having($this->_get_id_column_name(), $id);
        }

        /**
         * Add a HAVING ... LIKE clause to your query.
         */
        public function having_like($column_name, $value) {
            return $this->_add_simple_having($column_name, 'LIKE', $value);
        }

        /**
         * Add where HAVING ... NOT LIKE clause to your query.
         */
        public function having_not_like($column_name, $value) {
            return $this->_add_simple_having($column_name, 'NOT LIKE', $value);
        }

        /**
         * Add a HAVING ... > clause to your query
         */
        public function having_gt($column_name, $value) {
            return $this->_add_simple_having($column_name, '>', $value);
        }

        /**
         * Add a HAVING ... < clause to your query
         */
        public function having_lt($column_name, $value) {
            return $this->_add_simple_having($column_name, '<', $value);
        }

        /**
         * Add a HAVING ... >= clause to your query
         */
        public function having_gte($column_name, $value) {
            return $this->_add_simple_having($column_name, '>=', $value);
        }

        /**
         * Add a HAVING ... <= clause to your query
         */
        public function having_lte($column_name, $value) {
            return $this->_add_simple_having($column_name, '<=', $value);
        }

        /**
         * Add a HAVING ... IN clause to your query
         */
        public function having_in($column_name, $values) {
            $column_name = $this->_quote_identifier($column_name);
            $placeholders = $this->_create_placeholders($values);
            return $this->_add_having("{$column_name} IN ({$placeholders})", $values);
        }

        /**
         * Add a HAVING ... NOT IN clause to your query
         */
        public function having_not_in($column_name, $values) {
            $column_name = $this->_quote_identifier($column_name);
            $placeholders = $this->_create_placeholders($values);
            return $this->_add_having("{$column_name} NOT IN ({$placeholders})", $values);
        }

        /**
         * Add a HAVING column IS NULL clause to your query
         */
        public function having_null($column_name) {
            $column_name = $this->_quote_identifier($column_name);
            return $this->_add_having("{$column_name} IS NULL");
        }

        /**
         * Add a HAVING column IS NOT NULL clause to your query
         */
        public function having_not_null($column_name) {
            $column_name = $this->_quote_identifier($column_name);
            return $this->_add_having("{$column_name} IS NOT NULL");
        }

        /**
         * Add a raw HAVING clause to the query. The clause should
         * contain question mark placeholders, which will be bound
         * to the parameters supplied in the second argument.
         */
        public function having_raw($clause, $parameters=array()) {
            return $this->_add_having($clause, $parameters);
        }

        /**
         * Build a SELECT statement based on the clauses that have
         * been passed to this instance by chaining method calls.
         */
        protected function _build_select() {
            // If the query is raw, just set the $this->_values to be
            // the raw query parameters and return the raw query
            if ($this->_is_raw_query) {
                $this->_values = $this->_raw_parameters;
                return $this->_raw_query;
            }

            // Build and return the full SELECT statement by concatenating
            // the results of calling each separate builder method.
            return $this->_join_if_not_empty(" ", array(
                $this->_build_select_start(),
                $this->_build_join(),
                $this->_build_where(),
                $this->_build_group_by(),
                $this->_build_having(),
                $this->_build_order_by(),
                $this->_build_limit(),
                $this->_build_offset(),
            ));
        }

        /**
         * Build the start of the SELECT statement
         */
        protected function _build_select_start() {
            $fragment = 'SELECT ';
            $result_columns = join(', ', $this->_result_columns);

            if (!is_null($this->_limit) &&
                self::$_config[$this->_connection_name]['limit_clause_style'] === ORM::LIMIT_STYLE_TOP_N) {
                $fragment .= "TOP {$this->_limit} ";
            }

            if ($this->_distinct) {
                $result_columns = 'DISTINCT ' . $result_columns;
            }

            $fragment .= "{$result_columns} FROM " . $this->_quote_identifier($this->_table_name);

            if (!is_null($this->_table_alias)) {
                $fragment .= " " . $this->_quote_identifier($this->_table_alias);
            }
            return $fragment;
        }

        /**
         * Build the JOIN sources
         */
        protected function _build_join() {
            if (count($this->_join_sources) === 0) {
                return '';
            }

            return join(" ", $this->_join_sources);
        }

        /**
         * Build the WHERE clause(s)
         */
        protected function _build_where() {
            return $this->_build_conditions('where');
        }

        /**
         * Build the HAVING clause(s)
         */
        protected function _build_having() {
            return $this->_build_conditions('having');
        }

        /**
         * Build GROUP BY
         */
        protected function _build_group_by() {
            if (count($this->_group_by) === 0) {
                return '';
            }
            return "GROUP BY " . join(", ", $this->_group_by);
        }

        /**
         * Build a WHERE or HAVING clause
         * @param string $type
         * @return string
         */
        protected function _build_conditions($type) {
            $conditions_class_property_name = "_{$type}_conditions";
            // If there are no clauses, return empty string
            if (count($this->$conditions_class_property_name) === 0) {
                return '';
            }

            $conditions = array();
            foreach ($this->$conditions_class_property_name as $condition) {
                $conditions[] = $condition[self::CONDITION_FRAGMENT];
                $this->_values = array_merge($this->_values, $condition[self::CONDITION_VALUES]);
            }

            return strtoupper($type) . " " . join(" AND ", $conditions);
        }

        /**
         * Build ORDER BY
         */
        protected function _build_order_by() {
            if (count($this->_order_by) === 0) {
                return '';
            }
            return "ORDER BY " . join(", ", $this->_order_by);
        }

        /**
         * Build LIMIT
         */
        protected function _build_limit() {
            $fragment = '';
            if (!is_null($this->_limit) &&
                self::$_config[$this->_connection_name]['limit_clause_style'] == ORM::LIMIT_STYLE_LIMIT) {
                if (self::$_db[$this->_connection_name]->getAttribute(PDO::ATTR_DRIVER_NAME) == 'firebird') {
                    $fragment = 'ROWS';
                } else {
                    $fragment = 'LIMIT';
                }
                $fragment .= " {$this->_limit}";
            }
            return $fragment;
        }

        /**
         * Build OFFSET
         */
        protected function _build_offset() {
            if (!is_null($this->_offset)) {
                $clause = 'OFFSET';
                if (self::$_db[$this->_connection_name]->getAttribute(PDO::ATTR_DRIVER_NAME) == 'firebird') {
                    $clause = 'TO';
                }
                return "$clause " . $this->_offset;
            }
            return '';
        }

        /**
         * Wrapper around PHP's join function which
         * only adds the pieces if they are not empty.
         */
        protected function _join_if_not_empty($glue, $pieces) {
            $filtered_pieces = array();
            foreach ($pieces as $piece) {
                if (is_string($piece)) {
                    $piece = trim($piece);
                }
                if (!empty($piece)) {
                    $filtered_pieces[] = $piece;
                }
            }
            return join($glue, $filtered_pieces);
        }

        /**
         * Quote a string that is used as an identifier
         * (table names, column names etc). This method can
         * also deal with dot-separated identifiers eg table.column
         */
        protected function _quote_identifier($identifier) {
            $parts = explode('.', $identifier);
            $parts = array_map(array($this, '_quote_identifier_part'), $parts);
            return join('.', $parts);
        }

        /**
         * This method performs the actual quoting of a single
         * part of an identifier, using the identifier quote
         * character specified in the config (or autodetected).
         */
        protected function _quote_identifier_part($part) {
            if ($part === '*') {
                return $part;
            }

            $quote_character = self::$_config[$this->_connection_name]['identifier_quote_character'];
            // double up any identifier quotes to escape them
            return $quote_character .
                   str_replace($quote_character,
                               $quote_character . $quote_character,
                               $part
                   ) . $quote_character;
        }

        /**
         * Create a cache key for the given query and parameters.
         */
        protected static function _create_cache_key($query, $parameters) {
            $parameter_string = join(',', $parameters);
            $key = $query . ':' . $parameter_string;
            return sha1($key);
        }

        /**
         * Check the query cache for the given cache key. If a value
         * is cached for the key, return the value. Otherwise, return false.
         */
        protected static function _check_query_cache($cache_key, $connection_name = self::DEFAULT_CONNECTION) {
            if (isset(self::$_query_cache[$connection_name][$cache_key])) {
                return self::$_query_cache[$connection_name][$cache_key];
            }
            return false;
        }

        /**
         * Clear the query cache
         */
        public static function clear_cache() {
            self::$_query_cache = array();
        }

        /**
         * Add the given value to the query cache.
         */
        protected static function _cache_query_result($cache_key, $value, $connection_name = self::DEFAULT_CONNECTION) {
            if (!isset(self::$_query_cache[$connection_name])) {
                self::$_query_cache[$connection_name] = array();
            }
            self::$_query_cache[$connection_name][$cache_key] = $value;
        }

        /**
         * Execute the SELECT query that has been built up by chaining methods
         * on this class. Return an array of rows as associative arrays.
         */
        protected function _run() {
            $query = $this->_build_select();
            $caching_enabled = self::$_config[$this->_connection_name]['caching'];

            if ($caching_enabled) {
                $cache_key = self::_create_cache_key($query, $this->_values);
                $cached_result = self::_check_query_cache($cache_key, $this->_connection_name);

                if ($cached_result !== false) {
                    return $cached_result;
                }
            }

            self::_execute($query, $this->_values, $this->_connection_name);
            $statement = self::get_last_statement();

            $rows = array();
            while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
                $rows[] = $row;
            }

            if ($caching_enabled) {
                self::_cache_query_result($cache_key, $rows, $this->_connection_name);
            }

            // reset Idiorm after executing the query
            $this->_values = array();
            $this->_result_columns = array('*');
            $this->_using_default_result_columns = true;

            return $rows;
        }

        /**
         * Return the raw data wrapped by this ORM
         * instance as an associative array. Column
         * names may optionally be supplied as arguments,
         * if so, only those keys will be returned.
         */
        public function as_array() {
            if (func_num_args() === 0) {
                return $this->_data;
            }
            $args = func_get_args();
            return array_intersect_key($this->_data, array_flip($args));
        }

        /**
         * Return the value of a property of this object (database row)
         * or null if not present.
         */
        public function get($key) {
            return isset($this->_data[$key]) ? $this->_data[$key] : null;
        }

        /**
         * Return the name of the column in the database table which contains
         * the primary key ID of the row.
         */
        protected function _get_id_column_name() {
            if (!is_null($this->_instance_id_column)) {
                return $this->_instance_id_column;
            }
            if (isset(self::$_config[$this->_connection_name]['id_column_overrides'][$this->_table_name])) {
                return self::$_config[$this->_connection_name]['id_column_overrides'][$this->_table_name];
            }
            return self::$_config[$this->_connection_name]['id_column'];
        }

        /**
         * Get the primary key ID of this object.
         */
        public function id() {
            return $this->get($this->_get_id_column_name());
        }

        /**
         * Set a property to a particular value on this object.
         * To set multiple properties at once, pass an associative array
         * as the first parameter and leave out the second parameter.
         * Flags the properties as 'dirty' so they will be saved to the
         * database when save() is called.
         */
        public function set($key, $value = null) {
            return $this->_set_orm_property($key, $value);
        }

        /**
         * Set a property to a particular value on this object.
         * To set multiple properties at once, pass an associative array
         * as the first parameter and leave out the second parameter.
         * Flags the properties as 'dirty' so they will be saved to the
         * database when save() is called. 
         * @param string|array $key
         * @param string|null $value
         */
        public function set_expr($key, $value = null) {
            return $this->_set_orm_property($key, $value, true);
        }

        /**
         * Set a property on the ORM object.
         * @param string|array $key
         * @param string|null $value
         * @param bool $raw Whether this value should be treated as raw or not
         */
        protected function _set_orm_property($key, $value = null, $expr = false) {
            if (!is_array($key)) {
                $key = array($key => $value);
            }
            foreach ($key as $field => $value) {
                $this->_data[$field] = $value;
                $this->_dirty_fields[$field] = $value;
                if (false === $expr and isset($this->_expr_fields[$field])) {
                    unset($this->_expr_fields[$field]);
                } else if (true === $expr) {
                    $this->_expr_fields[$field] = true;
                }
            }
            return $this;
        }

        /**
         * Check whether the given field has been changed since this
         * object was saved.
         */
        public function is_dirty($key) {
            return isset($this->_dirty_fields[$key]);
        }

        /**
         * Check whether the model was the result of a call to create() or not
         * @return bool
         */
        public function is_new() {
            return $this->_is_new;
        }

        /**
         * Save any fields which have been modified on this object
         * to the database.
         */
        public function save() {
            $query = array();

            // remove any expression fields as they are already baked into the query
            $values = array_values(array_diff_key($this->_dirty_fields, $this->_expr_fields));

            if (!$this->_is_new) { // UPDATE
                // If there are no dirty values, do nothing
                if (empty($values) && empty($this->_expr_fields)) {
                    return true;
                }
                $query = $this->_build_update();
                $values[] = $this->id();
            } else { // INSERT
                $query = $this->_build_insert();
            }

            $success = self::_execute($query, $values, $this->_connection_name);

            // If we've just inserted a new record, set the ID of this object
            if ($this->_is_new) {
                $this->_is_new = false;
                if (is_null($this->id())) {
                    if(self::$_db[$this->_connection_name]->getAttribute(PDO::ATTR_DRIVER_NAME) == 'pgsql') {
                        $this->_data[$this->_get_id_column_name()] = self::get_last_statement()->fetchColumn();
                    } else {
                        $this->_data[$this->_get_id_column_name()] = self::$_db[$this->_connection_name]->lastInsertId();
                    }
                }
            }

            $this->_dirty_fields = $this->_expr_fields = array();
            return $success;
        }

        /**
         * Build an UPDATE query
         */
        protected function _build_update() {
            $query = array();
            $query[] = "UPDATE {$this->_quote_identifier($this->_table_name)} SET";

            $field_list = array();
            foreach ($this->_dirty_fields as $key => $value) {
                if(!array_key_exists($key, $this->_expr_fields)) {
                    $value = '?';
                }
                $field_list[] = "{$this->_quote_identifier($key)} = $value";
            }
            $query[] = join(", ", $field_list);
            $query[] = "WHERE";
            $query[] = $this->_quote_identifier($this->_get_id_column_name());
            $query[] = "= ?";
            return join(" ", $query);
        }

        /**
         * Build an INSERT query
         */
        protected function _build_insert() {
            $query[] = "INSERT INTO";
            $query[] = $this->_quote_identifier($this->_table_name);
            $field_list = array_map(array($this, '_quote_identifier'), array_keys($this->_dirty_fields));
            $query[] = "(" . join(", ", $field_list) . ")";
            $query[] = "VALUES";

            $placeholders = $this->_create_placeholders($this->_dirty_fields);
            $query[] = "({$placeholders})";

            if (self::$_db[$this->_connection_name]->getAttribute(PDO::ATTR_DRIVER_NAME) == 'pgsql') {
                $query[] = 'RETURNING ' . $this->_quote_identifier($this->_get_id_column_name());
            }

            return join(" ", $query);
        }

        /**
         * Delete this record from the database
         */
        public function delete() {
            $query = join(" ", array(
                "DELETE FROM",
                $this->_quote_identifier($this->_table_name),
                "WHERE",
                $this->_quote_identifier($this->_get_id_column_name()),
                "= ?",
            ));

            return self::_execute($query, array($this->id()), $this->_connection_name);
        }

        /**
         * Delete many records from the database
         */
        public function delete_many() {
            // Build and return the full DELETE statement by concatenating
            // the results of calling each separate builder method.
            $query = $this->_join_if_not_empty(" ", array(
                "DELETE FROM",
                $this->_quote_identifier($this->_table_name),
                $this->_build_where(),
            ));

            return self::_execute($query, $this->_values, $this->_connection_name);
        }

        // --------------------- //
        // ---  ArrayAccess  --- //
        // --------------------- //

        public function offsetExists($key) {
            return isset($this->_data[$key]);
        }

        public function offsetGet($key) {
            return $this->get($key);
        }

        public function offsetSet($key, $value) {
            if(is_null($key)) {
                throw new InvalidArgumentException('You must specify a key/array index.');
            }
            $this->set($key, $value);
        }

        public function offsetUnset($key) {
            unset($this->_data[$key]);
            unset($this->_dirty_fields[$key]);
        }

        // --------------------- //
        // --- MAGIC METHODS --- //
        // --------------------- //
        public function __get($key) {
            return $this->offsetGet($key);
        }

        public function __set($key, $value) {
            $this->offsetSet($key, $value);
        }

        public function __unset($key) {
            $this->offsetUnset($key);
        }


        public function __isset($key) {
            return $this->offsetExists($key);
        }

        /**
         * Magic method to capture calls to undefined class methods.
         * In this case we are attempting to convert camel case formatted 
         * methods into underscore formatted methods.
         *
         * This allows us to call ORM methods using camel case and remain 
         * backwards compatible.
         * 
         * @param  string   $name
         * @param  array    $arguments
         * @return ORM
         */
        public function __call($name, $arguments)
        {
            $method = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $name));

            return call_user_func_array(array($this, $method), $arguments);
        }

        /**
         * Magic method to capture calls to undefined static class methods. 
         * In this case we are attempting to convert camel case formatted 
         * methods into underscore formatted methods.
         *
         * This allows us to call ORM methods using camel case and remain 
         * backwards compatible.
         * 
         * @param  string   $name
         * @param  array    $arguments
         * @return ORM
         */
        public static function __callStatic($name, $arguments)
        {
            $method = strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $name));

            return call_user_func_array(array('ORM', $method), $arguments);
        }
    }

    /**
     * A class to handle str_replace operations that involve quoted strings
     * @example IdiormString::str_replace_outside_quotes('?', '%s', 'columnA = "Hello?" AND columnB = ?');
     * @example IdiormString::value('columnA = "Hello?" AND columnB = ?')->replace_outside_quotes('?', '%s');
     * @author Jeff Roberson <ridgerunner@fluxbb.org>
     * @author Simon Holywell <treffynnon@php.net>
     * @link http://stackoverflow.com/a/13370709/461813 StackOverflow answer
     */
    class IdiormString {
        protected $subject;
        protected $search;
        protected $replace;

        /**
         * Get an easy to use instance of the class
         * @param string $subject
         * @return \self
         */
        public static function value($subject) {
            return new self($subject);
        }

        /**
         * Shortcut method: Replace all occurrences of the search string with the replacement
         * string where they appear outside quotes.
         * @param string $search
         * @param string $replace
         * @param string $subject
         * @return string
         */
        public static function str_replace_outside_quotes($search, $replace, $subject) {
            return self::value($subject)->replace_outside_quotes($search, $replace);
        }

        /**
         * Set the base string object
         * @param string $subject
         */
        public function __construct($subject) {
            $this->subject = (string) $subject;
        }

        /**
         * Replace all occurrences of the search string with the replacement
         * string where they appear outside quotes
         * @param string $search
         * @param string $replace
         * @return string
         */
        public function replace_outside_quotes($search, $replace) {
            $this->search = $search;
            $this->replace = $replace;
            return $this->_str_replace_outside_quotes();
        }

        /**
         * Validate an input string and perform a replace on all ocurrences
         * of $this->search with $this->replace
         * @author Jeff Roberson <ridgerunner@fluxbb.org>
         * @link http://stackoverflow.com/a/13370709/461813 StackOverflow answer
         * @return string
         */
        protected function _str_replace_outside_quotes(){
            $re_valid = '/
                # Validate string having embedded quoted substrings.
                ^                           # Anchor to start of string.
                (?:                         # Zero or more string chunks.
                  "[^"\\\\]*(?:\\\\.[^"\\\\]*)*"  # Either a double quoted chunk,
                | \'[^\'\\\\]*(?:\\\\.[^\'\\\\]*)*\'  # or a single quoted chunk,
                | [^\'"\\\\]+               # or an unquoted chunk (no escapes).
                )*                          # Zero or more string chunks.
                \z                          # Anchor to end of string.
                /sx';
            if (!preg_match($re_valid, $this->subject)) {
                throw new IdiormStringException("Subject string is not valid in the replace_outside_quotes context.");
            }
            $re_parse = '/
                # Match one chunk of a valid string having embedded quoted substrings.
                  (                         # Either $1: Quoted chunk.
                    "[^"\\\\]*(?:\\\\.[^"\\\\]*)*"  # Either a double quoted chunk,
                  | \'[^\'\\\\]*(?:\\\\.[^\'\\\\]*)*\'  # or a single quoted chunk.
                  )                         # End $1: Quoted chunk.
                | ([^\'"\\\\]+)             # or $2: an unquoted chunk (no escapes).
                /sx';
            return preg_replace_callback($re_parse, array($this, '_str_replace_outside_quotes_cb'), $this->subject);
        }

        /**
         * Process each matching chunk from preg_replace_callback replacing
         * each occurrence of $this->search with $this->replace
         * @author Jeff Roberson <ridgerunner@fluxbb.org>
         * @link http://stackoverflow.com/a/13370709/461813 StackOverflow answer
         * @param array $matches
         * @return string
         */
        protected function _str_replace_outside_quotes_cb($matches) {
            // Return quoted string chunks (in group $1) unaltered.
            if ($matches[1]) return $matches[1];
            // Process only unquoted chunks (in group $2).
            return preg_replace('/'. preg_quote($this->search, '/') .'/',
                $this->replace, $matches[2]);
        }
    }

    /**
     * A result set class for working with collections of model instances
     * @author Simon Holywell <treffynnon@php.net>
     */
    class IdiormResultSet implements Countable, IteratorAggregate, ArrayAccess, Serializable {
        /**
         * The current result set as an array
         * @var array
         */
        protected $_results = array();

        /**
         * Optionally set the contents of the result set by passing in array
         * @param array $results
         */
        public function __construct(array $results = array()) {
            $this->set_results($results);
        }

        /**
         * Set the contents of the result set by passing in array
         * @param array $results
         */
        public function set_results(array $results) {
            $this->_results = $results;
        }

        /**
         * Get the current result set as an array
         * @return array
         */
        public function get_results() {
            return $this->_results;
        }

        /**
         * Get the current result set as an array
         * @return array
         */
        public function as_array() {
            return $this->get_results();
        }
        
        /**
         * Get the number of records in the result set
         * @return int
         */
        public function count() {
            return count($this->_results);
        }

        /**
         * Get an iterator for this object. In this case it supports foreaching
         * over the result set.
         * @return \ArrayIterator
         */
        public function getIterator() {
            return new ArrayIterator($this->_results);
        }

        /**
         * ArrayAccess
         * @param int|string $offset
         * @return bool
         */
        public function offsetExists($offset) {
            return isset($this->_results[$offset]);
        }

        /**
         * ArrayAccess
         * @param int|string $offset
         * @return mixed
         */
        public function offsetGet($offset) {
            return $this->_results[$offset];
        }
        
        /**
         * ArrayAccess
         * @param int|string $offset
         * @param mixed $value
         */
        public function offsetSet($offset, $value) {
            $this->_results[$offset] = $value;
        }

        /**
         * ArrayAccess
         * @param int|string $offset
         */
        public function offsetUnset($offset) {
            unset($this->_results[$offset]);
        }

        /**
         * Serializable
         * @return string
         */
        public function serialize() {
            return serialize($this->_results);
        }

        /**
         * Serializable
         * @param string $serialized
         * @return array
         */
        public function unserialize($serialized) {
            return unserialize($serialized);
        }

        /**
         * Call a method on all models in a result set. This allows for method
         * chaining such as setting a property on all models in a result set or
         * any other batch operation across models.
         * @example ORM::for_table('Widget')->find_many()->set('field', 'value')->save();
         * @param string $method
         * @param array $params
         * @return \IdiormResultSet
         */
        public function __call($method, $params = array()) {
            foreach($this->_results as $model) {
                call_user_func_array(array($model, $method), $params);
            }
            return $this;
        }
    }

    /**
     * A placeholder for exceptions eminating from the IdiormString class
     */
    class IdiormStringException extends Exception {}
