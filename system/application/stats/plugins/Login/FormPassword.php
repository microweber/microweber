<?php
/**
 * Piwik - Open source web analytics
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 * @version $Id: FormPassword.php 3761 2011-01-16 22:01:31Z vipsoft $
 *
 * @category Piwik_Plugins
 * @package Piwik_Login
 */

/**
 *
 * @package Piwik_Login
 */
class Piwik_Login_FormPassword extends Piwik_QuickForm2
{
	function __construct( $id = 'lostpasswordform', $method = 'post', $attributes = null, $trackSubmit = false)
	{
		parent::__construct($id,  $method, $attributes, $trackSubmit);
	}

	function init()
	{
		$this->addElement('text', 'form_login')
		     ->addRule('required', Piwik_Translate('General_Required', Piwik_Translate('Login_LoginOrEmail')));

		$this->addElement('hidden', 'form_nonce');

		$this->addElement('submit', 'submit');
	}
}
