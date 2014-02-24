<?php
/**
 * Coseva CSV.
 *
 * A friendly, object-oriented alternative for parsing and filtering CSV files
 * with PHP.
 *
 * @package Coseva
 * @subpackage CSV
 * @copyright 2013 Johnny Freeman
 */

namespace Coseva;

use \SplFileObject,
    \LimitIterator,
    \IteratorAggregate,
    \ArrayIterator,
    \InvalidArgumentException;

/**
 * CSV.
 */
class CSV implements IteratorAggregate
{
    /**
     * Storage for parsed CSV rows.
     *
     * @var array $_rows the rows found in the CSV resource
     */
    protected $_rows;

    /**
     * Storage for filter callbacks to be executed during the parsing stage.
     *
     * @var array $_filters filter callbacks
     */
    protected $_filters = array();

    /**
     * Holds the CSV file pointer.
     *
     * @var SplFileObject $_file the active CSV file
     */
    protected $_file;

    /**
     * Holds config options for opening the file.
     *
     * @var array $_fileConfig configuration
     */
    protected $_fileConfig = array();

    /**
     * A list of open modes that are accepted by our file handler.
     *
     * @see http://php.net/manual/en/function.fopen.php for a list of modes
     * @var array $_availableOpenModes
     */
    private static $_availableOpenModes = array(
        'r', 'r+', 'w', 'w+', 'a', 'a+', 'x', 'x+', 'c', 'c+'
    );

    /**
     * Whether or not to flush empty rows after filtering.
     *
     * @var bool $_flushOnAfterFilter
     */
    protected $_flushOnAfterFilter = false;

    /**
     * Whether or not to do garbage collection after parsing.
     *
     * @var bool $_garbageCollection
     */
    protected $_garbageCollection = true;

    /**
     * An array of instances of CSV to prevent unnecessary parsing of CSV files.
     *
     * @var array $_instances A list of CSV instances, keyed by filename
     */
    private static $_instances = array();

    /**
     * Constructor for CSV.
     *
     * To read a csv file, just pass the path to the .csv file.
     *
     * @param string  $filename         The file to read. Should be readable.
     * @param string  $open_mode        The mode in which to open the file
     * @param boolean $use_include_path Whether to search through include_path
     * @see http://php.net/manual/en/function.fopen.php for a list of modes
     * @throws InvalidArgumentException when the given file could not be read
     * @throws InvalidArgumentException when the given open mode does not exist
     * @return CSV                      $this
     */
    public function __construct($filename, $open_mode = 'r', $use_include_path = false)
    {
        // Check if the given filename was readable.
        if (!$this->_resolveFilename($filename, $use_include_path)) {
            throw new InvalidArgumentException(
                var_export($filename, true) . ' is not readable.'
            );
        }

        // Check if the given open mode was valid.
        if (!in_array($open_mode, self::$_availableOpenModes)) {
            throw new InvalidArgumentException(
                'Unknown open mode ' . var_export($open_mode, true) . '.'
            );
        }

        // Store the configuration.
        $this->_fileConfig = array(
            'filename' => $filename,
            'open_mode' => $open_mode,
            // Explicitely cast this as a boolean to ensure proper bevahior.
            'use_include_path' => (bool) $use_include_path,
            // set comma as default delimiter
            'delimiter' => ','
        );

        // Try to automatically determine the most optimal settings for this file.
        // First we clear the stat cache to have a better prediction.
        clearstatcache(false, $filename);

        $fsize = filesize($filename);
        $malloc = memory_get_usage();
        $mlimit = (int) ini_get('memory_limit');

        // We have memory to spare. Make use of that.
        if ($mlimit < 0 || $mlimit - $malloc > $fsize * 2) {
            $this->_garbageCollection = false;
        }

        // If the file is large, flush empty rows to improve filter speed.
        if ($fsize > 1e6) $this->_flushOnAfterFilter = true;
    }

    /**
     * Get an instance of CSV, based on the filename.
     *
     * @param string $filename the CSV file to read. Should be readable.
     *   Filenames will be resolved. Symlinks will be followed.
     * @return CSV self::$_instances[$key]
     */
    public static function getInstance($filename, $open_mode = 'r', $use_include_path = false)
    {
        // Create a combined key so different open modes can get their own instance.
        $key = $open_mode . ':' . $filename;

        // Check if an instance exists. If not, create one.
        if (!isset(self::$_instances[$key])) {
            // Collect the class name. This won't break when the class name changes.
            $class = __CLASS__;

            // Create a new instance of this class.
            self::$_instances[$key] = new $class($filename, $open_mode, $use_include_path);
        }

        return self::$_instances[$key];
    }

