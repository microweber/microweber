<?php

/**
 * This class will parse a csv file in either standard or MS Excel format.
 * Two methods are provided to either process a line at a time or return the whole csv file as an array.
 *
 * It can deal with:
 * - Line breaks within quoted fields
 * - Character seperator (usually a comma or semicolon) in quoted fields
 * - Can leave or remove leading and trailing \s or \t
 * - Can leave or skip empty rows.
 * - Windows and Unix line breaks dealt with automatically. Care must be taken with Macintosh format.
 *
 * Also, the escape character is automatically removed.
 *
 * NOTICE:
 * - Quote character can be escaped by itself or by using an escape character, within a quoted field (i.e. "" or \" will work)
 *
 * USAGE:
 *
 * include_once 'class.csv_bv.php';
 *
 * $csv = & new csv_bv('test.csv', ';', '"' , '\\');
 * $csv->SkipEmptyRows(TRUE); // Will skip empty rows. TRUE by default. (Shown here for example only).
 * $csv->TrimFields(TRUE); // Remove leading and trailing \s and \t. TRUE by default.
 *
 * while ($arr_data = $csv->NextLine()){
 * 
 * 		echo "<br><br>Processing line ". $csv->RowCount() . "<br>";
 * 		echo implode(' , ', $arr_data);
 *
 * }
 *
 * echo "<br><br>Number of returned rows: ".$csv->RowCount();
 * echo "<br><br>Number of skipped rows: ".$csv->SkippedRowCount();
 *
 * ----
 * OR using the csv2array function.
 * ----
 *
 * include_once 'class.csv_bv.php';
 *
 * $csv = & new csv_bv('test.csv', ';', '"' , '\\');
 * $csv->SkipEmptyRows(TRUE); // Will skip empty rows. TRUE by default. (Shown here for example only).
 * $csv->TrimFields(TRUE); // Remove leading and trailing \s and \t. TRUE by default.
 *
 * $_arr = $csv->csv2Array();
 * print_r($_arr);
 *
 * echo "<br><br>Number of returned rows: ".$csv->RowCount();
 * echo "<br><br>Number of skipped rows: ".$csv->SkippedRowCount();
 *
 *
 * WARNING:
 * - Macintosh line breaks need to be dealt with carefully. See the PHP help files for the function 'fgetcsv'
 * 
 * The coding standards used in this file can be found here: http://www.dagbladet.no/development/phpcodingstandard/
 *
 *	All commets and suggestions are welcomed.
 *
 * SUPPORT: Visit http://vhd.com.au/forum/
 *
 * CHANGELOG: 
 * 
 * - Fixed skipping of last row if the last row did not have a new line. Thanks to Florian Bruch and Henry Flurry. (2006_05_15)
 * - Changed the class name to csv_bv for consistency. (2006_05_15)
 * - Fixed small problem where line breaks at the end of file returned a warning (2005_10_28) 
 * 
 * @author Ben Vautier <classes@vhd.com.au> 
 * @copyright (c) 2006
 * @license BSD
 * @version 1.2 (2006_05_15) 
 */


class csv_bv
{
	/**
	  * Seperator character
	  * @var char
	  * @access private
	  */
	var $mFldSeperator;
	
	/**
	  * Enclose character
	  * @var char
	  * @access private
	  */
	var $mFldEnclosure;
	
	/**
	  * Escape character
	  * @var char
	  * @access private
	  */
	var $mFldEscapor;
	
	/**
	  * Length of the largest row in bytes.Default is 4096
	  * @var int
	  * @access private
	  */
	var $mRowSize;
	
	/**
	  * Holds the file pointer
	  * @var resource
	  * @access private
	  */
	var $mHandle;
	
	/**
	  * Counts the number of rows that have been returned
	  * @var int
	  * @access private
	  */
	var $mRowCount;
	
	/**
	  * Counts the number of empty rows that have been skipped
	  * @var int
	  * @access private
	  */
	var $mSkippedRowCount;
	
	/**
	  * Determines whether empty rows should be skipped or not.
	  * By default empty rows are returned.
	  * @var boolean
	  * @access private
	  */
	var $mSkipEmptyRows;
	
	/**
	  * Specifies whether the fields leading and trailing \s and \t should be removed
	  * By default it is TRUE.
	  * @var boolean
	  * @access private
	  */
	var $mTrimFields;
	
