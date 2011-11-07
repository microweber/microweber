<?php

/** This file is part of KCFinder project
  *
  *      @desc Image detection class
  *   @package KCFinder
  *   @version 2.52-dev
  *    @author Pavel Tzonkov <pavelc@users.sourceforge.net>
  * @copyright 2010, 2011 KCFinder Project
  *   @license http://www.opensource.org/licenses/gpl-2.0.php GPLv2
  *   @license http://www.opensource.org/licenses/lgpl-2.1.php LGPLv2
  *      @link http://kcfinder.sunhater.com
  */

class type_img {

    public function checkFile($file, array $config) {

        $driver = isset($config['imageDriversPriority'])
            ? image::getDriver(explode(" ", $config['imageDriversPriority'])) : "gd";

        $img = image::factory($driver, $file);

        if ($img->initError)
            return "Unknown image format/encoding.";

        return true;
    }
}

?>