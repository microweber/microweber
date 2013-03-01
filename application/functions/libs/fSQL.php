<?php 

define("FSQL_ASSOC",1,TRUE);
define("FSQL_NUM",  2,TRUE);
define("FSQL_BOTH", 3,TRUE);
// http://fsql.sourceforge.net/docs/php_tutorial.html
define("FSQL_EXTENSION", ".cgi",TRUE);

// This function is in PHP5 but nowhere else so we're making it in case we're on PHP4
if (!function_exists('array_combine')) {
	function array_combine($keys, $values) {
		if(is_array($keys) && is_array($values) && count($keys) == count($values)) {
			$combined = array();
			foreach($keys as $indexnum => $key)
				$combined[$key] = $values[$indexnum];
			return $combined;
		}
		return false;
	}
}

/* A reentrant read write lock for a file */
class fSQLFileLock
{
	var $handle;
	var $filepath;
	var $lock;
	var $rcount = 0;
	var $wcount = 0;

	function fSQLFileLock($filepath)
	{
		$this->filepath = $filepath;
		$this->handle = null;
		$this->lock = 0;
	}

	function getHandle()
	{
		return $this->handle;
	}

	function acquireRead()
	{
		if($this->lock !== 0 && $this->handle !== null) {  /* Already have at least a read lock */
			$this->rcount++;
			return true;
		}               
		else if($this->lock === 0 && $this->handle === null) /* New lock */
		{
			$this->handle = fopen($this->filepath, 'rb');
			if($this->handle)
			{
				flock($this->handle, LOCK_SH);
				$this->lock = 1;
				$this->rcount = 1;
				return true;
			}
		}
            
		return false;     
	}

	function acquireWrite()
	{
		if($this->lock === 2 && $this->handle !== null)  /* Already have a write lock */
		{
			$this->wcount++;
			return true;
		}
		else if($this->lock === 1 && $this->handle !== null)  /* Upgrade a lock*/
		{
			flock($this->handle, LOCK_EX);
			$this->lock = 2;
			$this->wcount++;
			return true;
		}                
		else if($this->lock === 0 && $this->handle === null) /* New lock */
		{
			touch($this->filepath); // make sure it exists
			$this->handle = fopen($this->filepath, 'r+b');
			if($this->handle)
			{
				flock($this->handle, LOCK_EX);
				$this->lock = 2;
				$this->wcount = 1;
				return true;
			}
		}

		return false;
	}

	function releaseRead()
	{
		if($this->lock !== 0 && $this->handle !== null)
		{
			$this->rcount--;

			if($this->lock === 1 && $this->rcount === 0) /* Read lock now empty */
			{	
				// no readers or writers left, release lock
				flock($this->handle, LOCK_UN);
				fclose($this->handle);
				$this->handle = null;
				$this->lock = 0;
			}
		}

		return true;
	}

	function releaseWrite()
	{
		if($this->lock !== 0 && $this->handle !== null)
		{
			if($this->lock === 2) /* Write lock */
			{
				$this->wcount--;
				if($this->wcount === 0) // no writers left.
				{
					if($this->rcount > 0)  // only readers left.  downgrade lock.
					{
						flock($this->handle, LOCK_SH);
						$this->lock = 1;
					}
					else // no readers or writers left, release lock
					{
						flock($this->handle, LOCK_UN);
						fclose($this->handle);
						$this->handle = null;
						$this->lock = 0;
					}
				}
			}
		}

		return true;
	}
}

class fSQLTableCursor
{
	var $entries;
	var $num_rows;
	var $pos;
	
	function first()
	{
		$this->pos = 0;
	}
	
	function getRow()
	{
		if($this->pos >= 0 && $this->pos < $this->num_rows)
			return $this->entries[$this->pos];
		else
			return null;
	}
	
	function isDone()
	{
		return $this->pos < 0 || $this->pos >= $this->num_rows;
	}
	
	function last()
	{
		$this->pos = $this->num_rows - 1;
	}
	
	function previous()
	{
		$this->pos++;
	}
	
	function next()
	{
		$this->pos++;
	}
	
	function seek($pos)
	{
		if($pos >=0 & $pos < $count($this->entries))
			$this->pos = $pos;
	}
}

class fSQLTable
{
	var $cursor = NULL;
	var $columns = NULL;
	var $entries = NULL;
	var $data_load =0;
	var $uncommited = false;

	function create($path_to_db, $table_name, $columnDefs)
	{
		$table = new fSQLTable;
		$table->columns = $columnDefs;
		return $table;
	}
	
	function exists() {
		return TRUE;
	}
	
	function getColumnNames() {
		return array_keys($this->getColumns());
	}
	
	function getColumns() {
		return $this->columns;
	}
	
	function setColumns($columns) {
		$this->columns = $columns;
	}
	
	function getCursor()
	{
		if($this->cursor == NULL)
			$this->cursor = new fSQLTableCursor;
		
		$this->cursor->entries =& $this->entries;
		$this->cursor->num_rows = count($this->entries);
		$this->cursor->pos = 0;
		
		return $this->cursor;
	}
	
	function insertRow($data) {
		$this->entries[] = $data;
		$this->uncommited = true;
	}
	
	function updateRow($row, $data) {
		foreach($data as $key=> $value)
			$this->entries[$row][$key] = $value;
		$this->uncommited = true;
	}
	
	function deleteRow($row) {
		unset($this->entries[$row]);
		$this->uncommited = true;
	}
	
	function commit()
	{
		$this->uncommited = false;
	}
	
	function rollback()
	{
		$this->data_load = 0;
		$this->uncommited = false;
	}

	/* Unecessary for temporary tables */
	function readLock() { return true; }
	function writeLock() { return true; }
	function unlock() { return true; }
}

class fSQLCachedTable
{
	var $cursor = NULL;
	var $columns = NULL;
	var $entries = NULL;
	var $columns_path = NULL;
	var $data_path = NULL;
	var $columns_load = NULL;
	var $data_load = NULL;
	var $uncommited = false;
	var $columnsLockFile = NULL;
	var $columnsFile = NULL;
	var $dataLockFile = NULL;
	var $dataFile = NULL;
	var $lock = NULL;
	
	function fSQLCachedTable($path_to_db, $table_name)
	{
		$this->columns_path = $path_to_db.$table_name.'.columns';
		$this->data_path = $path_to_db.$table_name.'.data';
		$this->columnsLockFile = new fSQLFileLock($this->columns_path.'.lock.cgi');
		$this->columnsFile = new fSQLFileLock($this->columns_path.'.cgi');
		$this->dataLockFile = new fSQLFileLock($this->data_path.'.lock.cgi');
		$this->dataFile = new fSQLFileLock($this->data_path.'.cgi');
	}
	
	function create($path_to_db, $table_name, $columnDefs)
	{
		$table = new fSQLCachedTable($path_to_db, $table_name);
		$table->columns = $columnDefs;
		
		list($msec, $sec) = explode(' ', microtime());
		$table->columns_load = $table->data_load = $sec.$msec;

		// create the columns lock
		$table->columnsLockFile->acquireWrite();
		$columnsLock = $table->columnsLockFile->getHandle();
		ftruncate($columnsLock, 0);
		fwrite($columnsLock, $table->columns_load);
		
		// create the columns file
		$table->columnsFile->acquireWrite();
		$toprint = $table->_printColumns($columnDefs);
		fwrite($table->columnsFile->getHandle(), $toprint);
		
		$table->columnsFile->releaseWrite();	
		$table->columnsLockFile->releaseWrite();

		// create the data lock
		$table->dataLockFile->acquireWrite();
		$dataLock = $table->dataLockFile->getHandle();
		ftruncate($dataLock, 0);
		fwrite($dataLock, $table->data_load);
		
		// create the data file
		$table->dataFile->acquireWrite();
		fwrite($table->dataFile->getHandle(), "0\r\n");
		
		$table->dataFile->releaseWrite();	
		$table->dataLockFile->releaseWrite();
		
		return $table;
	}

	function _printColumns($columnDefs)
	{
		$toprint = count($columnDefs)."\r\n";
		foreach($columnDefs as $name => $column) {
			if(!is_array($column['restraint'])) {
				$toprint .= $name.": ".$column['type'].";;".$column['auto'].";".$column['default'].";".$column['key'].";".$column['null'].";\r\n";
			} else {
				$toprint .= $name.": ".$column['type'].";".implode(",", $column['restraint']).";".$column['auto'].";".$column['default'].";".$column['key'].";".$column['null'].";\r\n";
			}
		}
		return $toprint;
	}
	
	function exists() {
		return file_exists($this->columns_path.'.cgi');
	}
	
	function getColumnNames() {
		return array_keys($this->getColumns());
	}
	
	function getColumns() {
		$this->columnsLockFile->acquireRead();
		$lock = $this->columnsLockFile->getHandle();

		$modified = fread($lock, 20);
		if($this->columns_load === NULL || strcmp($this->columns_load, $modified) < 0)
		{
			$this->columns_load = $modified;
			
			$this->columnsFile->acquireRead();
			$columnsHandle = $this->columnsFile->getHandle();

			$line = fgets($columnsHandle);			
			if(!preg_match("/^(\d+)/", $line, $matches))
			{
				$this->columnsFile->releaseRead();
				$this->columnsLockFile->releaseRead();
				return NULL;
			}
			
			$num_columns = $matches[1];
			
			for($i = 0; $i < $num_columns; $i++) {
				$line =	fgets($columnsHandle, 4096);
				if(preg_match("/(\S+): (dt|d|i|f|s|t|e);(.*);(0|1);(-?\d+(?:\.\d+)?|'.*'|NULL);(p|u|k|n);(0|1);/", $line, $matches)) {
					$type = $matches[2];
					$default = $matches[5];
					if($type === 'i')
						$default = (int) $default;
					else if($type === 'f')
						$default = (float) $default;
					$this->columns[$matches[1]] = array(
						'type' => $type, 'auto' => $matches[4], 'default' => $default, 'key' => $matches[6], 'null' => $matches[7]
					);
					preg_match_all("/'.*?(?<!\\\\)'/", $matches[3], $this->columns[$matches[1]]['restraint']);
					$this->columns[$matches[1]]['restraint'] = $this->columns[$matches[1]]['restraint'][0];
				} else {
					return NULL;
				}
			}

			$this->columnsFile->releaseRead();
		}
		
		$this->columnsLockFile->releaseRead();
		
		return $this->columns;
	}
	
	function &getCursor()
	{
		$this->_loadEntries();

		if($this->cursor == NULL)
			$this->cursor = new fSQLTableCursor;
		
		$this->cursor->entries =& $this->entries;
		$this->cursor->num_rows = count($this->entries);
		$this->cursor->pos = 0;
		
		return $this->cursor;
	}
	
	function _loadEntries()
	{
		$this->dataLockFile->acquireRead();
		$lock = $this->dataLockFile->getHandle();
		
		$modified = fread($lock, 20);
		if($this->data_load === NULL || strcmp($this->data_load, $modified) < 0)
		{
			$entries = NULL;
			$this->data_load = $modified;

			$this->dataFile->acquireRead();
			$dataHandle = $this->dataFile->getHandle();

			$line = fgets($dataHandle);
			if(!preg_match("/^(\d+)/", $line, $matches))
			{
				$this->dataFile->releaseRead();
				$this->dataLockFile->releaseRead();
				return NULL;
			}
	
			$num_entries = rtrim($matches[1]);

			if($num_entries != 0)
			{
				$columns = array_keys($this->getColumns());
				$skip = false;
				$entries = array();
	
				for($i = 0;  $i < $num_entries; $i++) {
					$line = rtrim(fgets($dataHandle, 4096));

					if(!$skip) {
						if(preg_match("/^(\d+):(.*)$/", $line, $matches))
						{
							$row = $matches[1];
							$data = trim($matches[2]);
						}
						else
							continue;
					}
					else {
						$data .= $line;
					}
				
					if(!preg_match("/(-?\d+(?:\.\d+)?|'.*?(?<!\\\\)'|NULL);$/", $line)) {
						$skip = true;
						continue;
					} else {
						$skip = false;
					}
				
					preg_match_all("#(-?\d+(?:\.\d+)?|'.*?(?<!\\\\)'|NULL);#s", $data, $matches);
					for($m = 0; $m < count($matches[0]); $m++) {
						if($matches[1][$m] == 'NULL')
							$entries[$row][$columns[$m]] = NULL;
						else
							$entries[$row][$columns[$m]] = $matches[1][$m];
					}
				}
			}
			
			$this->entries = $entries;

			$this->dataFile->releaseRead();
		}
		
		$this->dataLockFile->releaseRead();

		return true;
	}
	
	function insertRow($data) {
		$this->_loadEntries();
		$this->entries[] = $data;
		$this->uncommited = true;
	}
	