    /**
     * Resolve a given filename, keeping include paths in mind.
     *
     * Note: Because PHP's integer type is signed and many platforms use 32bit
     * integers, some filesystem functions may return unexpected results for
     * files which are larger than 2GB.
     *
     * @param string &$filename the file to resolve.
     * @param boolean $use_include_path whether or not to use the PHP include path.
     *   If set to true, the PHP include path will be used to look for the given
     *   filename. Only if the filename is using a relative path.
     * @see http://php.net/manual/en/function.realpath.php
     * @return boolean true|false to indicate whether the resolving succeeded.
     */
    private function _resolveFilename(&$filename, $use_include_path = false)
    {
        $exists = file_exists($filename);

        // The given filename did not suffice. Let's do a deeper check.
        if (!$exists && $use_include_path && substr($filename, 0, 1) !== '/') {
            // Gather the include paths.
            $paths = explode(':', get_include_path());

            // Walk through the include paths.
            foreach ($paths as $path) {
                // Check if the file exists within this path.
                $exists = realpath($path . '/' . $filename);

                // It didn't work. Move along.
                if (!$exists) continue;

                // It actually did work. Now overwrite my filename.
                $filename = $exists;
                $exists = true;
                break;
            }
        }

        return $exists && is_readable($filename);
    }

    /**
     * Allows you to register any number of filters on a particular column or an
     * entire row.
     *
     * @param integer|callable $column Specific column number or the callable to
     *  be applied. Optional: Zero-based column number. If this parameter is
     *  preset the $callable will recieve the contents of the current column
     *  (as a string), and will receive the entire (array based) row otherwise.
     * @param callable $callable Either the current row (as an array) or the
     *  current column (as a string) as the first parameter. The callable must
     *  return the new filtered row or column.
     *  Note: You can also use any native PHP functions that permit one parameter
     *  and return the new value, like trim, htmlspecialchars, urlencode, etc.
     * @throws InvalidArgumentException when no valid callable was given
     * @throws InvalidArgumentException when no proper column index was supplied
     * @return CSV                      $this
     */
    public function filter($column, $callable = null)
    {
        // Get the function arguments.
        $args = func_get_args();
        $column = array_shift($args);

        // Check if we actually have a column or a callable.
        if (is_numeric($column)) {
            $callable = array_shift($args);
        } else {
            $callable = $column;
            $column = null;
        }

        // Check the function arguments.
        if (!is_callable($callable)) throw new InvalidArgumentException(
            'The $callable parameter must be callable.'
        );

        if (isset($column) && !is_numeric($column)) throw new InvalidArgumentException(
            'No proper column index provided. Expected a numeric, while given '
            . var_export($column, true)
        );

        // Add the filter to our stack. Apply it to the whole row when our column
        // appears to be the callable, being the only present argument.
        $this->_filters[] = array(
            'callable' => $callable,
            // Explicitely cast the column as an integer.
            'column' => isset($column) ? (int) $column : null,
            'args' => $args
        );

        return $this;
    }

    /**
     * Flush rows that have turned out empty, either after applying filters or
     * rows that simply have been empty in the source CSV from the get-go.
     *
     * @param boolean $onAfterFilter whether or not to trigger while parsing.
     *   Leave this blank to trigger a flush right now.
     * @return CSV $this
     */
    public function flushEmptyRows($onAfterFilter = null)
    {
        // Update the _flushOnAfterFilter flag and return.
        if (!empty($onAfterFilter)) {
            $this->_flushOnAfterFilter = (bool) $onAfterFilter;
            return $this;
        }

        // Parse the CSV.
        if (!isset($this->_rows)) $this->parse();

        // Walk through the rows.
        foreach ($this->_rows as $index => &$row) {
            $this->_flushEmptyRow($row, $index);
        }

        // Remove garbage.
        unset($row, $index);

        return $this;
    }

    /**
     * Flush a row if it's empty.
     *
     * @param mixed $row the row to flush
     * @param mixed $index the index of the row
     * @param bool $trim whether or not to trim the data.
     * @return void
     */
    private function _flushEmptyRow($row, $index, $trim = false)
    {
        // If the row is scalar, let's trim it first.
        if ($trim && is_scalar($row)) $row = trim($row);

        // Remove any rows that appear empty.
        if (empty($row)) unset($this->_rows[$index], $row, $index);
    }