	/**
	  * Constructor
	  *
	  * Only used to initialise variables.
	  *
	  * @param str $file - file path
	  * @param str $seperator - Only one character is allowed (optional) 
	  * @param str $enclose - Only one character is allowed (optional) 
	  * @param str $escape - Only one character is allowed (optional) 
	  * @access public
	  */
	Function csv_bv($file, $seperator = ',', $enclose = '"', $escape = ''){
		
		$this->mFldSeperator = $seperator;
		$this->mFldEnclosure = $enclose;
		$this->mFldEscapor = $escape;
		
		$this->mSkipEmptyRows = TRUE;
		$this->mTrimFields =  TRUE;
		
		$this->mRowCount = 0;
		$this->mSkippedRowCount = 0;
		
		$this->mRowSize = 4096;
		
		// Open file 
		$this->mHandle = @fopen($file, "r") or trigger_error('Unable to open csv file', E_USER_ERROR);
	}
	
	
	/**
	  * csv::NextLine() returns an array of fields from the next csv line.
	  *
	  * The position of the file pointer is stored in PHP internals.
	  *
	  * Empty rows can be skipped
	  * Leading and trailing \s and \t can be removed from each field
	  *
	  * @access public
	  * @return array of fields
	  */
	Function NextLine(){
		
		if (feof($this->mHandle)){
			return False;
		}
		
		$arr_row = fgetcsv ($this->mHandle, $this->mRowSize, $this->mFldSeperator, $this->mFldEnclosure);
		
		$this->mRowCount++;
		
		//-------------------------
		// Skip empty rows if asked to
		if ($this->mSkipEmptyRows){
			
			
			if ($arr_row[0] === ''  && count($arr_row) === 1){
				
				$this->mRowCount--;
				$this->mSkippedRowCount++;
				
				$arr_row = $this->NextLine();
				
				// This is to avoid a warning when empty lines are found at the bvery end of a file.
				if (!is_array($arr_row)){ // This will only happen if we are at the end of a file.
					return FALSE;
				}
			}
		}
		
		//-------------------------
		// Remove leading and trailing spaces \s and \t
		if ($this->mTrimFields && is_array($arr_row)){
			array_walk($arr_row, array($this, 'ArrayTrim'));
		}
		
		//-------------------------
		// Remove escape character if it is not empty and different from the enclose character
		// otherwise fgetcsv removes it automatically and we don't have to worry about it.
		if ($this->mFldEscapor !== '' && $this->mFldEscapor !== $this->mFldEnclosure && is_array($arr_row)){
			array_walk($arr_row, array($this, 'ArrayRemoveEscapor'));
		}
		
		return $arr_row;
	}
	
	/**
	  * csv::Csv2Array will return the whole csv file as 2D array
	  *
	  * @access public
	  */
	Function Csv2Array(){
		
		$arr_csv = array();
		
		while ($arr_row = $this->NextLine()){
			$arr_csv[] = $arr_row;
		}
		
		return $arr_csv;
	}
	
	/**
	  * csv::ArrayTrim will remove \s and \t from an array
	  *
	  * It is called from array_walk.
	  * @access private
	  */
	Function ArrayTrim(&$item, $key){
		$item = trim($item, " \t"); // space and tab
	}
	
	/**
	  * csv::ArrayRemoveEscapor will escape the enclose character
	  *
	  * It is called from array_walk.
	  * @access private
	  */
	Function ArrayRemoveEscapor(&$item, $key){
		$item = str_replace($this->mFldEscapor.$this->mFldEnclosure, $this->mFldEnclosure, $item);
	}
	
	/**
	  * csv::RowCount return the current row count
	  *
	  * @access public
	  * @return int
	  */
	Function RowCount(){
		return $this->mRowCount;
	}
	
	/**
	  * csv::RowCount return the current skipped row count
	  *
	  * @access public
	  * @return int
	  */
	Function SkippedRowCount(){
		return $this->mSkippedRowCount;
	}
	
	/**
	  * csv::SkipEmptyRows, sets whether empty rows should be skipped or not
	  *
	  * @access public
	  * @param bool $bool
	  * @return void
	  */
	Function SkipEmptyRows($bool = TRUE){
		$this->mSkipEmptyRows = $bool;
	}
	
	/**
	  * csv::TrimFields, sets whether fields should have their \s and \t removed.
	  *
	  * @access public
	  * @param bool $bool
	  * @return void
	  */
	Function TrimFields($bool = TRUE){
		$this->mTrimFields = $bool;
	}
	
}

?>