	function updateRow($row, $data) {
		$this->_loadEntries();
		foreach($data as $key=> $value)
			$this->entries[$row][$key] = $value;
		$this->uncommited = true;
	}
	
	function deleteRow($row) {
		$this->_loadEntries();
		unset($this->entries[$row]);
		$this->uncommited = true;
	}

	function setColumns($columnDefs)
	{
		$this->columnsLockFile->acquireWrite();
		$lock = $this->columnsLockFile->getHandle();
		$modified = fread($lock, 20);
		
		$this->columns = $columnDefs;

		list($msec, $sec) = explode(' ', microtime());
		$this->columns_load = $sec.$msec;
		fseek($lock, 0, SEEK_SET);
		fwrite($lock, $this->columns_load);

		$this->columnsFile->acquireWrite();

		$toprint = $this->_printColumns($columnDefs);
		$columnsHandle = $this->columnsFile->getHandle();
		ftruncate($columnsHandle, 0);
		fwrite($columnsHandle, $toprint);
	
		$this->columnsFile->releaseWrite();
		$this->columnsLockFile->releaseWrite();
	}
	
	function commit()
	{
		if($this->uncommited === false)
			return;

		$this->dataLockFile->acquireWrite();
		$lock = $this->dataLockFile->getHandle();
		$modified = fread($lock, 20);
		
		if($this->data_load === NULL || strcmp($this->data_load, $modified) >= 0)
		{
			$toprint = count($this->entries)."\r\n";
			foreach($this->entries as $number => $entry) {
				$toprint .= $number.': ';
				foreach($entry as $key => $value) {
					if($value === NULL)
						$value = 'NULL';

					$toprint .= $value.';';
				}
				$toprint .= "\r\n";
			}
		} else {
			$toprint = "0\r\n";
		}
		
		list($msec, $sec) = explode(' ', microtime());
		$this->data_load = $sec.$msec;
		fseek($lock, 0, SEEK_SET);
		fwrite($lock, $this->data_load);
		
		$this->dataFile->acquireWrite();

		$dataHandle = $this->dataFile->getHandle();
		ftruncate($dataHandle, 0);
		fwrite($dataHandle, $toprint);
		
		$this->dataFile->releaseWrite();
		$this->dataLockFile->releaseWrite();

		$this->uncommited = false;
	}
	
	function rollback()
	{
		$this->data_load = 0;
		$this->uncommited = false;
	}

	function isReadLocked()
	{
		return $this->lock === 'r';
	}

	function readLock()
	{
		$success = $this->columnsLockFile->acquireRead() && $this->columnsFile->acquireRead()
	 		&& $this->dataLockFile->acquireRead() && $this->dataFile->acquireRead();
		if($success) {
			$this->lock = 'r';
			return true;
		} else {
			$this->unlock();  // release any locks that did work if at least one failed
			return false;
		}
	}

	function writeLock()
	{
		$success = $this->columnsLockFile->acquireRead() && $this->columnsFile->acquireRead()
			&& $this->dataLockFile->acquireRead() && $this->dataFile->acquireRead();
		if($success) {
			$this->lock = 'w';
			return true;
		} else {
			$this->unlock();  // release any locks that did work if at least one failed
			return false;
		}
	}

	function unlock()
	{
		if($this->lock === 'r')
		{
			$this->columnsLockFile->releaseRead();
			$this->columnsFile->releaseRead();
			$this->dataLockFile->releaseRead();
			$this->dataFile->releaseRead();
		}
		else if($this->lock === 'w')
		{
			$this->columnsLockFile->releaseWrite();
			$this->columnsFile->releaseWrite();
			$this->dataLockFile->releaseWrite();
			$this->dataFile->releaseWrite();
		}
		$this->lock = null;
		return true;
	}
}

class fSQLDatabase
{
	var $name = NULL;
	var $path_to_db = NULL;
	var $loadedTables = array();

	function close()
	{
		unset($this->name, $this->path_to_db, $this->loadedTables);
	}
	
	function createTable($table_name, $columns, $temporary = false)
	{
		$table = NULL;
		
		if(!$temporary) {
			$table = fSQLCachedTable::create($this->path_to_db, $table_name, $columns);
		} else {
			$table = fSQLTable::create($this->path_to_db, $table_name, $columns);
			$this->loadedTables[$table_name] =& $table;
		}
		
		return $table;
	}
	
	function &getTable($table_name)
	{
		if(!isset($this->loadedTables[$table_name])) {
			$table = new fSQLCachedTable($this->path_to_db, $table_name);
			$this->loadedTables[$table_name] = $table;
			unset($table);
		}
		
		return $this->loadedTables[$table_name];
	}
	
	function listTables()
	{
		$dir = opendir($this->path_to_db);

		$tables = array();
		while (false !== ($file = readdir($dir))) {
			if ($file != '.' && $file != '..' && !is_dir($file)) {
				if(substr($file, -12) == '.columns.cgi') {
					$tables[] = substr($file, 0, -12);
				}
			}
		}
		
		closedir($dir);
		
		return $tables;
	}
	
	function &loadTable($table_name)
	{		
		$table =& $this->getTable($table_name);
		if(!$table->exists())
			return NULL;

		$table->_loadEntries();

		$old_style_table = array('columns' => $table->getColumns(), 'entries' => $table->entries);
		return $old_style_table;
	}
	
	function renameTable($old_table_name, $new_table_name, &$new_db)
	{
		$oldTable =& $this->getTable($old_table_name);
		if($oldTable->exists()) {
			if(!$oldTable->temporary) {
				$newTable = $new_db->createTable($new_table_name,  $oldTable->getColumns());
				copy($oldTable->data_path.'.cgi', $newTable->data_path.'.cgi');
				copy($oldTable->data_path.'.lock.cgi', $newTable->data_path.'.lock.cgi');
				$this->dropTable($old_table_name);
			} else {
				$new_db->loadedTables[$new_table_name] =& $this->loadedTables[$old_table_name];
				unset($this->loadedTables[$old_table_name]);
			}

			return true;
		} else {
			return false;
		}
	}
	
	function dropTable($table_name)
	{
		$table =& $this->getTable($table_name);
		if($table->exists()) {
			if(!$table->temporary) {
				unlink($table->columns_path.'.cgi');
				unlink($table->columns_path.'.lock.cgi');
				unlink($table->data_path.'.cgi');
				unlink($table->data_path.'.lock.cgi');
			}
			
			$table = NULL;
			unset($this->loadedTables[$table_name]);
			unset($table);

			return true;
		} else {
			return false;
		}
	}
	
	function copyTable($name, $src_path, $dest_path)
	{
		copy($src_path.$name.'columns.cgi', $dest_path.$name.'columns.cgi');
		copy($src_path.$name.'data.cgi', $dest_path.$name.'data.cgi');
	}
}

class fSQLEnvironment
{
	var $updatedTables = array();
	var $lockedTables = array();
	var $databases = array();
	var $currentDB = NULL;
	var $error_msg = NULL;
	var $query_count = 0;
	var $cursors = array();
	var $data = array();
	var $affected = 0;
	var $insert_id = 0;
	var $auto = 1;
	
	var $allow_func = array('abs','acos','asin','atan2','atan','ceil','cos','crc32','exp','floor',
	   'ltrim','md5','pi','pow','rand','rtrim','round','sha1','sin','soundex','sqrt','strcmp','tan');
	var $custom_func = array('concat','concat_ws','count','curdate','curtime','database','dayofweek',
	   'dayofyear','elt','from_unixtime','last_insert_id', 'left','locate','log','log2','log10','lpad','max','min',
	   'mod','now','repeat','right','row_count','sign','substring_index','sum','truncate','unix_timestamp',
	   'weekday');
	var $renamed_func = array('conv'=>'base_convert','ceiling' => 'ceil','degrees'=>'rad2deg','format'=>'number_format',
	   'length'=>'strlen','lower'=>'strtolower','ln'=>'log','power'=>'pow','quote'=>'addslashes',
	   'radians'=>'deg2rad','repeat'=>'str_repeat','replace'=>'strtr','reverse'=>'strrev',
	   'rpad'=>'str_pad','sha' => 'sha1', 'substring'=>'substr','upper'=>'strtoupper');
	
	function define_db($name, $path)
	{
		$path = realpath($path);
		if($path === FALSE || !is_dir($path)) {
			if(@mkdir($path, 0777))
				$path = realpath($path);
		} else if(!is_readable($path) || !is_writeable($path)) {
			chmod($path, 0777);
		}

		if($path && substr($path, -1) != '/')
			$path .= '/';

		list($usec, $sec) = explode(' ', microtime());
		srand((float) $sec + ((float) $usec * 100000));
		
		if(is_dir($path) && is_readable($path) && is_writeable($path)) {
			$db = new fSQLDatabase; 
			$db->name = $name;
			$db->path_to_db = $path;
			$this->databases[$name] =& $db;
			unset($db);
			return true;
		} else {
			$this->_set_error("Path to directory for {$name} database is not valid.  Please correct the path or create the directory and chmod it to 777.");
			return false;
		}
	}
	
	function select_db($name)
	{
		if(isset($this->databases[$name])) {
			$this->currentDB =& $this->databases[$name];
			$this->currentDB->name = $name;
			unset($name);
			return true;
		} else {
			$this->_set_error("No database called {$name} found");
			return false;
		}
	}
	
	function close()
	{
		foreach (array_keys($this->databases) as $db_name ) {
			$this->databases[$db_name]->close();
		}
		unset($this->Columns, $this->cursors, $this->data, $this->currentDB, $this->databases, $this->error_msg);
	}
	
	function error()
	{
		return $this->error_msg;
	}

	function register_function($sqlName, $phpName)
	{
		$this->renamed_func[$sqlName] = $phpName;
		return TRUE;
	}
	
	function _set_error($error)
	{
		$this->error_msg = $error."\r\n";
	}
	
	function _error_table_not_exists($db_name, $table_name)
	{
		$this->error_msg = "Table {$db_name}.{$table_name} does not exist"; 
	}

	function _error_table_read_lock($db_name, $table_name)
	{
		$this->error_msg = "Table {$db_name}.{$table_name} is locked for reading only"; 
	}
	
	function &_load_table(&$db, $table_name)
	{		
		$table =& $db->loadTable($table_name);
		if(!$table)
			$this->_set_error("Unable to load table {$db->name}.{$table_name}");

		return $table;
	}
	
	function escape_string($string)
	{
		return str_replace(array("\\", "\0", "\n", "\r", "\t", "'"), array("\\\\", "\\0", "\\n", "\\", "\\t", "\\'"), $string);
	}
	
	function affected_rows()
	{
		return $this->affected;
	}

	function insert_id()
	{
		return $this->insert_id;
	}
	
	function num_rows($id)
	{
		if(isset($this->data[$id])) {
			return count($this->data[$id]);
		} else {
			return 0;
		}
	}
	
	function query_count()
	{
		return $this->query_count;
	}
	
	function _unlock_tables()
	{
		foreach (array_keys($this->lockedTables) as $index )
			$this->lockedTables[$index]->unlock();
		$this->lockedTables = array();
	}

	function _begin()
	{
		$this->auto = 0;
		$this->_unlock_tables();
		$this->_commit();
	}
	
	function _commit()
	{
		$this->auto = 1;
		foreach (array_keys($this->updatedTables) as $index ) {
			$this->updatedTables[$index]->commit();
		}
		$this->updatedTables = array();
	}
	
	function _rollback()
	{
		$this->auto = 1;
		foreach (array_keys($this->updatedTables) as $index ) {
			$this->updatedTables[$index]->rollback();
		}
		$this->updatedTables = array();
	}
	
	function query($query)
	{
		$query = trim($query);
		list($function, ) = explode(" ", $query);
		$this->query_count++;
		$this->error_msg = NULL;
		switch(strtoupper($function)) {
			case 'CREATE':		return $this->_query_create($query);
			case 'SELECT':		return $this->_query_select($query);
			//case "SEARCH":		return $this->_query_search($query);
			case 'INSERT':
			case 'REPLACE':	return $this->_query_insert($query);
			case 'UPDATE':		return $this->_query_update($query);
			case 'ALTER':		return $this->_query_alter($query);
			case 'DELETE':		return $this->_query_delete($query);
			case 'BEGIN':		return $this->_query_begin($query);
			case 'START':		return $this->_query_start($query);
			case 'COMMIT':		return $this->_query_commit($query);
			case 'ROLLBACK':	return $this->_query_rollback($query);
			case 'RENAME':	return $this->_query_rename($query);
			case 'TRUNCATE':	return $this->_query_truncate($query);
			case 'DROP':		return $this->_query_drop($query);
			case 'BACKUP':		return $this->_query_backup($query);
			case 'RESTORE':	return $this->_query_restore($query);
			case 'USE':		return $this->_query_use($query);
			case 'DESC':
			case 'DESCRIBE':	return $this->_query_describe($query);
			case 'SHOW':		return $this->_query_show($query);
			case 'LOCK':		return $this->_query_lock($query);
			case 'UNLOCK':		return $this->_query_unlock($query);
			//case 'MERGE':		return $this->_query_merge($query);
			//case 'IF':			return $this->_query_ifelse($query);
			default:			$this->_set_error('Invalid Query');  return NULL;
		}
	}
	
