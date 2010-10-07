<?php

/**
 * Zend Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://framework.zend.com/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@zend.com so we can send you a copy immediately.
 *
 * @category     Zend
 * @package      Zend_Gdata
 * @copyright    Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */

/**
 * @see Zend_Gdata_Entry
 */
require_once 'Zend/Gdata/Entry.php';

/**
 * @see Zend_Gdata_Spreadsheets_Extension_RowCount
 */
require_once 'Zend/Gdata/Spreadsheets/Extension/RowCount.php';

/**
 * @see Zend_Gdata_Spreadsheets_Extension_ColCount
 */
require_once 'Zend/Gdata/Spreadsheets/Extension/ColCount.php';

/**
 * Concrete class for working with Worksheet entries.
 *
 * @category     Zend
 * @package        Zend_Gdata
 * @copyright    Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Gdata_Spreadsheets_WorksheetEntry extends Zend_Gdata_Entry
{

    protected $_entryClassName = 'Zend_Gdata_Spreadsheets_WorksheetEntry';

    protected $_rowCount = null;
    protected $_colCount = null;

    /**
     * Constructs a new Zend_Gdata_Spreadsheets_WorksheetEntry object.
     * @param DOMElement $element (optional) The DOMElement on which to base this object.
     */
    public function __construct($element = null)
    {
        foreach (Zend_Gdata_Spreadsheets::$namespaces as $nsPrefix => $nsUri) {
            $this->registerNamespace($nsPrefix, $nsUri);
        }
        parent::__construct($element);
    }

    public function getDOM($doc = null)
    {
        $element = parent::getDOM($doc);
        if ($this->_rowCount != null) {
            $element->appendChild($this->_rowCount->getDOM($element->ownerDocument));
        }
        if ($this->_colCount != null) {
            $element->appendChild($this->_colCount->getDOM($element->ownerDocument));
        }
        return $element;
    }

    protected function takeChildFromDOM($child)
    {
        $absoluteNodeName = $child->namespaceURI . ':' . $child->localName;
        switch ($absoluteNodeName) {
            case $this->lookupNamespace('gs') . ':' . 'rowCount';
                $rowCount = new Zend_Gdata_Spreadsheets_Extension_RowCount();
                $rowCount->transferFromDOM($child);
                $this->_rowCount = $rowCount;
                break;
            case $this->lookupNamespace('gs') . ':' . 'colCount';
                $colCount = new Zend_Gdata_Spreadsheets_Extension_ColCount();
                $colCount->transferFromDOM($child);
                $this->_colCount = $colCount;
                break;
            default:
                parent::takeChildFromDOM($child);
                break;
        }
    }


    /**
     * Gets the row count for this entry.
     * @return string The row count for the entry.
     */
    public function getRowCount()
    {
        return $this->_rowCount;
    }

    /**
     * Gets the column count for this entry.
     * @return string The column count for the entry.
     */
    public function getColumnCount()
    {
        return $this->_colCount;
    }

    /**
     * Sets the row count for this entry.
     * @param string $rowCount The new row count for the entry.
     */
    public function setRowCount($rowCount)
    {
        $this->_rowCount = $rowCount;
        return $this;
    }

    /**
     * Sets the column count for this entry.
     * @param string $colCount The new column count for the entry.
     */
    public function setColumnCount($colCount)
    {
        $this->_colCount = $colCount;
        return $this;
    }

}
