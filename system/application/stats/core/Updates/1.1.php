<?php
/**
 * Piwik - Open source web analytics
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 * @version $Id: 1.1.php 3643 2011-01-06 05:10:03Z vipsoft $
 *
 * @category Piwik
 * @package Updates
 */

/**
 * @package Updates
 */
class Piwik_Updates_1_1 extends Piwik_Updates
{
	static function update($schema = 'Myisam')
	{
		$config = Zend_Registry::get('config');

		$rootLogin = $config->superuser->login;
		try {
			// throws an exception if invalid
			Piwik::checkValidLoginString($rootLogin);
		} catch(Exception $e) {
			throw new Exception('Superuser login name "' . $rootLogin . '" is no longer a valid format. '
						. $e->getMessage()
						. ' Edit your config/config.ini.php to change it.');
		}
	}
}