	function _query_begin($query)
	{
		if(preg_match("/\ABEGIN(?:\s+WORK)?\s*[;]?\Z/is", $query, $matches)) {			
			$this->_begin();
			return true;
		} else {
			$this->_set_error('Invalid Query');
			return NULL;
		}
	}
	
	function _query_start($query)
	{
		if(preg_match("/\ASTART\s+TRANSACTION\s*[;]?\Z/is", $query, $matches)) {			
			$this->_begin();
			return true;
		} else {
			$this->_set_error('Invalid Query');
			return NULL;
		}
	}
	
	function _query_commit($query)
	{
		if(preg_match("/\ACOMMIT\s*[;]?\Z/is", $query, $matches)) {
			$this->_commit();
			return true;
		} else {
			$this->_set_error('Invalid Query');
			return NULL;
		}
	}
	
	function _query_rollback($query)
	{
		if(preg_match("/\AROLLBACK\s*[;]?\Z/is", $query, $matches)) {
			$this->_rollback();
			return true;
		} else {
			$this->_set_error('Invalid Query');
			return NULL;
		}
	}
	
	function _query_create($query)
	{	
		if(preg_match("/\ACREATE(?:\s+(TEMPORARY))?\s+TABLE\s+(?:(IF\s+NOT\s+EXISTS)\s+)?`?(?:([A-Z][A-Z0-9\_]*)`?\.`?)?([A-Z][A-Z0-9\_]*?)`?(?:\s*\((.+)\)|\s+LIKE\s+(?:([A-Z][A-Z0-9\_]*)\.)?([A-Z][A-Z0-9\_]*))/is", $query, $matches)) { 
			
			list(, $temporary, $ifnotexists, $db_name, $table_name, $column_list) = $matches;
	
			if(!$table_name) {
				$this->_set_error("No table name specified"); 
				return NULL;
			}
			
			if(!$db_name)
				$db =& $this->currentDB;
			else
				$db =& $this->databases[$db_name];

			$table =& $db->getTable($table_name);
			if($table->exists()) {
				if(empty($ifnotexists)) {
					$this->_set_error("Table {$db->name}.{$table_name} already exists");
					return NULL;
				} else {
					return true;
				}
			}
			
			$temporary = !empty($temporary) ? true : false;			

			if(!isset($matches[6])) {
				//preg_match_all("/(?:(KEY|PRIMARY KEY|UNIQUE) (?:([A-Z][A-Z0-9\_]*)\s*)?\((.+?)\))|(?:`?([A-Z][A-Z0-9\_]*?)`?(?:\s+((?:TINY|MEDIUM|BIG)?(?:TEXT|BLOB)|(?:VAR)?(?:CHAR|BINARY)|INTEGER|(?:TINY|SMALL|MEDIUM|BIG)?INT|FLOAT|REAL|DOUBLE(?: PRECISION)?|BIT|BOOLEAN|DEC(?:IMAL)?|NUMERIC|DATE(?:TIME)?|TIME(?:STAMP)?|YEAR|ENUM|SET)(?:\((.+?)\))?)(\s+UNSIGNED)?(.*?)?(?:,|\)|$))/is", trim($column_list), $Columns);
				preg_match_all("/(?:(?:CONSTRAINT\s+(?:[A-Z][A-Z0-9\_]*\s+)?)?(KEY|INDEX|PRIMARY\s+KEY|UNIQUE)(?:\s+([A-Z][A-Z0-9\_]*))?\s*\((.+?)\))|(?:`?([A-Z][A-Z0-9\_]*?)`?(?:\s+((?:TINY|MEDIUM|LONG)?(?:TEXT|BLOB)|(?:VAR)?(?:CHAR|BINARY)|INTEGER|(?:TINY|SMALL|MEDIUM|BIG)?INT|FLOAT|REAL|DOUBLE(?: PRECISION)?|BIT|BOOLEAN|DEC(?:IMAL)?|NUMERIC|DATE(?:TIME)?|TIME(?:STAMP)?|YEAR|ENUM|SET)(?:\((.+?)\))?)\s*(UNSIGNED\s+)?(.*?)?(?:,|\)|$))/is", trim($column_list), $Columns);

				if(!$Columns) {
					$this->_set_error("Parsing error in CREATE TABLE query");
					return NULL;
				}
				
				$new_columns = array();

				for($c = 0; $c < count($Columns[0]); $c++) {
					//$column = str_replace("\"", "'", $column);
					if($Columns[1][$c])
					{
						if(!$Columns[3][$c]) {
							$this->_set_error("Parse Error: Excepted column name in \"{$Columns[1][$c]}\"");
							return null;
						}
						
						$keytype = strtolower($Columns[1][$c]);
						if($keytype === "index")
							$keytype = "key";
						$keycolumns = explode(",", $Columns[3][$c]);
						foreach($keycolumns as $keycolumn)
						{
							$new_columns[trim($keycolumn)]['key'] = $keytype{0}; 
						}
					}
					else
					{
						$name = $Columns[4][$c];
						$type = $Columns[5][$c];
						$options =  $Columns[8][$c];
						
						if(isset($new_columns[$name])) {
							$this->_set_error("Column '{$name}' redefined");
							return NULL;
						}
						
						$type = strtoupper($type);
						if(in_array($type, array('CHAR', 'VARCHAR', 'BINARY', 'VARBINARY', 'TEXT', 'TINYTEXT', 'MEDIUMTEXT', 'LONGTEXT', 'SET', 'BLOB', 'TINYBLOB', 'MEDIUMBLOB', 'LONGBLOB'))) {
							$type = 's';
						} else if(in_array($type, array('BIT','TINYINT', 'SMALLINT','MEDIUMINT','INT','INTEGER','BIGINT'))) {
							$type = 'i';
						} else if(in_array($type, array('FLOAT','REAL','DOUBLE','DOUBLE PRECISION','NUMERIC','DEC','DECIMAL'))) {
							$type = 'f';
						} else {
							switch($type)
							{
								case 'DATETIME':
									$type = 'dt';
									break;
								case 'DATE':
									$type = 'd';
									break;
								case 'ENUM':
									$type = 'e';
									break;
								case 'TIME':
									$type = 't';
									break;
								default:
									break;
							}
						}
						
						if(eregi("not null", $options)) 
							$null = 0;
						else
							$null = 1;
						
						if(eregi("AUTO_INCREMENT", $options))
							$auto = 1;
						else
							$auto = 0;
						
						if($type == 'e') {
							preg_match_all("/'.*?(?<!\\\\)'/", $Columns[6][$c], $values);
							$restraint = $values[0];
						} else {
							$restraint = NULL;
						}
				
						if(preg_match("/DEFAULT\s+((?:[\+\-]\s*)?\d+(?:\.\d+)?|NULL|(\"|').*?(?<!\\\\)(?:\\2))/is", $options, $matches)) {
							$default = $matches[1];
							if(!$null && strcasecmp($default, "NULL")) {
								if(preg_match("/\A(\"|')(.*)(?:\\1)\Z/is", $default, $matches)) {
									if($type == 'i')
										$default = intval($matches[2]);
									else if($type == 'f')
										$default = floatval($matches[2]);
									else if($type == 'e') {
										if(in_array($default, $restraint))
											$default = array_search($default, $restraint) + 1;
										else
											$default = 0;
									}
								} else {
									if($type == 'i')
										$default = intval($default);
									else if($type == 'f')
										$default = floatval($default);
									else if($type == 'e') {
										$default = intval($default);
										if($default < 0 || $default > count($restraint)) {
											$this->_set_error("Numeric ENUM value out of bounds");
											return NULL;
										}
									}
								}
							}
						} else if($type == 's')
							// The default for string types is the empty string 
							$default = "''";
						else
							// The default for dates, times, and number types is 0
							$default = 0;
				
						if(preg_match("/(PRIMARY KEY|UNIQUE(?: KEY)?)/is", $options, $keymatches)) {
							$keytype = strtolower($keymatches[1]);
							$key = $keytype{0}; 
						}
						else {
							$key = "n";
						}
						
						$new_columns[$name] = array('type' => $type, 'auto' => $auto, 'default' => $default, 'key' => $key, 'null' => $null, 'restraint' => $restraint);
					}
				}
			} else {
				$src_db_name = $matches[6];
				$src_table_name = $matches[7];

				if(!$src_db_name)
					$src_db =& $this->currentDB;
				else
					$src_db =& $this->databases[$src_db_name];

				$src_table =& $src_db->getTable($src_table_name);
				if($src_table->exists()) {
					$new_columns = $src_table->getColumns();
				} else {
					$this->_set_error("Table {$src_db->name}.{$src_table_name} doesn't exist");
					return null;
				}
			}
			
			$db->createTable($table_name, $new_columns, $temporary);
	
			return true;
		} else {
			$this->_set_error('Invalid CREATE query');
			return false;
		}
	}
	
