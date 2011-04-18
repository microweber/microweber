<?php
/**
 * Piwik - Open source web analytics
 *
 * @link http://piwik.org
 * @license http://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 * @version $Id: 1.2-rc2.php 3912 2011-02-15 09:58:12Z matt $
 *
 * @category Piwik
 * @package Updates
 */

/**
 * @package Updates
 */
class Piwik_Updates_1_2_rc2 extends Piwik_Updates
{
	static function update()
	{
		try {
			Piwik_PluginsManager::getInstance()->activatePlugin('CustomVariables');
		} catch(Exception $e) {
		}
	}
}

