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
 * @category   Zend
 * @package    Zend_Controller
 * @subpackage Zend_Controller_Action_Helper
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 * @version    $Id: AutoCompleteDojo.php 9231 2008-04-16 20:26:24Z matthew $
 */

/**
 * @see Zend_Controller_Action_Helper_AutoComplete_Abstract
 */
require_once 'Zend/Controller/Action/Helper/AutoComplete/Abstract.php';

/**
 * Create and send Dojo-compatible autocompletion lists
 *
 * @uses       Zend_Controller_Action_Helper_AutoComplete_Abstract
 * @category   Zend
 * @package    Zend_Controller
 * @subpackage Zend_Controller_Action_Helper
 * @copyright  Copyright (c) 2005-2008 Zend Technologies USA Inc. (http://www.zend.com)
 * @license    http://framework.zend.com/license/new-bsd     New BSD License
 */
class Zend_Controller_Action_Helper_AutoCompleteDojo extends Zend_Controller_Action_Helper_AutoComplete_Abstract
{
    /**
     * Validate data for autocompletion
     * 
     * @param  mixed $data 
     * @return boolean
     */
    public function validateData($data)
    {
        if (is_array($data) && isset($data['items']) && is_array($data['items'])) {
            return true;
        }

        return false;
    }

    /**
     * Prepare data for autocompletion
     * 
     * @param  mixed   $data 
     * @param  boolean $keepLayouts 
     * @return string
     */
    public function prepareAutoCompletion($data, $keepLayouts = false)
    {
        $items = array();
        foreach ($data as $key => $value) {
            $items[] = array('label' => $value, 'name' => $value);
        }
        $final = array(
            'identifier' => 'name',
            'items'      => $items,
        );
        return $this->encodeJson($final, $keepLayouts);
    }
}