	function _query_insert($query)
	{
		$this->affected = 0;

		// All INSERT/REPLACE queries are the same until after the table name
		if(preg_match("/\A((INSERT|REPLACE)(?:\s+(IGNORE))?\s+INTO\s+`?(?:([A-Z][A-Z0-9\_]*)`?\.`?)?([A-Z][A-Z0-9\_]*)`?)\s+(.+?)\s*[;]?\Z/is", $query, $matches)) { 
			list(, $beginning, $command, $ignore, $db_name, $table_name, $the_rest) = $matches;
		} else {
			$this->_set_error('Invalid Query');
			return NULL;
		}

		// INSERT...SELECT
		if(preg_match("/^SELECT\s+.+/is", $the_rest)) { 
			$id = $this->_query_select($the_rest);
			while($values = fsql_fetch_array($id)) {
				$this->query_count--;
				$this->_query_insert($beginning." VALUES('".join("', '", $values)."')");
			}
			fsql_free_result($id);
			unset ($id, $values);
			return TRUE;
		}
		
		if(!$db_name)
			$db =& $this->currentDB;
		else
			$db =& $this->databases[$db_name];
		
		$table =& $db->getTable($table_name);
		if(!$table->exists()) {
			$this->_error_table_not_exists($db->name, $table_name);
			return NULL;
		}
		elseif($table->isReadLocked()) {
			$this->_error_table_read_lock($db->name, $table_name);
			return null;
		}

		$tableColumns = $table->getColumns();
		$tableCursor =& $table->getCursor();

		$check_names = 1;
		$replace = !strcasecmp($command, 'REPLACE');

		// Column List present and VALUES list
		if(preg_match("/^\(`?(.+?)`?\)\s+VALUES\s*\((.+)\)/is", $the_rest, $matches)) { 
			$Columns = preg_split("/`?\s*,\s*`?/s", $matches[1]);
			$get_data_from = $matches[2];
		}
		// VALUES list but no column list
		else if(preg_match("/^VALUES\s*\((.+)\)/is", $the_rest, $matches)) { 
			$get_data_from = $matches[1];
			$Columns = $table->getColumnNames();
			$check_names = 0;
		}
		// SET syntax
		else if(preg_match("/^SET\s+(.+)/is", $the_rest, $matches)) { 
			$SET = explode(",", $matches[1]);
			$Columns= array();
			$data_values = array();
			
			foreach($SET as $set) {
				list($column, $value) = explode("=", $set);
				$Columns[] = trim($column);
				$data_values[] = trim($value);
			}
			
			$get_data_from = implode(",", $data_values);
		} else {
			$this->_set_error('Invalid Query');
			return NULL;
		}

		preg_match_all("/\s*(DEFAULT|AUTO|NULL|'.*?(?<!\\\\)'|(?:[\+\-]\s*)?\d+(?:\.\d+)?|[^$])\s*(?:$|,)/is", $get_data_from, $newData);
		$dataValues = $newData[1];
	
		if($check_names == 1) {
			$i = 0;
			$TableColumns = $table->getColumnNames();
			
			if(count($dataValues) != count($Columns)) {
				$this->_set_error("Number of inserted values and columns not equal");
				return null;
			}

			foreach($Columns as $col_name) {
				if(!in_array($col_name, $TableColumns)) {
					$this->_set_error("Invalid column name '{$col_name}' found");
					return NULL;
				} else {
					$Data[$col_name] = $dataValues[$i++];
				}
			}
			
			if(count($Columns) != count($TableColumns)) {
				foreach($TableColumns as $col_name) {
					if(!in_array($col_name, $Columns)) {
						$Data[$col_name] = "NULL";
					}
				}
			}
		}
		else
		{
			$countData = count($dataValues);
			$countColumns = count($Columns);
			
			if($countData < $countColumns) { 
				$Data = array_combine($Columns, array_pad($dataValues, $countColumns, "NULL"));
			} else if($countData > $countColumns) { 
				$this->_set_error("Trying to insert too many values");
				return NULL;
			} else {
				$Data = array_combine($Columns, $dataValues);
			}
		}
		
		$newentry = array();
		
		////Load Columns & Data for the Table
		foreach($tableColumns as $col_name => $columnDef)  {

			unset($delete);

			$data = trim($Data[$col_name]);				
			$data = strtr($data, array("$" => "\$", "\$" => "\\\$"));
			
			////Check for Auto_Increment
			if((empty($data) || !strcasecmp($data, "AUTO") || !strcasecmp($data, "NULL")) && $columnDef['auto'] == 1) {
				$tableCursor->last();
				$lastRow = $tableCursor->getRow();
				if($lastRow !== NULL)
					$this->insert_id = $lastRow[$col_name] + 1;
				else
					$this->insert_id = 1;
				$newentry[$col_name] = $this->insert_id;
			}
			///Check for NULL Values
			else if((!strcasecmp($data, "NULL") && !$columnDef['null']) || empty($data) || !strcasecmp($data, "DEFAULT")) {
				$newentry[$col_name] = $columnDef['default'];
			}
			else if($columnDef['type'] == 'i' && preg_match("/\A'(.*?(?<!\\\\))'\Z/is", $data, $matches)) {
				$newentry[$col_name] = intval($matches[1]);
			} else if($columnDef['type'] == 'f' && preg_match("/\A'(.*?(?<!\\\\))'\Z/is", $data, $matches)) {
				$newentry[$col_name] = floatval($matches[1]);
			} else if($columnDef['type'] == 'e') {
				if(in_array($data, $columnDef['restraint'])) {
					$newentry[$col_name]= array_search($data, $columnDef['restraint']) + 1;
				} else if(is_numeric($data))  {
					$val = intval($data);
					if($val >= 0 && $val <= count($columnDef['restraint']))
						$newentry[$col_name]= $val;
					else {
						$this->_set_error("Numeric ENUM value out of bounds");
						return NULL;
					}
				} else {
					$newentry[$col_name] = $columnDef['default'];
				}
			} else { $newentry[$col_name] = $data; }
	
			////See if it is a PRIMARY KEY or UNIQUE
			if($columnDef['key'] == 'p' || $columnDef['key'] == 'u') {
				if($replace) {
					$delete = array();
					$tableCursor->first();
					$n = 0;
					while(!$tableCursor->isDone()) {
						$row = $tableCursor->getRow();
						if($row[$col_name] == $newentry[$col_name]) { $delete[] = $n; }
						$tableCursor->next();
						$n++;
					}
					if(!empty($delete)) {
						foreach($delete as $d) {
							$this->affected++;
							$table->deleteRow($d);
						}
					}
				} else {
					$tableCursor->first();
					while(!$tableCursor->isDone()) {
						$row = $tableCursor->getRow();
						if($row[$col_name] == $newentry[$col_name]) {
							if(empty($ignore)) {
								$this->_set_error("Duplicate value for unique column '{$col_name}'");
								return NULL;
							} else {
								return TRUE;
							}
						}
						$tableCursor->next();
					}
				}
			}
		}

		$table->insertRow($newentry);
		
		if($this->auto)
			$table->commit();
		else if(!in_array($table, $this->updatedTables))
			$this->updatedTables[] =& $table;

		$this->affected++;
		
		return TRUE;
	}
	
	////Update data in the DB
	function _query_update($query) {
		$this->affected = 0;
		if(preg_match("/\AUPDATE\s+`?(?:([A-Z][A-Z0-9\_]*)`?\.`?)?([A-Z][A-Z0-9\_]*)`?\s+SET\s+(.*)(?:\s+WHERE .+)?\Z/is", $query, $matches)) {
			$matches[3] = preg_replace("/(.+?)(\s+WHERE)(.*)/is", "\\1", $matches[3]);
			$table_name = $matches[2];

			if(!$matches[1])
				$db =& $this->currentDB;
			else
				$db =& $this->databases[$matches[1]];
				
			$table =& $db->getTable($table_name);
			if(!$table->exists()) {
				$this->_error_table_not_exists($db->name, $table_name);
				return NULL;
			}
			elseif($table->isReadLocked()) {
				$this->_error_table_read_lock($db->name, $table_name);
				return null;
			}
		
			$columns = $table->getColumns();
			$cursor =& $table->getCursor();

			if(preg_match_all("/`?((?:\S+)`?\s*=\s*(?:'(?:.*?)'|\S+))`?\s*(?:,|\Z)/is", $matches[3], $sets)) {
				foreach($sets[1] as $set) {
					$s = preg_split("/`?\s*=\s*`?/", $set);
					$SET[] = $s;
					if(!isset($columns[$s[0]])) {
						$this->_set_error("Invalid column name '{$s[0]}' found");
						return null;
					}
				}
				unset($s);
			}
			else
				$SET[0] =  preg_split("/\s*=\s*/", $matches[3]);

			if(preg_match("/\s+WHERE\s+((?:.+)(?:(?:(?:\s+(AND|OR)\s+)?(?:.+)?)*)?)/is", $query, $sets))
			{
				$where = $this->_load_where($sets[1], false);
				if(!$where) {
					$this->_set_error('Invalid/Unsupported WHERE clause');
					return null;
				}
				
				$alter_columns = array();
				foreach($columns as $column => $columnDef) {
					if($columnDef['type'] == 'e')
						$alter_columns[] = $column;
				}

				$newentry = array();
				for($e = 0; !$cursor->isDone(); $e++, $cursor->next()) {
					
					unset($entry);
					$entry = $cursor->getRow();
					foreach($alter_columns as $column) {
						if($columns[$column]['type'] == 'e') {
							$i = $entry[$column];
							$entry[$column] = ($i == 0) ? "''" : $columns[$column]['restraint'][$i - 1];
						}
					}
					
					$proceed = "";
					for($i = 0; $i < count($where); $i++) {
						if($i > 0 && $where[$i - 1]["next"] == "AND")
							$proceed .= " && ".$this->_where_functions($where[$i], $entry, $table_name);
						else if($i > 0 && $where[$i - 1]["next"] == "OR")
							$proceed .= " || ".$this->_where_functions($where[$i], $entry, $table_name);
						else 
							$proceed .= intval($this->_where_functions($where[$i], $entry, $table_name) == 1);
					}
					eval("\$cont = $proceed;");
					if(!$cont) 
						continue;

					foreach($SET as $set) {
						list($column, $value) = $set;
						
						$columnDef = $columns[$column];
						
						if(!$columnDef['null'] && $value == "NULL")
							$value = $columnDef['default'];
						else if(preg_match("/\A([A-Z][A-Z0-9\_]*)/i", $value))
							$value = $entry[$value];
						else if($columnDef['type'] == 'i')
							if(preg_match("/\A'(.*?(?<!\\\\))'\Z/is", $value, $sets))
								$value = intval($sets[1]);
							else
								$value = intval($value);
						else if($columnDef['type'] == 'f')
							if(preg_match("/\A'(.*?(?<!\\\\))'\Z/is", $value, $sets))
								$value = floatval($sets[1]);
							else
								$value = floatval($value);
						else if($columnDef['type'] == 'e')
							if(in_array($value, $columnDef['restraint'])) {
								$value = array_search($value, $columnDef['restraint']) + 1;
							} else if(is_numeric($value))  {
								$value = intval($value);
								if($value < 0 || $value > count($columnDef['restraint'])) {
									$this->_set_error("Numeric ENUM value out of bounds");
									return NULL;
								}
							} else {
								$value = $columnDef['default'];
							}
						
						$newentry[$column] = $value;
					}
					
					$table->updateRow($e, $newentry);
					$this->affected++;
				}
			} else {
				$newentry = array();
				for($e = 0; !$cursor->isDone(); $e++, $cursor->next()) {
					unset($entry);
					$entry = $cursor->getRow();				
					foreach($SET as $set) {
						list($column, $value) = $set;
						
						$columnDef = $columns[$column];
						
						if(!$columnDef['null'] && $value == "NULL")
							$value = $columnDef['default'];
						else if(preg_match("/\A([A-Z][A-Z0-9\_]*)/i", $value))
							$value = $entry[$value];
						else if($columnDef['type'] == 'i')
							if(preg_match("/\A'(.*?(?<!\\\\))'\Z/is", $value, $sets))
								$value = intval($sets[1]);
							else
								$value = intval($value);
						else if($columnDef['type'] == 'f')
							if(preg_match("/\A'(.*?(?<!\\\\))'\Z/is", $value, $sets))
								$value = floatval($sets[1]);
							else
								$value = floatval($value);
						else if($columnDef['type'] == 'e')
							if(in_array($value, $columnDef['restraint'])) {
								$value = array_search($value, $columnDef['restraint']) + 1;
							} else if(is_numeric($value))  {
								$value = intval($value);
								if($value < 0 || $value > count($columnDef['restraint'])) {
									$this->_set_error("Numeric ENUM value out of bounds");
									return NULL;
								}
							} else {
								$value = $columnDef['default'];
							}
						
						$newentry[$column] = $value;
					}
					
					$table->updateRow($e, $newentry);
				}
				$this->affected = $e;
			}

			if($this->affected)
			{
				if($this->auto)
					$table->commit();
				else if(!in_array($table, $this->updatedTables))
					$this->updatedTables[] =& $table;
			}
			
			return TRUE;
		} else {
			$this->_set_error('Invalid UPDATE query');
			return NULL;
		}
	}
	
	/*
		MERGE INTO 
		  table_dest d
		USING
		  table_source s
		  table_source s
		ON
		  (s.id = d.id)
		when     matched then update set d.txt = s.txt
		when not matched then insert (id, txt) values (s.id, s.txt);
	*/
	function _query_merge($query)
	{
		if(preg_match("/\AMERGE\s+INTO\s+`?(?:([A-Z][A-Z0-9\_]*)`?\.`?)?([A-Z][A-Z0-9\_]*)`?(?:\s+AS\s+`?([A-Z][A-Z0-9\_]*)`?)?\s+USING\s+(?:([A-Z][A-Z0-9\_]*)\.)?([A-Z][A-Z0-9\_]*)(?:\s+AS\s+([A-Z][A-Z0-9\_]*))?\s+ON\s+(.+?)(?:\s+WHEN\s+MATCHED\s+THEN\s+(UPDATE .+?))?(?:\s+WHEN\s+NOT\s+MATCHED\s+THEN\s+(INSERT .+?))?/is", $query, $matches)) {
			list( , $dest_db_name, $dest_table, $dest_alias, $src_db_name, $src_table, $src_alias, $on_clause) = $matches;
			
			if(!$dest_db_name)
				$dest_db =& $this->currentDB;
			else
				$dest_db =& $this->databases[$dest_db_name];
				
			if(!$src_db_name)
				$src_db =& $this->currentDB;
			else
				$src_db =& $this->databases[$src_db_name];

			if(!($dest = $this->_load_table($dest_db, $dest_table))) {
				return NULL;
			}
			
			if(!($src = $this->_load_table($src_db, $src_table))) {
				return NULL;
			}
			
			if(preg_match("/(?:\()?(\S+)\s*=\s*(\S+)(?:\))?/", $on_clause, $on_pieces)) {
			
			} else {
				$this->_set_error('Invalid ON clause');
				return NULL;
			}
			
			$TABLES = explode(",", $matches[1]);
			foreach($TABLES as $table_name) {
				$table_name = trim($table_name);
				if(!$this->table_exists($table_name)) { $this->error_msg = "Table $table_name does not exist";  return NULL; }
				$table = $this->load_table($table_name);
				$tables[] = $table;
			}
			foreach($tables as $table) {
				if($table['columns'] != $tables[1]['columns']) { $this->error_msg = "Columns in the tables to be merged don't match"; return NULL; }
				foreach($table['entries'] as $tbl_entry) { $entries[] = $tbl_entry; }
			}
			$this->print_tbl($matches[2], $tables[1]['columns'], $entries);
			return TRUE;
		} else {
			$this->_set_error("Invalid MERGE query");
			return NULL;
		}
	}
 
