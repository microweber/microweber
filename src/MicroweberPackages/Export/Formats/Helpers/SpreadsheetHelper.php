<?php
namespace MicroweberPackages\Export\Formats\Helpers;

use Exception;
use function MicroweberPackages\Backup\Exporters\mb_substr;

/**
 * PhpSpreadsheet Helper
 *
 * @author      Nick Tsai <myintaer@gmail.com>
 * @version     1.3.6
 * @filesource 	PhpSpreadsheet <https://github.com/PHPOffice/PhpSpreadsheet>
 * @see         https://github.com/yidas/phpspreadsheet-helper
 * @example
 *  \yidas\phpSpreadsheet\Helper::newExcel()
 *      ->addRow(['ID', 'Name', 'Email'])
 *      ->addRows([
 *          ['1', 'Nick','myintaer@gmail.com'],
 *          ['2', 'Eric','eric@.....'],
 *      ])
 *      ->output('My Excel');
 */
class SpreadsheetHelper
{
    /**
     * @var object Cached PhpSpreadsheet object
     */
    private static $_objSpreadsheet;

    /**
     * @var object Cached PhpSpreadsheet Sheet object
     */
    private static $_objSheet;
    /**
     * @var int Current row offset for the actived sheet
     */
    private static $_offsetRow;
    /**
     * @var int Current column offset for the actived sheet
     */
    private static $_offsetCol;
    /**
     * @var array Map of coordinates by keys
     */
    private static $_keyCoordinateMap;
    /**
     * @var array Map of column alpha by keys
     */
    private static $_keyColumnMap;
    /**
     * @var array Map of row number by keys
     */
    private static $_keyRowMap;
    /**
     * @var int Map of ranges by keys
     */
    private static $_keyRangeMap;
    /**
     * Extension list for reader
     */
    private static $_readerExtensions = [
        'Excel5' => '.xls',
        'Excel2003XML' => '.xls',
        'Excel2007' => '.xlsx',
        'OOCalc' => '.ods',
        'SYLK' => '.slk',
        'Gnumeric' => '.gnumeric',
        'CSV' => '.csv',
        'HTML' => '.html',
    ];
    /**
     * Extension list for writer
     */
    private static $_writerTypeInfo = [
        'Ods' => [
            'extension' => '.ods',
            'contentType' => 'application/vnd.oasis.opendocument.spreadsheet'
        ],
        'Xls' => [
            'extension' => '.xls',
            'contentType' => 'application/vnd.ms-excel'
        ],
        'Xlsx' => [
            'extension' => '.xlsx',
            'contentType' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
        ],
        'Html' => [
            'extension' => '.html',
            'contentType' => 'text/html'
        ],
        'Csv' => [
            'extension' => '.csv',
            'contentType' => 'text/csv'
        ],
    ];
    /**
     * Invalid characters in sheet title.
     *
     * @var array
     */
    private static $_invalidCharacters = ['*', ':', '/', '\\', '?', '[', ']'];
    /**
     * New or load an PhpSpreadsheet object
     *
     * @param object|string $phpSpreadsheet PhpSpreadsheet object or filepath
     * @return self
     */
    public static function newSpreadsheet($phpSpreadsheet=NULL)
    {
        if (is_object($phpSpreadsheet)) {

            self::$_objSpreadsheet = &$phpSpreadsheet;
        }
        elseif (is_string($phpSpreadsheet)) {
            self::$_objSpreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($phpSpreadsheet);
        }
        else {
            self::$_objSpreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        }

        return self::resetSheet();
    }
    /**
     * Get PhpSpreadsheet object from cache
     *
     * @return object PhpSpreadsheet object
     */
    public static function getSpreadsheet()
    {
        return self::$_objSpreadsheet;
    }

