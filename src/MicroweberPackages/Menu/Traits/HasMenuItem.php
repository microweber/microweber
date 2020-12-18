<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 8/20/2020
 * Time: 1:16 PM
 */

namespace MicroweberPackages\Menu\Traits;

trait HasMenuItem
{
    public static $addContentToMenu = [];

    public function addToMenu($contentId)
    {
        self::$addContentToMenu[] = $contentId;
    }

    public static function bootHasMenuItem()
    {
        static::saved(function ($model) {

            if (!empty(self::$addContentToMenu) && is_array(self::$addContentToMenu)) {
                foreach (self::$addContentToMenu as $menuId) {
                    mw()->content_manager->helpers->add_content_to_menu($model->id, $menuId);
                }
            }

        });
    }

}