	////Select data from the DB
	function _query_select($query)
	{
		$randval = rand();
		$selects = preg_split("/\s+UNION\s+/is", $query);
		$e = 0;
		foreach($selects as $select) {
			unset($matches, $where, $tables, $Columns);
			
			$simple = 1;
			$distinct = 0;
			
			if(preg_match("/(.*?)(?:WHERE|(LEFT|RIGHT|INNER)\s+JOIN|ORDER\s+BY|LIMIT)(.*?)/is",$select)) {
				$simple = 0;
				preg_match("/SELECT(?:\s+(ALL|DISTINCT(?:ROW)?))?(\s+RANDOM(?:\((?:\d+)\)?)?\s+|\s+)(.*?) FROM\s+((?:\w+(?:\s+)(?:AS\s+)?(?:\w+)?)(?:(?:\s)?(?:,)(?:\s)?(?:\w+\s+(?:AS\s+)?(?:\w+)?)(?:\s+WHERE|\s+(?:LEFT|RIGHT|INNER)\s+JOIN|\s+ORDER\s+BY|\s+LIMIT)?)*)/is", $select, $matches);
				$matches[4] = preg_replace("/(.+?)\s+(WHERE|(LEFT|RIGHT|INNER)|ORDER\s+BY|LIMIT)(.*)?/is", "\\1", $matches[4]);
			}
			else if(preg_match("/SELECT(?:\s+(ALL|DISTINCT(?:ROW)?))?(\s+RANDOM(?:\((?:\d+)\)?)?\s+|\s+)(.*?)\s+FROM\s+(.+)/is", $select, $matches)) { /* I got the matches, do nothing else */ }
			else { preg_match("/SELECT(?:\s+(ALL|DISTINCT(?:ROW)?))?(\s+RANDOM(?:\((?:\d+)\)?)?\s+|\s+)(.*)/is", $select, $matches); $matches[4] = "FSQL"; }

			$distinct = !strncasecmp($matches[1], "DISTINCT", 8);
			$has_random = $matches[2] != " ";
			
			if($simple == 0) {
				if(preg_match("/(LEFT|RIGHT|INNER)\s+JOIN/is",$select)) {
					@preg_match_all("/\s+(LEFT|RIGHT|INNER)?\s+JOIN\s+(.+?)\s+(USING|ON)\s*(?:(?:\((.*?)\))|(?:(?:\()?((?:\S+)\s*=\s*(?:\S+)(?:\))?)))/is", $select, $join);
					
					$join_name = trim($join[1][0]);
					if(!strcasecmp($join_name, "LEFT")) {
						$join_type = 1;
					} else if(!strcasecmp($join_name, "RIGHT")) {
						$join_type = 2;
					} else if(!strcasecmp($join_name, "INNER")) {
						$join_type = 3;
					} else {
						$join_type = 4;
					}
					
					for($i = 0; $i < count($join[0]); $i++) {
						$join_tables .= ", {$join[2][$i]}";
						if(!strcasecmp($join[3][$i], "ON")) { 
							$list = preg_split("/\s+AND\s+/i", $join[4][$i]);
						//	echo "<pre>";
						//	print_r($list);
						//	print_r($join);
						//	echo "</pre>";
							for($n = 0; $n < count($list); $n++) {
								if($join_type == 1) {   $join_where .= " AND {$list[$n]}";  }
								else if($join_type == 2) {
									preg_match("/(\S+)\s*=\s*(\S+)/", $list[$n], $right);
									$join_where .= " AND {$right[2]}={$right[1]}";
								}
								else if($join_type == 3) {	$join_where .= " AND ".str_replace("="," ~=~ ",$list[$n]);	}
							}
						}
						else if(!strcasecmp($join[3][$i], "USING")) { 
							$list = explode(",", $join[4][$i]);
							for($n = 0; $n < count($list); $n++) {
								if($join_type == 1) { $join_where .= " AND <<using>>.{$list[$n]}={$join[2][$i]}.{$list[$n]}"; }
								else if($join_type == 2) { $join_where .= " AND {$join[2][$i]}.{$list[$n]}=<<using>>.{$list[$n]}"; }
								else if($join_type == 3)	{ $join_where .= " AND <<using>>.{$list[$n]} ~=~ {$join[2][$i]}.{$list[$n]}"; }
							}
						}
					}
					$matches[4] .= $join_tables;
				}
			}
	
			//expands the tables and loads their data
			$tbls = explode(",", $matches[4]);
			foreach($tbls as $table_name) {
				if(preg_match("/(?:([A-Z][A-Z0-9\_]*)\.)?([A-Z][A-Z0-9\_]*)\s+(?:AS\s+)?([A-Z][A-Z0-9\_]*)/is", $table_name, $tbl_data)) {
					list(, $db_name, $table_name, $saveas) = $tbl_data;
					if(empty($db_name)) {
						$db_name = $this->currentDB->name;
					}
				} else if(preg_match("/(?:([A-Z][A-Z0-9\_]*)\.)?([A-Z][A-Z0-9\_]*)/is", $table_name, $tbl_data)) {
					list(, $db_name, $table_name) = $tbl_data;
					if(empty($db_name)) {
						$db_name = $this->currentDB->name;
						$saveas = $table_name;
					} else {
						$saveas = $db_name.'.'.$table_name;
					}
				} else {
					$this->_set_error("Invalid table list");
					return NULL;
				}
				
				$db =& $this->databases[$db_name];
				
				if(isset($join_where) && isset($last)) {
					$join_where = preg_replace("/<<using>>\.(\S+)(?:\s+)?(=| ~=~ )(?:\s+)?$saveas.(\\1)/is", "$last.\\1\\2$saveas.\\1", $join_where);
					$join_where = preg_replace("/$saveas\.(\S+)(?:\s+)?(=| ~=~ )(?:\s+)?<<using>>.(\\1)/is", "$saveas.\\1\\2$last.\\1", $join_where);
				}
				
				if(!($table = $this->_load_table($db, $table_name))) {
					return NULL;
				}
			
				$tables[$saveas] = $table;
				$last = $saveas;
			}
			
			if($simple == 0) {
				if(isset($join_where) && preg_match("/\s+WHERE\s+((?:.+)(?:(?:(\s+(?:AND|OR)\s+)?(?:.+)?)*)?)(?:\s+ORDER\s+BY|\s+LIMIT)?/is", $select, $first_where)) {
					$change = true;
					$first_where[1] .= $join_where;
				}
				else if(isset($join_where)) {
					$first_where[1] = "WHERE ".substr($join_where, 5);
					$change = true;
				}
				else if(preg_match("/\s+WHERE\s+((?:.+)(?:(?:(\s+(?:AND|OR)\s+)?(?:.+)?)*)?)(?:\s+ORDER\s+BY|\s+LIMIT)?/is", $select, $first_where)) {
					$change = false;
				}
	
				if($first_where[1]) {
					$where = $this->_load_where($first_where[1], $change);
					if(!$where) {
						$this->_set_error("Invalid/Unsupported WHERE clause");
						return null;
					}
				}
			}

			if(preg_match_all("/(?:\A|\s*,\s*)((?:(?:[A-Z][A-Z0-9\_]*\s*\(.*?\))|(?:(?:(?:[A-Z][A-Z0-9\_]*)\.)?(?:(?:[A-Z][A-Z0-9\_]*)|\*)))(?:\s+(?:AS\s+)?[A-Z][A-Z0-9\_]*)?)/is", trim($matches[3]), $columns)) {
				$Columns = $columns[1];
			}
			else { $Columns = explode(",", $matches[3]); }
			if(!$Columns) { return NULL; }

			$ColumnList = array();
			foreach($Columns as $column) {
				$column = trim($column);
				if($column == "") { continue; }
		
				if(preg_match("/[A-Z][A-Z0-9\_]*\s*\(.+?\)(\s+(?:AS\s+)?[A-Z][A-Z0-9\_]*)?/is", $column, $colmatches)) {
					$ColumnList[] = $colmatches[0];
				}
				else if(preg_match("/(?:([A-Z][A-Z0-9\_]*)\.)?((?:[A-Z][A-Z0-9\_]*)|\*)(?:\s+(?:AS\s+)?([A-Z][A-Z0-9\_]*))?/is",$column, $colmatches)) {
					list(, $name, $column) = $colmatches;
					if(isset($colmatches[3])) {
						$ColumnList[] = $colmatches[0];
					} else if(!empty($name) && $column == "*") {
						$ColumnList = array_merge($ColumnList, array_keys($tables[$name]['columns']));
					} else if($column == "*") {
						foreach($tables as $table => $tabledata) {
							$ColumnList = array_merge($ColumnList, array_keys($tabledata['columns']));
						}
					} else {
						$ColumnList[] = $column;
					}
				}
				else {
					$ColumnList[] = $column;
				}
			}
			$this->Columns[$randval] = $ColumnList;

			$this_random = array();
			$this->tosort = array();
			
			if($matches[4] != "FSQL") {
				if(preg_match("/\s+LIMIT\s+(?:(?:(\d+)\s*,\s*(\-1|\d+))|(\d+))/is", $select, $additional)) {
					list(, $limit_start, $limit_stop) = $additional;
					if($additional[3]) { $limit_stop = $additional[3]; $limit_start = 0; }
					else if($additional[2] != -1) { $limit_stop += $limit_start; }
				}
				else { $limit_start = 0; $limit_stop = -1; }

				if(preg_match("/\s+ORDER\s+BY\s+(?:(.*)\s+LIMIT|(.*))?/is", $select, $additional)) {
					if($additional[1] != "") { $ORDERBY = explode(", ", $additional[1]); }
					else { $ORDERBY = explode(", ", $additional[2]); }
					for($i = 0; $i < count($ORDERBY); $i++) {
						if(preg_match("/(\w+)(?:(?:\s+)(\w+))?/is", $ORDERBY[$i], $additional)) {
							if(!$additional[2]) { $additonal[2] = "ASC"; }
							$this->tosort[] = array("key" => $additional[1], "sortby" => strtoupper($additional[2]));
						}
					}
				}

				$data = array();

				$table_names = array_keys($tables);
				$alter_columns = array();
				foreach($table_names as $tname) {
					$table =& $tables[$tname];
					if(!$table['entries']) { continue; }
					if(!empty($this->tosort)) { usort($table['entries'], array($this, "_orderBy")); }
					foreach($table['columns'] as $column => $columnDef) {
						if($columnDef['type'] == 'e') {
							$alter_columns[] = $column;
						}
					}
				}
				
				if(isset($where)) {
					$done = array();
					$reset = 0;
					foreach($tables as $tname => $table) {
						if($reset == 1) { $e = 0; $reset = 0; }
						if(!$table['entries']) { continue; }
						foreach($table['entries'] as $entry) {

							$tableColumns =& $table['columns'];
							foreach($alter_columns as $column) {
								if($tableColumns[$column]['type'] == 'e') {
									$i = $entry[$column];
									$entry[$column] = ($i == 0) ? "''" : $tableColumns[$column]['restraint'][$i - 1];
								}
							}
							
							$proceed = "";
							foreach($where as $i => $line) {
								if(!$line || !is_array($line)) { continue; }
								$temp = $where[$i]; 
								$temp_table = $temp['table']; 
								$var = $temp['var'];
								if(!preg_match("/'(.+?)'/is", $temp['value']) && 
									  preg_match("/(\S+)\.(\S+)/", $temp['value'], $value) && 
									  ( $temp_table == $tname || $tname == $value[1] ) && 
									  ($temp['operator'] == "=" || $temp['operator'] == " ~=~ ")) {
									  
								  list(, $value_tbl, $value_col) = $value;
								  if($temp_table == $tname) {
									  list(, $value_tbl, $value_col) = $value;
									  $var_table= $temp_table;	
									  $var = $temp['var'];
									}
									else {
										list(, $var_table, $var) = $value;
									  $value_tbl = $temp_table;	
									  $value_col = $temp['var'];
									}
									//echo "$tname -> $value_tbl = Sorting with this other table:\n";
									//print_r($tables[$value_tbl]['entries']);
									foreach($tables[$value_tbl]['entries'] as $other_entry) {
										//echo "\nif(".strval(trim($entry[$var], "'"))." == ".strval($other_entry[$value_col]).")\n";
										if(strval(trim($entry[$var], "'")) == strval(trim($other_entry[$value_col],"'"))) {
											$entry = array_merge($entry, $other_entry);
											//print_r($entry);
											if($temp['operator'] == " ~=~ ") { $proceed .= "1"; }
										}
										if($temp['operator'] == "=") { $proceed .= "1"; }
										$reset = 1;
									}
									$done[] = $i;
								}
								else {	$proceed .= ($this->_where_functions($where[$i], $entry, $tname)) ? "1" : "0";	}
								
								if(isset($where[$i + 1]) && is_array($where[$i + 1])) {
									if($where[$i]["next"] == "AND") {
										$proceed .= " && ";
									} else if($where[$i]["next"] == "OR") {
										$proceed .= " || ";
									}
								}
							}
							eval("if(!($proceed)) { \$go = true; } else { \$go = false; }");
							if($go == true) { continue; }
	
							if($distinct && in_array($entry, $data)) { continue; }
							if($has_random) { $this_random[] = $e; }
							if($e >= $limit_start) {
								if($limit_stop == -1 || $e < $limit_stop) { $data[$e] = $entry; }
								else if($limit_stop != -1 && $e >= $limit_stop) { break; }
							}
							$e++;
						}
						
						if(count($done) > 0 ) {
							foreach($done as $index) { unset($where[$index]); }
							unset($done);
						}
					}
				} else {
					foreach($tables as $tname => $table) {
						if(!$table['entries']) { continue; }
						foreach($table['entries'] as $entry) {
							$tableColumns =& $table['columns'];
							foreach($alter_columns as $column) {
								if($tableColumns[$column]['type'] == 'e') {
									$i = $entry[$column];
									$entry[$column] = ($i == 0) ? "''" : $tableColumns[$column]['restraint'][$i - 1];
								}
							}
							
							if($distinct && in_array($entry, $data)) { continue; }
							if($has_random) { $this_random[] = $e; }
							if($e >= $limit_start) {
								if($limit_stop == -1 || $e < $limit_stop) { $data[$e] = $entry; }
								else if($limit_stop != -1 && $e >= $limit_stop) { break; }
							}
							$e++;
						}
					}
				}
			}
			else { $data[$e++] = $entry; }

			if(!empty($data) && $has_random && preg_match("/\s+RANDOM(?:\((\d+)\)?)?\s+/is", $select, $additional)) {
				if(!$additional[1]) { $additional[1] = 1; }
				if($additional[1] >= count($this_random)) { $results = $data; }
				else {
					$random = array_rand($this_random, $additional[1]);
					if(is_array($random)) {	for($i = 0; $i < count($random); $i++) { $results[] = $data[$random[$i]]; }	}
					else { $results[] = $data[$random]; }
				}
				unset($data);
				$data = $results;
			}
		}

		$this->cursors[$randval] = array(0, 0);
		$this->data[$randval] = $data;
		return $randval;
	}
	
