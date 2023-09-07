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


    public function initializeHasMenuItem()
    {
         $this->fillable[] = 'add_content_to_menu';
    }

    public function addToMenu($contentId)
    {
        self::$addContentToMenu[] = $contentId;
    }

    public static function bootHasMenuItem()
    {

        static::saving(function ($model) {

            // append content to categories
            if (isset($model->add_content_to_menu)) {
                self::$addContentToMenu = $model->add_content_to_menu;
            }
            unset($model->add_content_to_menu);
        });


        static::saved(function ($model) {

            if (!empty(self::$addContentToMenu) && is_array(self::$addContentToMenu)) {
                foreach (self::$addContentToMenu as $menuId) {
                    // check if content is already in menu
                    if(!app()->menu_manager->is_in_menu($menuId, $model->id)) {
                        app()->content_manager->helpers->add_content_to_menu($model->id, $menuId);
                    }
                }
            }

        });
    }

}
