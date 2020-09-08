<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 8/20/2020
 * Time: 1:16 PM
 */

namespace MicroweberPackages\Menu\Traits;

trait HasMenuItem {

    /**
     * Override save method
     *
     * @param array $options
     * @return mixed
     */
    public function save(array $options = [])
    {
        if (isset($this->add_content_to_menu)) {
            if (is_array($this->add_content_to_menu) && $this->id > 0) {
                foreach ($this->add_content_to_menu as $menu_id) {
                    mw()->content_manager->helpers->add_content_to_menu($this->id, $menu_id);
                }
            }
            unset($this->add_content_to_menu);
        }

        parent::save($options);
    }

}