	function _load_functions($result_id, $section, $entry, $newentry) {
		if(preg_match("/([A-Z][A-Z0-9\_]*)\s*\((.+?)?\)(?:\s+(?:AS\s+)?([A-Z][A-Z0-9\_]*))?/is",$section,$functions)) {
			$in_a_class = 0;
			$is_grouping = 0;
			$function = strtolower($functions[1]);

			$alias = (!empty($functions[3])) ? $functions[3] : $functions[0];

			if(isset($this->renamed_func[$function])) {
				$function = $this->renamed_func[$function];
			} else if(in_array($function, $this->custom_func)) {
				if(in_array($function, array("sum", "max", "min", "count")))
					$is_grouping = 1;
				$in_a_class = 1;
				$function = "_fsql_functions_".$function;
			} else if(!in_array($function, $this->allow_func)) {
				$this->_set_error("Call to unknown SQL function");
				continue;
			}
			
			if($functions[2] != "") {
				$parameter = explode(",", $functions[2]);
				foreach($parameter as $param) {
					if(!preg_match("/'(.+?)'/is", $param) && !is_numeric($param) && $is_grouping == 0) {
						if(preg_match("/(?:\S+)\.(?:\S+)/", $param)) { list($name, $var) = explode(".", $param); }
						else { $var = $param; }
						$parameters[] = $entry[$var];
					}
					else { $parameters[] = $param; }
				}
				if($is_grouping) {
					$parameters[] = $result_id;
				}
				if($in_a_class == 0) { $newentry[$alias] = call_user_func_array($function, $parameters); }
				else { $newentry[$alias] = call_user_func_array(array($this,$function), $parameters); }
			}
			else {
				if($in_a_class == 0) { $newentry[$alias] = call_user_func($function); }
				else { $newentry[$alias] = call_user_func(array($this,$function)); }
			}
			return $newentry;
		}
		return NULL;
	}
	
	function _load_where($statement, $change)
	{
		if($statement) {
			preg_match_all("/(\S+?)\s*(~=~|!=|<>|>=|<=|>|<|=|(?:NOT\s+)?IN|(?:NOT\s+)?R?LIKE|(?:NOT\s+)?REGEXP)\s*('.*?'|\S+)(?:\s+(AND|OR)\s+)?/is", $statement, $WHERE);
			
			$where_count = count($WHERE[0]);
			if($where_count == 0)
				return null;

			for($i = 0; $i < $where_count; $i++) {
				$next = "";
				
				$var = $WHERE[1][$i];
				$operator = $WHERE[2][$i];
				$value = $WHERE[3][$i];

				if(isset($WHERE[4][$i])) {
					$next = $WHERE[4][$i];
				}
				
				if($operator == "<>") { $operator = "!="; }
				else if($change && $operator == "=") { $operator = " ~=~ "; }

				if(!$next || (strtoupper($next) != "AND" && strtoupper($next) != "OR")) { $next = ""; }
				
				if(!preg_match("/'(.+?)'/is", $var) && !is_numeric($var)) {
					if(preg_match("/(?:\S+)\.(?:\S+)/", $var) && !preg_match("/(.+?)\((.+?)?\)/is",$var) ) {
						list($from_tbl, $new_var) = explode(".", $var);
					}
					else { $from_tbl = NULL; $new_var = $var; }
				} else { $from_tbl = NULL; $new_var = $var; }
				$where[] = array('table' => $from_tbl, 'var' => $new_var, 'value' => $value, 'operator' => strtoupper($operator), 'next' => strtoupper($next));
			}
			return $where;
		}
		return NULL;
	 }
 
	function _where_functions($statement, $entry = NULL, $tname = NULL)
	{
		$operator = $statement['operator'];	
		foreach($statement as $name => $section) {
			if($name == "table" && $section && $section != $tname) { return NULL; }
			else if($name == "table" || $name == "next" || $name == "operator") { continue; }

			if(preg_match("/(.+?)\((.+?)?\)/is",$section,$functions)) {
				$in_a_class = 0;
				$function = strtolower($functions[1]);
				
				if(isset($this->renamed_func[$function])) {
					$function = $this->renamed_func[$function];
				} else if(in_array($function, $this->custom_func)) {
					$in_a_class = 1;
					$function = "_fsql_functions_".$function;
				} else if(!in_array($function, $this->allow_func)) {
					$this->_set_error("Call to unknown SQL function");
					continue;
				}
				
				if($functions[2] != "") {
					$parameter = explode(",", $functions[2]);
					foreach($parameter as $param) {
						$param = trim($param);
						if(!preg_match("/'(.+)'/is", $param) && !is_numeric($param)) {
							if(preg_match("/(?:\S+)\.(?:\S+)/", $param)) { list( , $new_var) = explode(".", $param); }
							else { $new_var = $param; }
							$parameters[] = $entry[$new_var];
						} else {
							$parameters[] = $param;
						}
					}
					if($in_a_class == 0) { $$name = call_user_func_array($function, $parameters); }
					else { $$name = call_user_func_array(array($this,$function), $parameters); }
				} else { 
					if($in_a_class == 0) { $$name = call_user_func_array($function, $parameters); }
					else { $$name = call_user_func_array(array($this,$function), $parameters); }
				}
			}
			else if($name == "var") {
				if(preg_match("/'(.*?)(?<!\\\\)'/is", $section, $matches))
					$var = $matches[1];
				else
					$var = $entry[$section];
			} else if($name == "value") { $value = $section; }
		}
		if(preg_match("/'(.*?)(?<!\\\\)'/is", $var, $matches)) { $var = $matches[1]; }
		if(preg_match("/'(.*?)(?<!\\\\)'/is", $value, $matches)) { $value = $matches[1]; }
		if($operator == "=" || $operator == " ~=~ ") { $operator = "=="; }
		
		$ops = preg_split("/\s+/", $operator);
		if($ops[0] == "NOT") {
			$operator = $ops[1];
			$not = 1;
		} else {
			$not = 0;
		}
		
		if($operator == "LIKE") {
			$value = preg_quote($value);
			$value = preg_replace("/(?<!\\\\)_/", ".", $value);
			$value = preg_replace("/(?<!\\\\)%/", ".*", $value);
			$value = str_replace("\\\\_", "_", $value);
			$value = str_replace("\\\\%", "%", $value);
			$return = (preg_match("/\A{$value}\Z/is", $var)) ? 1 : 0;
			$return ^= $not;
		} else if($operator == "REGEXP" || $operator == "RLIKE") {
			$return = (eregi($value, $var)) ? 1 : 0;
			$return ^= $not;
		}
		/*else if($operator == "IN") {
			eval("\$return = (in_array(\$var, array$value)) ? 1 : 0;");
			$return ^= $not;
		} */
		else
			eval("\$return = (\$var $operator \$value) ? 1 : 0;");

		return $return;
	}
 
	function _orderBy($a, $b)
	{
		foreach($this->tosort as $tosort) {
			extract($tosort);
			$a[$key] = preg_replace("/^'(.+?)'$/", "\\1", $a[$key]);
			$b[$key] = preg_replace("/^'(.+?)'$/", "\\1", $b[$key]);
			if (($a[$key] > $b[$key] && $sortby == "ASC") || ($a[$key] < $b[$key] && $sortby == "DESC")) {
				return 1;
			} else if (($a[$key] < $b[$key] && $sortby == "ASC") || ($a[$key] > $b[$key] && $sortby == "DESC")) {
				return -1;
			}
		}
	}
	
	////Delete data from the DB
	function _query_delete($query)
	{
		$this->affected  = 0;
		if(preg_match("/\ADELETE\s+FROM\s+(?:([A-Z][A-Z0-9\_]*)\.)?([A-Z][A-Z0-9\_]*)(?:\s+(WHERE\s+.+))?\s*[;]?\Z/is", $query, $matches)) {
			list(, $db_name, $table_name) = $matches;
		
			if(!$db_name)
				$db =& $this->currentDB;
			else
				$db =& $this->databases[$db_name];
			
			$table =& $db->getTable($table_name);
			if(!$table->exists()) {
				$this->_error_table_not_exists($db->name, $table_name);
				return NULL;
			}
			elseif($table->isReadLocked()) {
				$this->_error_table_read_lock($db->name, $table_name);
				return null;
			}
			
			$columns = $table->getColumns();
			$cursor =& $table->getCursor();
			
			if($cursor->isDone())
				return true;
				
			if(isset($matches[3]) && preg_match("/^WHERE ((?:.+)(?:(?:(?:\s+(AND|OR)\s+)?(?:.+)?)*)?)/is", $matches[3], $first_where))
			{
				$where = $this->_load_where($first_where[1], false);
				if(!$where) {
					$this->_set_error("Invalid/Unsupported WHERE clause");
					return null;
				}

				$alter_columns = array();
				foreach($columns as $column => $columnDef) {
					if($columnDef['type'] == 'e')
						$alter_columns[] = $column;
				}
			
				for($k = 0; !$cursor->isDone(); $k++, $cursor->next()) {
					
					$entry = $cursor->getRow();
					foreach($alter_columns as $column) {
						if($columns[$column]['type'] == 'e') {
							$i = $entry[$column];
							$entry[$column] = ($i == 0) ? "''" : $columns[$column]['restraint'][$i - 1];
						}
					}
					
					$proceed = "";
					for($i = 0; $i < count($where); $i++) {
						if($i > 0 && $where[$i - 1]["next"] == "AND") {
							$proceed .= " && ".$this->_where_functions($where[$i], $entry, $table_name);
						}
						else if($i > 0 && $where[$i - 1]["next"] == "OR") {
							$proceed .= " || ".$this->_where_functions($where[$i], $entry, $table_name);
						}
						else {
							$proceed .=  intval($this->_where_functions($where[$i], $entry, $table_name) == 1);
						}
					}
					eval("\$cond = $proceed;");
					if(!($cond))
						continue;
					
					$table->deleteRow($k);
				
					$this->affected++;
				}
			} else {
				for($k = 0; !$cursor->isDone(); $k++, $cursor->next())
					$table->deleteRow($k);
				$this->affected = $k;
			}
			
			if($this->affected)
			{
				if($this->auto)
					$table->commit();
				else if(!in_array($table, $this->updatedTables))
					$this->updatedTables[] =& $table;
			}

			return TRUE;
		} else {
			$this->_set_error("Invalid DELETE query");
			return null;
		}
	}
 
