<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 8/20/2020
 * Time: 1:16 PM
 */

namespace MicroweberPackages\Menu\Traits;

use Illuminate\Database\Eloquent\Model;

trait HasMenuItem
{
    protected static $saveToMenus = [];

    public function save(array $options = [])
    {
        if (isset($this->add_content_to_menu)) {
            if (is_array($this->add_content_to_menu)) {
                self::$saveToMenus = $this->add_content_to_menu;
            }
            unset($this->add_content_to_menu);
        }

        parent::save($options);
    }

    public static function bootHasMenuItem()
    {
        static::saved(function ($model) {

            if (!empty(self::$saveToMenus) && is_array(self::$saveToMenus)) {
                foreach (self::$saveToMenus as $menu_id) {
                    mw()->content_manager->helpers->add_content_to_menu($model->id, $menu_id);
                }
            }

        });
    }

}