    /**
     * Reset cached PhpSpreadsheet sheet object and helper data
     *
     * @return self
     */
    public static function resetSheet()
    {
        self::$_objSheet = NULL;
        self::$_offsetRow = 0;
        self::$_offsetCol = 0; // A1 => 1
        return new static();
    }
    /**
     * Set an active PhpSpreadsheet Sheet
     *
     * @param object|int $sheet PhpSpreadsheet sheet object or index number
     * @param string $title Sheet title
     * @param bool $normalizeTitle Auto-normalize title rule
     * @return self
     */
    public static function setSheet($sheet=0, $title=NULL, $normalizeTitle=false)
    {
        self::resetSheet();
        if (is_object($sheet)) {

            self::$_objSheet = &$sheet;
        }
        elseif (is_numeric($sheet) && $sheet>=0 && self::$_objSpreadsheet) {
            /* Sheets Check */
            $sheetCount = self::$_objSpreadsheet->getSheetCount();
            if ($sheet >= $sheetCount) {
                for ($i=$sheetCount; $i <= $sheet; $i++) {
                    self::$_objSpreadsheet->createSheet($i);
                }
            }
            // Select sheet
            self::$_objSheet = self::$_objSpreadsheet->setActiveSheetIndex($sheet);
        }
        elseif (is_null($sheet) && self::$_objSpreadsheet) {
            // Auto create a sheet without index
            self::setSheet(self::getSheetCount());
        }
        else {
            throw new Exception("Invalid or empty PhpSpreadsheet Object for setting sheet", 400);
        }
        // Sheet Title
        if ($title) {
            // Auto-normalize title rule
            if ($normalizeTitle) {
                /**
                 * @see PhpOffice\PhpSpreadsheet\Worksheet\Worksheet
                 */
                // Some of the printable ASCII characters are invalid:  * : / \ ? [ ]
                $title = str_replace(self::$_invalidCharacters, '', $title);
                // Maximum 31 characters allowed for sheet title
                $title = mb_substr($title, 0, 31);
            }
            self::$_objSheet->setTitle($title);
        }
        return new static();
    }
    /**
     * Get PhpSpreadsheet Sheet object from cache
     *
     * @param int|string $identity Sheet index or name
     * @param bool $autoCreate
     * @return object PhpSpreadsheet Sheet object
     */
    public static function getSheet($identity=null, $autoCreate=false)
    {
        // Deafult is get active sheet
        if (!$identity) {

            return self::$_objSheet;
        }
        // Giving $identity situation
        if (is_numeric($identity)) {

            $objSheet = self::$_objSpreadsheet->getSheet($identity);
            // Auto create if not exist
            if (!$objSheet && $autoCreate) {
                // Create a new sheet by index
                $objSheet = self::setSheet($identity)
                    ->getSheet();
            }
        }
        elseif (is_string($identity)) {

            $objSheet = self::$_objSpreadsheet->getSheetByName($identity);
            // Auto create if not exist
            if (!$objSheet && $autoCreate) {
                // Create a new sheet by name
                $objSheet = self::setSheet(null, $identity, true)
                    ->getSheet();
            }
        }
        return $objSheet;
    }
    /**
     * Get sheet count
     *
     * @return int Count of sheets
     */
    public static function getSheetCount()
    {
        return self::$_objSpreadsheet->getSheetCount();
    }
    /**
     * Get active sheet index
     *
     * @return int Index of active sheet
     */
    public static function getActiveSheetIndex()
    {
        return self::$_objSpreadsheet->getActiveSheetIndex();
    }
    /**
     * Set the offset of rows for the actived PhpSpreadsheet Sheet
     *
     * @param int $var The offset number
     * @return self
     */
    public static function setRowOffset($var=0)
    {
        self::$_offsetRow = (int)$var;

        return new static();
    }
    /**
     * Get the offset of rows for the actived PhpSpreadsheet Sheet
     *
     * @return int The offset number
     */
    public static function getRowOffset()
    {
        return self::$_offsetRow;
    }
    /**
     * Set the offset of columns for the actived PhpSpreadsheet Sheet
     *
     * @param int $var The offset number
     * @return self
     */
    public static function setColumnOffset($var=0)
    {
        self::$_offsetCol = (int)$var;

        return new static();
    }
    /**
     * Add a row to the actived sheet of PhpSpreadsheet
     *
     * @param array $rowData
     *  @param mixed|array Cell value | Cell attributes
     *   Cell attributes key-value:
     *   @param string|int 'key' Cell key for index
     *   @param mixed 'value' Cell value
     *   @param int 'col' Column span for mergence
     *   @param int 'row' Row span for mergence
     *   @param int 'skip' Column skip counter
     *   @param int 'width' Column width pixels
     *   @param array 'style' Array containing style information for applyFromArray()
     * @param array Row attributes refers to cell attributes
     * @return self
     */
    public static function addRow($rowData, $rowAttributes=null)
    {
        $sheetObj = self::validSheetObj();

        // Column pointer
        $posCol = self::$_offsetCol + 1;
        // Next row
        self::$_offsetRow++;

        foreach ($rowData as $key => $cell) {

            // Attribute defining Cell
            if (is_array($cell) || is_array($rowAttributes)) {
                // Attr map: key as variable & value as value
                $attributeMap = [
                    // Basic attributes
                    'key' => null,
                    'value' => null,
                    // Merging
                    'col' => 1,
                    'row' => 1,
                    'skip' => 1,
                    // Cell Format
                    'width' => null,
                    'style' => null,
                ];
                // Row attributes inheriting process (Based on default value of map)
                foreach ($attributeMap as $aKey => $mapVal) {

                    ${$aKey} = isset($rowAttributes[$aKey]) ? $rowAttributes[$aKey] : $mapVal;
                }
                // Cell Process
                if (is_array($cell)) {
                    // Cell attributes process (Based on row attributes)
                    foreach ($attributeMap as $aKey => $mapVal) {

                        ${$aKey} = isset($cell[$aKey]) ? $cell[$aKey] : ${$aKey};
                    }

                } else {
                    // Override value attribute
                    $value = $cell;
                }
                // Cached column alpha
                $colAlpha = self::num2alpha($posCol);
                // Set value
                $sheetObj->setCellValueByColumnAndRow($posCol, self::$_offsetRow, $value);
                // Setting the column's width
                if ($width) {

                    $sheetObj->getColumnDimension($colAlpha)->setWidth($width);
                }
                // Setting applyFromArray
                if ($style) {

                    $sheetObj->getStyle($colAlpha.self::$_offsetRow)
                        ->applyFromArray($style);
                }
                // Merge handler
                $colspan = & $col;
                $rowspan = & $row;
                $mergeVal = null;
                if ($colspan>1 || $rowspan>1) {
                    $posColLast = $posCol;
                    $posCol = $posCol + $colspan - 1;
                    $posRow = self::$_offsetRow + $rowspan - 1;
                    $mergeVal = self::num2alpha($posColLast).self::$_offsetRow
                        . ':'
                        . self::num2alpha($posCol).$posRow;
                    $sheetObj->mergeCells($mergeVal);
                }
                // Save key Map
                if ($key) {
                    $startColumn = $colAlpha;
                    $startCoordinate = $startColumn. self::$_offsetRow;
                    // Range Map
                    if (isset($mergeVal)) {
                        self::$_keyRangeMap[$key] = $mergeVal;
                        // Reset column coordinate
                        $startColumn = self::num2alpha($posColLast);
                        $startCoordinate = $startColumn. self::$_offsetRow;
                    }
                    elseif ($skip > 1) {
                        self::$_keyRangeMap[$key] = $startCoordinate
                            . ':'
                            . self::num2alpha($posCol+($skip-1)) . self::$_offsetRow;
                    }
                    else {
                        self::$_keyRangeMap[$key] = "{$startCoordinate}:{$startCoordinate}";
                    }
                    // Coordinate & col-row Map
                    self::$_keyCoordinateMap[$key] = $startCoordinate;
                    self::$_keyColumnMap[$key] = $startColumn;
                    self::$_keyRowMap[$key] = self::$_offsetRow;
                }
                // Skip option
                $posCol += $skip;
            } else {
                $sheetObj->setCellValueByColumnAndRow($posCol, self::$_offsetRow, $cell);

                $posCol++;
            }
        }
        return new static();
    }
    /**
     * Add rows to the actived sheet of PhpSpreadsheet
     *
     * @param array array of rowData for addRow()
     * @param array Row attributes refers to cell attributes
     * @return self
     */
    public static function addRows($data, $rowAttributes=null)
    {
         foreach ($data as $key => $row) {
            self::addRow($row, $rowAttributes);
        }
        return new static();
    }
    /**
     * Output file to browser
     *
     * @param string $filename
     * @param string $format
     */
    public static function output($filename='excel', $format='Xlsx')
    {
        $objPhpSpreadsheet = self::validExcelObj();
        // Create Writer first
        $objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPhpSpreadsheet, $format);
        /**
         * Type Mapping
         */
        $inTypeList = isset(self::$_writerTypeInfo[$format]) ? true : false;
        $extension = ($inTypeList) ? self::$_writerTypeInfo[$format]['extension'] : '';
        $contentType = ($inTypeList)
            ? self::$_writerTypeInfo[$format]['contentType']
            : 'application/octet-stream';
        // Redirect output to a client's web browser
        header("Content-Type: {$contentType}");
        /**
         * @see http://www.rfc-editor.org/rfc/rfc5987.txt
         */
        $filename = rawurlencode($filename);
        header("Content-Disposition: attachment; filename={$filename}{$extension}; filename*=UTF-8''{$filename}{$extension};");
        header("Cache-Control: max-age=0");
        $objWriter->save('php://output');
        exit;
    }
    /**
     * Save as file
     *
     * @param string $filename Support file path
     * @param string $format
     * @return string Filepath
     */
    public static function save($filename='excel', $format='Xlsx')
    {
        $objPhpSpreadsheet = self::validExcelObj();
        // Create Writer first
        $objWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($objPhpSpreadsheet, $format);
        /**
         * Type Mapping
         */
        $inTypeList = isset(self::$_writerTypeInfo[$format]) ? true : false;
        $extension = ($inTypeList) ? self::$_writerTypeInfo[$format]['extension'] : '';

        // Check if filename contained extension
        $filepath = (stripos($filename, $extension)===(strlen($filename)-strlen($extension)))
            ? $filename
            : "{$filename}{$extension}";
        // Save file
        $objWriter->save($filepath);
        return $filepath;
    }
    /**
     * Get data of a row from the actived sheet of PhpSpreadsheet
     *
     * @param bool $toString All values from sheet to be string type
     * @param bool $options [
     *  row (int) Ended row number
     *  column (int) Ended column number
     *  timestamp (bool) Excel datetime to Unixtime
     *  timestampFormat (string) Format for date() when usgin timestamp
     *  ]
     * @param callable $callback($cellValue, int $columnIndex, int $rowIndex)
     * @return array Data of Spreadsheet
     */
    public static function getRow($toString=true, $options=[], callable $callback=null)
    {
        $worksheet = self::validSheetObj();
        // Options
        $defaultOptions = [
            'columnOffset' => 0,
            'columns' => NULL,
            'timestamp' => true,
            'timestampFormat' => 'Y-m-d H:i:s', // False would use Unixtime
        ];
        $options = array_replace($defaultOptions, $options);
        // Calculate the column range of the worksheet
        $startColumn = ($options['columnOffset']) ?: 0;
        $columns = ($options['columns']) ?: self::alpha2num($worksheet->getHighestColumn());
        // Next row
        self::$_offsetRow++;
        // Check if exceed highest row by PHPSpreadsheet cachedHighestRow
        if (self::$_offsetRow > $worksheet->getHighestRow()) {
            return [];
        }
        // Fetch data from the sheet
        $data = [];
        for ($col = $startColumn + 1; $col <= $columns; ++$col) {
            $cell = $worksheet->getCellByColumnAndRow($col, self::$_offsetRow);
            $value = $cell->getCalculatedValue();
            // Timestamp option
            if ($options['timestamp'] && \PhpOffice\PhpSpreadsheet\Shared\Date::isDateTime($cell)) {
                $value = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToTimestamp($value);
                // Timestamp Format option
                $value = ($options['timestampFormat'])
                    ? date($options['timestampFormat'], $value) : $value;
            }
            $value = ($toString) ? (string)$value : $value;
            // Callback function
            if ($callback) {
                $callback($value, $col, self::$_offsetRow);
            }
            $data[] = $value;
        }
        return $data;
    }
    /**
     * Get rows from the actived sheet of PhpSpreadsheet
     *
     * @param bool $toString All values from sheet to be string type
     * @param bool $options [
     *  row (int) Ended row number
     *  column (int) Ended column number
     *  timestamp (bool) Excel datetime to Unixtime
     *  timestampFormat (string) Format for date() when usgin timestamp
     *  ]
     * @param callable $callback($cellValue, int $columnIndex, int $rowIndex)
     * @return array Data of Spreadsheet
     */
    public static function getRows($toString=true, Array $options=[], callable $callback=null)
    {
        $worksheet = self::validSheetObj();
        // Options
        $defaultOptions = [
            'rowOffset' => 0,
            'rows' => NULL,
            'columns' => NULL,
            'timestamp' => true,
            'timestampFormat' => 'Y-m-d H:i:s', // False would use Unixtime
        ];
        $options = array_replace($defaultOptions, $options);
        // Get the highest row and column numbers referenced in the worksheet
        $highestRow = $worksheet->getHighestRow();
        $rowOffset = ($options['rowOffset'] && $options['rowOffset'] <= $highestRow)
            ? $options['rowOffset']
            : 0;
        $rows = ($options['rows'] && ($rowOffset+$options['rows']) < $highestRow)
            ? $options['rows']
            : $highestRow - $rowOffset;
        // Enhance performance for each getRow()
        $options['columns'] = ($options['columns']) ?: self::alpha2num($worksheet->getHighestColumn());
        // Set row offset
        self::$_offsetRow = $rowOffset;
        // Fetch data from the sheet
        $data = [];
        $pointerRow = &$data;
        for ($i=1; $i <= $rows ; $i++) {

            $pointerRow[] = self::getRow($toString, $options, $callback);
        }
        return $data;
    }
    /**
     * Get Coordinate Map by key or all from the actived sheet
     *
     * @param string|int $key Key set by addRow()
     * @return string|array Coordinate string | Key-Coordinate array
     */
    public static function getCoordinateMap($key=NULL)
    {
        if ($key) {
            return isset(self::$_keyCoordinateMap[$key]) ? self::$_keyCoordinateMap[$key] : NULL;
        } else {
            return self::$_keyCoordinateMap;
        }
    }
    /**
     * Get Column Alpha Map by key or all from the actived sheet
     *
     * @param string|int $key Key set by addRow()
     * @return string|array Column alpha string | Key-Coordinate array
     */
    public static function getColumnMap($key=NULL)
    {
        if ($key) {
            return isset(self::$_keyColumnMap[$key]) ? self::$_keyColumnMap[$key] : NULL;
        } else {
            return self::$_keyColumnMap;
        }
    }
    /**
     * Get Row Number Map by key or all from the actived sheet
     *
     * @param string|int $key Key set by addRow()
     * @return int|array Row number | Key-Coordinate array
     */
    public static function getRowMap($key=NULL)
    {
        if ($key) {
            return isset(self::$_keyRowMap[$key]) ? self::$_keyRowMap[$key] : NULL;
        } else {
            return self::$_keyRowMap;
        }
    }
    /**
     * Get Range Map by key or all from the actived sheet
     *
     * @param string|int $key Key set by addRow()
     * @return string|array Range string | Key-Range array
     */
    public static function getRangeMap($key=NULL)
    {
        if ($key) {
            return isset(self::$_keyRangeMap[$key]) ? self::$_keyRangeMap[$key] : NULL;
        } else {
            return self::$_keyRangeMap;
        }
    }
    /**
     * Get Range of all actived cells from the actived sheet
     *
     * @return string Range string
     */
    public static function getRangeAll()
    {
        $sheetObj = self::validSheetObj();

        return self::num2alpha(self::$_offsetCol+1). '1:'. $sheetObj->getHighestColumn(). $sheetObj->getHighestRow();
    }
    /**
     * Set WrapText for all actived cells or set by giving range to the actived sheet
     *
     * @param string $range Cells range format
     * @param bool $value PhpSpreadsheet setWrapText() argument
     * @return self
     */
    public static function setWrapText($range=NULL, $value=true)
    {
        $sheetObj = self::validSheetObj();
        $range = ($range) ? $range : self::getRangeAll();
        $sheetObj->getStyle($range)
            ->getAlignment()
            ->setWrapText($value);

        return new static();
    }
    /**
     * Set Style for all actived cells or set by giving range to the actived sheet
     *
     * @param array Array containing style information for applyFromArray()
     * @param string $range Cells range format
     * @return self
     */
    public static function setStyle($styleArray, $range=NULL)
    {
        $sheetObj = self::validSheetObj();
        $range = ($range) ? $range : self::getRangeAll();
        $sheetObj->getStyle($range)
            ->applyFromArray($styleArray);

        return new static();
    }
    /**
     * Set AutoSize for all actived cells or set by giving column range to the actived sheet
     *
     * @param string $colAlphaStart Column Alpah of start
     * @param string $colAlphaEnd Column Alpah of end
     * @param bool $value PhpSpreadsheet AutoSize() argument
     * @return self
     */
    public static function setAutoSize($colAlphaStart=NULL, $colAlphaEnd=NULL, $value=true)
    {
        $sheetObj = self::validSheetObj();
        $colStart = ($colAlphaStart) ? self::alpha2num($colAlphaStart) : self::$_offsetCol + 1;
        $colEnd = ($colAlphaEnd)
            ? self::alpha2num($colAlphaEnd)
            : self::alpha2num($sheetObj->getHighestColumn());
        foreach (range($colStart,$colEnd ) as $key => $colNum) {
            $sheetObj->getColumnDimension(self::num2alpha($colNum))->setAutoSize($value);
        }
        return new static();
    }
    /**
     * Number to Alpha
     *
     * Optimizing from \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex()
     *
     * @example
     *  1 => A, 27 => AA
     * @param int $n column number
     * @return string Excel column alpha
     */
    public static function num2alpha($n)
    {
        $n = $n - 1;
        $r = '';
        for ($i = 1; $n >= 0 && $i < 10; $i++) {
            $r = chr(0x41 + ($n % pow(26, $i) / pow(26, $i - 1))) . $r;
            $n -= pow(26, $i);
        }
        return $r;
    }
    /**
     * Alpha to Number
     *
     * Optimizing from \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString()
     *
     * @example
     *  A => 1, AA => 27
     * @param int $n Excel column alpha
     * @return string column number
     */
    public static function alpha2num($a)
    {
        $r = 0;
        $l = strlen($a);
        for ($i = 0; $i < $l; $i++) {
            $r += pow(26, $i) * (ord($a[$l - $i - 1]) - 0x40);
        }
        return $r;
    }
    /**
     * Validate and return the selected PhpSpreadsheet Object
     *
     * @param object $excelObj PhpSpreadsheet Object
     * @return object Cached object or given object
     */
    private static function validExcelObj($excelObj=NULL)
    {
        if (is_object($excelObj)) {
            return $excelObj;
        }
        elseif (is_object(self::$_objSpreadsheet)) {
            return self::$_objSpreadsheet;
        }
        else {

            throw new Exception("Invalid or empty PhpSpreadsheet Object", 400);
        }
    }
    /**
     * Validate and return the selected PhpSpreadsheet Sheet Object
     *
     * @param object $excelObj PhpSpreadsheet Sheet Object
     * @return object Cached object or given object
     */
    private static function validSheetObj($sheetObj=NULL)
    {
        if (is_object($sheetObj)) {
            return $sheetObj;
        }
        elseif (is_object(self::$_objSheet)) {
            return self::$_objSheet;
        }
        elseif (is_object(self::$_objSpreadsheet)) {
            // Set to default sheet if is unset
            return self::setSheet()->getSheet();
        }
        else {

            throw new Exception("Invalid or empty PhpSpreadsheet Sheet Object", 400);
        }
    }
}