	function _query_alter($query)
	{
		if(preg_match("/\AALTER\s+TABLE\s+`?(?:([A-Z][A-Z0-9\_]*)`?\.`?)?([A-Z][A-Z0-9\_]*)`?\s+(.*)/is", $query, $matches)) {
			list(, $db_name, $table_name, $changes) = $matches;
			
			if(!$db_name)
				$db =& $this->currentDB;
			else
				$db =& $this->databases[$db_name];
			
			$tableObj =& $db->getTable($table_name);
			if(!$tableObj->exists()) {
				$this->_error_table_not_exists($db->name, $table_name);
				return NULL;
			}
			elseif($tableObj->isReadLocked()) {
				$this->_error_table_read_lock($db->name, $table_name);
				return null;
			}
			$columns =  $tableObj->getColumns();
			
			$table = $this->_load_table($db, $table_name);
			
			preg_match_all("/(?:ADD|ALTER|CHANGE|DROP|RENAME).*?(?:,|\Z)/is", trim($changes), $specs);
			for($i = 0; $i < count($specs[0]); $i++) {
				if(preg_match("/\AADD\s+(?:CONSTRAINT\s+`?[A-Z][A-Z0-9\_]*`?\s+)?PRIMARY\s+KEY\s*\((.+?)\)/is", $specs[0][$i], $matches)) {
					$columnDef =& $columns[$matches[1]];
					
					foreach($columns as $name => $column) {
						if($column['key'] == 'p') {
							$this->_set_error("Primary key already exists");
							return NULL;
						}
					}
					
					$columnDef['key'] = 'p';
					$tableObj->setColumns($columns);
					
					return true;
				} else if(preg_match("/\ACHANGE(?:\s+(?:COLUMN))?\s+`?([A-Z][A-Z0-9\_]*)`?\s+(?:SET\s+DEFAULT ((?:[\+\-]\s*)?\d+(?:\.\d+)?|NULL|(\"|').*?(?<!\\\\)(?:\\3))|DROP\s+DEFAULT)(?:,|;|\Z)/is", $specs[0][$i], $matches)) {
					$columnDef =& $columns[$matches[1]];
					if(isset($matches[2]))
						$default = $matches[2];
					else
						$default = "NULL";
					
					if(!$columnDef['null'] && strcasecmp($default, "NULL")) {
						if(preg_match("/\A(\"|')(.*)(?:\\1)\Z/is", $default, $matches)) {
							if($columnDef['type'] == 'i')
								$default = intval($matches[2]);
							else if($columnDef['type'] == 'f')
								$default = floatval($matches[2]);
							else if($columnDef['type'] == 'e') {
								if(in_array($default, $columnDef['restraint']))
									$default = array_search($default, $columnDef['restraint']) + 1;
								else
									$default = 0;
							}
						} else {
							if($columnDef['type'] == 'i')
								$default = intval($default);
							else if($columnDef['type'] == 'f')
								$default = floatval($default);
							else if($columnDef['type'] == 'e') {
								$default = intval($default);
								if($default < 0 || $default > count($columnDef['restraint'])) {
									$this->_set_error("Numeric ENUM value out of bounds");
									return NULL;
								}
							}
						}
					} else if(!$columnDef['null']) {
						if($columnDef['type'] == 's')
							// The default for string types is the empty string 
							$default = "''";
						else
							// The default for dates, times, and number types is 0
							$default = 0;
					}
					
					$columnDef['default'] = $default;
					$tableObj->setColumns($columns);
					
					return true;
				} else if(preg_match("/\ADROP\s+PRIMARY\s+KEY/is", $specs[0][$i], $matches)) {
					$found = false;
					foreach($columns as $name => $column) {
						if($column['key'] == 'p') {
							$columns[$name]['key'] = 'n';
							$found = true;
						}
					}
					
					if($found) {
						$tableObj->setColumns($columns);
						return true;
					} else {
						$this->_set_error("No primary key found");
						return NULL;
					}
				}
				else if(preg_match("/\ARENAME\s+(?:TO\s+)?`?(?:([A-Z][A-Z0-9\_]*)`?\.`?)?([A-Z][A-Z0-9\_]*)`?/is", $specs[0][$i], $matches)) {
					list(, $new_db_name, $new_table_name) = $matches;

					if(!$new_db_name)
						$new_db =& $this->currentDB;
					else
						$new_db =& $this->databases[$new_db_name];
				
					$new_table =& $new_db->getTable($new_table_name);
					if($new_table->exists()) {
						$this->_set_error("Destination table {$new_db_name}.{$new_table_name} already exists");
						return NULL;
					}
				
					return $db->renameTable($old_table_name, $new_table_name, $new_db);
				}
				else {
					$this->_set_error("Invalid ALTER query");
					return null;
				}
			}
		} else {
			$this->_set_error("Invalid ALTER query");
			return null;
		}
	}

	function _query_rename($query)
	{
		if(preg_match("/\ARENAME\s+TABLE\s+(.*)\s*[;]?\Z/is", $query, $matches)) {
			$tables = explode(",", $matches[1]);
			foreach($tables as $table) {
				list($old, $new) = preg_split("/\s+TO\s+/i", trim($table));
				
				if(preg_match("/`?(?:([A-Z][A-Z0-9\_]*)`?\.`?)?([A-Z][A-Z0-9\_]*)`?/is", $old, $table_parts)) {
					list(, $old_db_name, $old_table_name) = $table_parts;
					
					if(!$old_db_name)
						$old_db =& $this->currentDB;
					else
						$old_db =& $this->databases[$old_db_name];
				} else {
					$this->_set_error("Parse error in table listing");
					return NULL;
				}
				
				if(preg_match("/(?:([A-Z][A-Z0-9\_]*)\.)?([A-Z][A-Z0-9\_]*)/is", $new, $table_parts)) {
					list(, $new_db_name, $new_table_name) = $table_parts;
					
					if(!$new_db_name)
						$new_db =& $this->currentDB;
					else
						$new_db =& $this->databases[$new_db_name];
				} else {
					$this->_set_error("Parse error in table listing");
					return NULL;
				}
				
				$old_table =& $old_db->getTable($old_table_name);
				if(!$old_table->exists()) {
					$this->_error_table_not_exists($old_db_name, $old_table_name);
					return NULL;
				}
				elseif($old_table->isReadLocked()) {
					$this->_error_table_read_lock($old_db_name, $old_table_name);
					return null;
				}
				
				$new_table =& $new_db->getTable($new_table_name);
				if($new_table->exists()) {
					$this->_set_error("Destination table {$new_db_name}.{$new_table_name} already exists");
					return NULL;
				}
				
				return $old_db->renameTable($old_table_name, $new_table_name, $new_db);
			}
			return TRUE;
		} else {
			$this->_set_error("Invalid RENAME query");
			return null;
		}
	}
	
	function _query_drop($query)
	{
		if(preg_match("/\ADROP(?:\s+(TEMPORARY))?\s+TABLE(?:\s+(IF EXISTS))?\s+(.*)\s*[;]?\Z/is", $query, $matches)) {
			$temporary = !empty($matches[1]);
			$ifexists = !empty($matches[2]);
			$tables = explode(",", $matches[3]);
	
			foreach($tables as $table) {
				if(preg_match("/`?(?:([A-Z][A-Z0-9\_]*)`?\.`?)?([A-Z][A-Z0-9\_]*)`?/is", $table, $table_parts)) {
					list(, $db_name, $table_name) = $table_parts;
					
					if(!$db_name)
						$db =& $this->currentDB;
					else
						$db =& $this->databases[$db_name];
				
					$table =& $db->getTable($table_name);
					if($table->isReadLocked()) {
						$this->_error_table_read_lock($db->name, $table_name);
						return null;
					}

					$existed = $db->dropTable($table_name);
					if(!$ifexists && !$existed) {
						$this->_error_table_not_exists($db->name, $table_name); 
						return null;
					}
				} else {
					$this->_set_error("Parse error in table listing");
					return NULL;
				}
			}
			return TRUE;
		} else if(preg_match("/\ADROP\s+DATABASE(?:\s+(IF EXISTS))?\s+`?([A-Z][A-Z0-9\_]*)`?s*[;]?\Z/is", $query, $matches)) {
			$ifexists = !empty($matches[1]);
			$db_name = $matches[2];
			
			if(!$ifexists && !isset($this->databases[$db_name])) {
				$this->_set_error("Database '{$db_name}' does not exist"); 
				return null;
			} else if(!isset($this->databases[$db_name])) {
				return true;
			}
			
			$db =& $this->databases[$db_name];
	
			$tables = $db->listTables();
			
			foreach($tables as $table) {
				$db->dropTable($table_name);
			}
			
			unset($this->databases[$db_name]);
			
			return TRUE;
		} else {
			$this->_set_error("Invalid DROP query");
			return null;
		}
	}
	
	function _query_truncate($query)
	{
		if(preg_match("/\ATRUNCATE\s+TABLE\s+(.*)[;]?\Z/is", $query, $matches)) {
			$tables = explode(",", $matches[1]);
			foreach($tables as $table) {
				if(preg_match("/`?(?:([A-Z][A-Z0-9\_]*)`?\.`?)?([A-Z][A-Z0-9\_]*)`?/is", $table, $matches)) {
					list(, $db_name, $table_name) = $matches;
				
					if(!$db_name)
						$db =& $this->currentDB;
					else
						$db =& $this->databases[$db_name];
					
					$table =& $db->getTable($table_name);
					if($table->exists()) {
						if($table->isReadLocked()) {
							$this->_error_table_read_lock($db->name, $table_name);
							return null;
						}
						$columns = $table->getColumns();
						$db->dropTable($table_name);
						$db->createTable($table_name, $columns);
					} else {
						return NULL;
					}
				} else {
					$this->_set_error("Parse error in table listing");
					return NULL;
				}
			}
		} else {
			$this->_set_error("Invalid TRUNCATE query");
			return NULL;
		}
		
		return true;
	}
	
	function _query_backup($query)
	{
		if(!preg_match("/\ABACKUP TABLE (.*?) TO '(.*?)'\s*[;]?\Z/is", $query, $matches)) {
			if(substr($matches[2], -1) != "/")
				$matches[2] .= '/';
			
			$tables = explode(",", $matches[1]);
			foreach($tables as $table) {
				if(preg_match("/`?(?:([A-Z][A-Z0-9\_]*)`?\.`?)?([A-Z][A-Z0-9\_]*)`?/is", $table, $table_name_matches)) {
					list(, $db_name, $table_name) = $table_name_matches;
					
					if(!$db_name)
						$db =& $this->currentDB;
					else
						$db =& $this->databases[$db_name];
					
					$db->copyTable($table_name, $db->path_to_db, $matches[2]);
				} else {
					$this->_set_error("Parse error in table listing");
					return NULL;
				}
			}
		} else {
			$this->_set_error("Invalid Query");
			return NULL;
		}
	}
	
	function _query_restore($query)
	{
		if(!preg_match("/\ARESTORE TABLE (.*?) FROM '(.*?)'\s*[;]?\s*\Z/is", $query, $matches)) {
			if(substr($matches[2], -1) != "/")
				$matches[2] .= '/';
			
			$tables = explode(",", $matches[1]);
			foreach($tables as $table) {
				if(preg_match("/`?(?:([A-Z][A-Z0-9\_]*)`?\.`?)?([A-Z][A-Z0-9\_]*)`?/is", $table, $table_name_matches)) {
					list(, $db_name, $table_name) = $table_name_matches;
					
					if(!$db_name)
						$db =& $this->currentDB;
					else
						$db =& $this->databases[$db_name];
					
					$db->copyTable($table_name, $matches[2], $db->path_to_db);
				} else {
					$this->_set_error("Parse error in table listing");
					return NULL;
				}
			}
		} else {
			$this->_set_error("Invalid Query");
			return NULL;
		}
	}
 
	function _query_show($query)
	{
		if(preg_match("/\ASHOW\s+TABLES(?:\s+FROM\s+`?([A-Z][A-Z0-9\_]*)`?)?\s*[;]?\s*\Z/is", $query, $matches)) {
			
			$randval = rand();
			
			if(!$matches[1])
				$db =& $this->currentDB;
			else
				$db =& $this->databases[$matches[1]];
		
			$tables = $db->listTables();
			$data = array();
			
			foreach($tables as $table_name) {
				$table_name = '\''.$table_name.'\'';
				$data[] = array("name" => $table_name);
			}
			
			$this->Columns[$randval] = array("name");
			$this->cursors[$randval] = array(0, 0);
			$this->data[$randval] = $data;
		
			return $randval;
		} else if(preg_match("/\ASHOW\s+DATABASES\s*[;]?\s*\Z/is", $query, $matches)) {
			$randval = rand();
			
			$dbs = array_keys($this->databases);
			foreach($dbs as $db) {
				$db = '\''.$db.'\'';
				$data[] = array("name" => $db);
			}
			
			$this->Columns[$randval] = array("name");
			$this->cursors[$randval] = array(0, 0);
			$this->data[$randval] = $data;
		
			return $randval;
		} else {
			$this->_set_error("Invalid SHOW query");
			return NULL;
		}
	}
	