    /**
     * This method will convert the csv to an array and will run all registered
     * filters against it.
     *
     * @param integer $rowOffset Determines which row the parser will start on.
     *   Zero-based index.
     *   Note: When using a row offset, skipped rows will never be parsed nor
     *   stored. As such, we encourage to use different instances when mixing
     *   offsets, to prevent resultsets from interfering.
     * @return CSV $this
     */
    public function parse($rowOffset = 0)
    {
        // Cast the row offset as an integer.
        $rowOffset = (int) $rowOffset;

        if (!isset($this->_rows)) {
            // Open the file if there is no SplFIleObject present.
            if (!($this->_file instanceof SplFileObject)) {
                $this->_file = new SplFileObject(
                    $this->_fileConfig['filename'],
                    $this->_fileConfig['open_mode'],
                    $this->_fileConfig['use_include_path']
                );

                // Set the flag to parse CSV.
                $this->_file->setFlags(SplFileObject::READ_CSV);

                // Set the delimiter
                $this->setDelimiter($this->_fileConfig['delimiter']);
            }

            $this->_rows = array();

            // Fetch the rows.
            foreach (new LimitIterator($this->_file, $rowOffset) as $key => $row) {
                // Apply any filters.
                $this->_rows[$key] = $this->_applyFilters($row);

                // Flush empty rows.
                if ($this->_flushOnAfterFilter) {
                    $this->_flushEmptyRow($row, $key, true);
                }
            }

            // Flush the filters.
            $this->flushFilters();

            // We won't need the file anymore.
            unset($this->_file);
        } elseif (empty($this->_filters)) {
            // Nothing to do here.
            // We return now to avoid triggering garbage collection.
            return $this;
        }

        if (!empty($this->_filters)) {
            // We explicitely divide the strategies here, since checking this
            // after applying filters on every row makes for a double iteration
            // through $this->flushEmptyRows().
            // We therefore do this while iterating, but array_map cannot supply
            // us with a proper index and therefore the flush would be delayed.
            if ($this->_flushOnAfterFilter) {
                foreach ($this->_rows as $index => &$row) {
                    // Apply the filters.
                    $row = $this->_applyFilters($row);

                    // Flush it if it's empty.
                    $this->_flushEmptyRow($row, $index);
                }
            } else {
                // Apply our filters.
                $this->_rows = array_map(
                    array($this, '_applyFilters'),
                    $this->_rows
                );
            }

            // Flush the filters.
            $this->flushFilters();
        }

        // Do some garbage collection to free memory of garbage we won't use.
        // @see http://php.net/manual/en/function.gc-collect-cycles.php
        if ($this->_garbageCollection) gc_collect_cycles();

        return $this;
    }

    /**
     * Whether or not to use garbage collection after parsing.
     *
     * @param bool $collect
     * @return CSV $this
     */
    public function collectGarbage($collect = true) {
        $this->_garbageCollection = (bool) $collect;
        return $this;
    }

    /**
     * Flushes all active filters.
     *
     * @return CSV $this
     */
    public function flushFilters()
    {
        $this->_filters = array();
        return $this;
    }

    /**
     * Apply filters to the given row.
     *
     * @param  array $row
     * @return array $row
     */
    public function _applyFilters(array $row)
    {
        if (!empty($this->_filters)) {
            // Run filters in the same order they were registered.
            foreach ($this->_filters as &$filter) {
                $callable =& $filter['callable'];
                $column =& $filter['column'];
                $arguments =& $filter['args'];

                // Apply to the entire row.
                if (is_null($column)) {
                    $row = call_user_func_array(
                        $callable,
                        array_merge(
                            array(&$row),
                            $arguments
                        )
                    );
                } else {
                    $row[$column] = call_user_func_array(
                        $callable,
                        array_merge(
                            array(&$row[$column]),
                            $arguments
                        )
                    );
                }
            }

            // Unset references.
            unset($filter, $callable, $column, $arguments);
        }

        return $row;
    }

    /**
     * Set a delimiter for the CSV file.
     *
     * @param string $delimiter
     * @return object $this CSV instance
     */
    public function setDelimiter($delimiter)
    {
        $this->_fileConfig['delimiter'] = $delimiter;

        // if the file has already been instantiated
        // just set the dimiter immediately
        if ($this->_file instanceof SplFileObject) {
            $this->_file->setCsvControl($this->_fileConfig['delimiter']);
        }

        return $this;
    }

    /**
     * Get an array iterator for the CSV rows.
     *
     * Required for implementing IteratorAggregate
     *
     * @return ArrayIterator
     */
    public function getIterator()
    {
        if (!isset($this->_rows)) $this->parse();
        return new ArrayIterator($this->_rows);
    }

    /**
     * This is a great way to display the filtered contents of the csv to you
     * during the development process (for debugging purposes).
     *
     * @return string $output HTML table of CSV contents
     */
    public function toTable()
    {
        $output = '';

        if (!isset($this->_rows)) $this->parse();

        if (!empty($this->_rows)) {
            // Begin table.
            $output = '<table border="1" cellspacing="1" cellpadding="3">';

            // Table head.
            $output .= '<thead><tr><th>&nbsp;</th>';
            foreach ($this->_rows as $row) {
                foreach ($row as $key => $col) {
                    $output .= '<th>' . $key .  '</th>';
                }
                break;
            }
            $output .= '</tr></thead>';

            // Table body.
            $output .= '<tbody>';
            foreach ($this->_rows as $i => $row) {
                $output .= '<tr>';
                $output .= '<th>' . $i . '</th>';
                foreach ($row as $col) {
                     $output .= '<td>' . $col .  '</td>';
                }
                $output .= '</tr>';
            }
            $output .= '</tbody>';

            // Close table.
            $output .= '</table>';
        }

        return $output;
    }

    /**
     * Use this to get the entire CSV in JSON format.
     *
     * @return string JSON encoded string
     */
    public function toJSON()
    {
        if (!isset($this->_rows)) $this->parse();
        return json_encode($this->_rows);
    }

    /**
     * If you cast a CSV instance as a string it will print the contents on the
     * CSV to an HTML table.
     *
     * @return string $this->toTable() HTML table of CSV contents
     */
    public function __toString()
    {
        return $this->toTable();
    }

}