	function _query_describe($query)
	{
		if(preg_match("/\ADESC(?:RIBE)?\s+`?(?:([A-Z][A-Z0-9\_]*)`?\.`?)?([A-Z][A-Z0-9\_]*)`?\s*[;]?\s*\Z/is", $query, $matches)) {
			
			$randval = rand();
			
			if(!$matches[1])
				$db =& $this->currentDB;
			else
				$db =& $this->databases[$matches[1]];
		
			$tableObj =& $db->getTable($matches[2]);
			if(!$tableObj->exists()) {
				$this->_error_table_not_exists($db->name, $matches[2]);
				return NULL;
			}
			$columns =  $tableObj->getColumns();
			
			$data = array();
			
			foreach($columns as $name => $column) {
				$name = '\''.$name.'\'';
				$null = ($column['null']) ? "'YES'" : "''";
				$extra = ($column['auto']) ? "'auto_increment'" : "''";
				
				if($column['key'] == 'p')
					$key = "'PRI'";
				else if($column['key'] == 'u')
					$key = "'UNI'";
				else
					$key = "''";

				$data[] = array("Field" => $name, "Type" => "''", "Null" => $null, "Default" => $column['default'], "Key" => $key, "Extra" => $extra);
			}
			
			$this->Columns[$randval] = array_keys($data);
			$this->cursors[$randval] = array(0, 0);
			$this->data[$randval] = $data;
		
			return $randval;
		} else {
			$this->_set_error('Invalid DESCRIBE query');
			return NULL;
		}
	}
	
	function _query_use($query)
	{
		if(preg_match("/\AUSE\s+`?([A-Z][A-Z0-9\_]*)`?\s*[;]?\s*\Z/is", $query, $matches)) {
			$this->select_db($matches[1]);
			return TRUE;
		} else {
			$this->_set_error('Invalid USE query');
			return NULL;
		}
	}

	function _query_lock($query)
	{
		if(preg_match("/\ALOCK\s+TABLES\s+(.+?)\s*[;]?\s*\Z/is", $query, $matches)) {
			preg_match_all("/(?:([A-Z][A-Z0-9\_]*)`?\.`?)?`?([A-Z][A-Z0-9\_]*)`?\s+((?:READ(?:\s+LOCAL)?)|((?:LOW\s+PRIORITY\s+)?WRITE))/is", $matches[1], $rules);
			$numRules = count($rules[0]);
			for($r = 0; $r < $numRules; $r++) {
				if(!$rules[1][$r])
					$db =& $this->currentDB;
				else
					$db =& $this->databases[$rules[1][$r]];
		
				$table_name = $rules[2][$r];
				$table =& $db->getTable($table_name);
				if(!$table->exists()) {
					$this->_error_table_not_exists($db->name, $table_name);
					return NULL;
				}

				if(!strcasecmp(substr($rules[3][$r], 0, 4), "READ")) {
					$table->readLock();
				}
				else {  /* WRITE */
					$table->writeLock();
				}

				$lockedTables[] =& $table;
			}
			return TRUE;
		} else {
			$this->_set_error('Invalid LOCK query');
			return NULL;
		}
	}

	function _query_unlock($query)
	{
		if(preg_match("/\AUNLOCK\s+TABLES\s*[;]?\s*\Z/is", $query)) {
			$this->_unlock_tables();
			return TRUE;
		} else {
			$this->_set_error('Invalid UNLOCK query');
			return NULL;
		}
	}
	
	function fetch_array($id, $type = 1)
	{
		if(!$id || !isset($this->cursors[$id]) || !isset($this->data[$id][$this->cursors[$id][0]]))
			return NULL;

		$entry = $this->data[$id][$this->cursors[$id][0]];
		if(!$entry)
			return NULL;

		foreach($this->Columns[$id] as $column) {
			$column = trim($column);
			if($column == "")
				continue;

			if(preg_match("/\A(?:([A-Z][A-Z0-9\_]*)\.)?([A-Z][A-Z0-9\_]*)(?:\s+(?:AS\s+)?([A-Z][A-Z0-9\_]*))?\Z/is",$column,$matches)) {
				list(, $name, $column, $as) = $matches;
				if(empty($as))
					$as = $column;
				$load = $entry[$column];
				$load = strtr($load, array("\\\"" => "\"", "\\\\\"" => "\\\""));
				if($load) {
					//echo "\$newentry[\$as] = $load;<br/>";
					//echo "\$newentry[$as] = $load;<br/>";
					eval("\$newentry[\$as] = $load;");
				}
			}
			else if(preg_match("/(.+?)\((.+?)?\)(?:\s+(?:AS\s+)?([A-Z][A-Z0-9\_]*))?/is",$column,$functions)) {
				$newentry = $this->_load_functions($id, $column, $entry, $newentry);
			}
			else {
				$load = $entry[$column];
				$load = strtr($load, array("\\\"" => "\"", "\\\\\"" => "\\\""));
				if($load) {
					//echo "\$newentry[\$as] = $load;<br/>";
					//echo "\$newentry[$as] = $load;<br/>";
					eval("\$newentry[\$column] = $load;");
				}
			}
		}
	
		$this->cursors[$id][0]++;

		if($type == 1) {  return $newentry; }
		else if($type == 2) { return array_values($newentry); }
		else{ return array_merge($newentry, array_values($newentry)); } 
	}
	
	function fetch_assoc($results) { return $this->fetch_array($results, FSQL_ASSOC); }
	function fetch_row	($results) { return $this->fetch_array($results, FSQL_NUM); }
	function fetch_both	($results) { return $this->fetch_array($results, FSQL_BOTH); }
 
	function fetch_object($results)
	{
		$row = $this->fetch_array($results, FSQL_ASSOC);
		
		if($row == NULL)
			return NULL;

		$obj = new stdClass();

		foreach($row as $key => $value)
			$obj->{$key} = $value;

		return $obj;
	}
	
	function data_seek($id, $i)
	{
		if(!$id || !isset($this->cursors[$id][0])) {
			$this->_set_error("Bad results id passed in");
			return false;
		} else {
			$this->cursors[$id][0] = $i;
			return true;
		}
	}
	
	function num_fields($id)
	{
		if(!$id || !isset($this->Columns[$id])) {
			$this->_set_error("Bad results id passed in");
			return false;
		} else {
			return count($this->Columns[$id]);
		}
	}
	
	function fetch_field($id, $i = NULL)
	{
		if(!$id || !isset($this->Columns[$id]) || !isset($this->cursors[$id][1])) {
			$this->_set_error("Bad results id passed in");
			return false;
		} else {
			if($i == NULL)
				$i = 0;
			
			if(!isset($this->Columns[$id][$i]))
				return null;

			$field = new stdClass();
			$field->name = $this->Columns[$id][$i];
			return $field;
		}
	}

	function free_result($id)
	{
		unset($this->Columns[$id], $this->data[$id], $this->cursors[$id]);
	}

	function _fsql_strip_stringtags($string)
	{
		return preg_replace("/^'(.+)'$/s", "\\1", $string);
	}

	//////Misc Functions
	function _fsql_functions_database()
	{
		return $this->currentDB->name;
	}
	
	function _fsql_functions_last_insert_id()
	{
		return $this->insert_id;
	}
	
	function _fsql_functions_row_count()
	{
		return $this->affected;
	}
	
	/////Math Functions
	function _fsql_functions_log($arg1, $arg2 = NULL) {
		$arg1 = $this->_fsql_strip_stringtags($arg1);
		if($arg2) {
			$arg2 = $this->_fsql_strip_stringtags($arg2);
		}
		if(($arg1 < 0 || $arg1 == 1) && !$arg2) { return NULL; }
		if(!$arg2) { return log($arg1); } else { return log($arg2) / log($arg1); }
	}
	function _fsql_functions_log2($arg)
	{
		$arg = $this->_fsql_strip_stringtags($arg);
		return $this->_fsql_functions_log(2, $arg);
	}
	function _fsql_functions_log10($arg) {
		$arg = $this->_fsql_strip_stringtags($arg);
		return $this->_fsql_functions_log(10, $arg);
	}
	function _fsql_functions_mod($one, $two) {
		$one = $this->_fsql_strip_stringtags($one);
		$two = $this->_fsql_strip_stringtags($two);
		return $one % $two;
	}
	function _fsql_functions_sign($number) {
		$number = $this->_fsql_strip_stringtags($number);
		if($number > 0) { return 1; } else if($number == 0) { return 0; } else { return -1; }
	}
	function _fsql_functions_truncate($number, $places) {
		$number = $this->_fsql_strip_stringtags($number);
		$places = round($this->_fsql_strip_stringtags($number));
		list($integer, $decimals) = explode(".", $number);
		if($places == 0) { return $integer; }
		else if($places > 0) { return $integer.'.'.substr($decimals,0,$places); }
		else {   return substr($number,0,$places) * pow(10, abs($places));  }
	}
	 
	 /////Grouping and other Misc. Functions
	function _fsql_functions_count($column, $id) {
		if($column == "*") { return count($this->data[$id]); }
		else {   $i = 0;   foreach($this->data[$id] as $entry) {  if($entry[$column]) { $i++; } }  return $i;  }
	}
	function _fsql_functions_max($column, $id) {
		foreach($this->data[$id] as $entry){   if($entry[$column] > $i || !$i) { $i = $entry[$column]; }  }	return $i;
	}
	function _fsql_functions_min($column, $id) {
		foreach($this->data[$id] as $entry){   if($entry[$column] < $i || !$i) { $i = $entry[$column]; }  }	return $i;
	}
	function _fsql_functions_sum($column, $id) {  foreach($this->data[$id] as $entry){ $i += $entry[$column]; }  return $i; }
	 
	 /////String Functions
	function _fsql_functions_concat_ws($string) {
		$numargs = func_num_args();
		if($numargs > 2) {
			for($i = 1; $i < $numargs; $i++) { $return[] = func_get_arg($i);  }
			return implode($string, $return);
		}
		else { return NULL; }
	}
	function _fsql_functions_concat() { return call_user_func_array(array($this,'_fsql_functions_concat_ws'), array("",func_get_args())); }
	function _fsql_functions_elt() {
		$return = func_get_arg(0);
		if(func_num_args() > 1 && $return >= 1 && $return <= func_num_args()) {	return func_get_arg($return);  }
		else { return NULL; }
	}
	function _fsql_functions_locate($string, $find, $start = NULL) {
		if($start) { $string = substr($string, $start); }
		$pos = strpos($string, $find);
		if($pos === false) { return 0; } else { return $pos; }
	}
	function _fsql_functions_lpad($string, $length, $pad) { return str_pad($string, $length, $pad, STR_PAD_LEFT); }
	function _fsql_functions_left($string, $end)	{ return substr($string, 0, $end); }
	function _fsql_functions_right($string,$end)	{ return substr($string, -$end); }
	function _fsql_functions_substring_index($string, $delim, $count) {
		$parts = explode($delim, $string);
		if($count < 0) {   for($i = $count; $i > 0; $i++) { $part = count($parts) + $i; $array[] = $parts[$part]; }  }
		else { for($i = 0; $i < $count; $i++) { $array[] = $parts[$i]; }  }
		return implode($delim, $array);
	}
	 
	////Date/Time functions
	function _fsql_functions_now()		{ return $this->_fsql_functions_from_unixtime(time()); }
	function _fsql_functions_curdate()	{ return $this->from_unixtime(time(), "%Y-%m-%d"); }
	function _fsql_functions_curtime() 	{ return $this->from_unixtime(time(), "%H:%M:%S"); }
	function _fsql_functions_dayofweek($date) 	{ return $this->_fsql_functions_from_unixtime($date, "%w"); }
	function _fsql_functions_weekday($date)		{ return $this->_fsql_functions_from_unixtime($date, "%u"); }
	function _fsql_functions_dayofyear($date)		{ return round($this->_fsql_functions_from_unixtime($date, "%j")); }
	function _fsql_functions_unix_timestamp($date = NULL) {
		if(!$date) { return NULL; } else { return strtotime(str_replace("-","/",$date)); }
	}
	function _fsql_functions_from_unixtime($timestamp, $format = "%Y-%m-%d %H:%M:%S")
	{
		if(!is_int($timestamp)) { $timestamp = $this->_fsql_functions_unix_timestamp($timestamp); }
		return strftime($format, $timestamp);
	}
 
}

?>
