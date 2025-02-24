<?php


namespace MicroweberPackages\LiveEdit\Services;


use MicroweberPackages\LiveEdit\MenuBuilder\LiveEditMenu;
use MicroweberPackages\LiveEdit\Traits\HasLiveEditMenus;
use MicroweberPackages\Template\Traits\HasScriptsAndStylesTrait;


class LiveEditManagerService
{
    use HasLiveEditMenus;
    use HasScriptsAndStylesTrait;

    public function __construct()
    {
        $this->initMenus();
    }

    public function initMenus()
    {
        $this->menus['top_right_menu'] = new LiveEditMenu();
    }

    public function headTags()
    {
        $tags = [];
        $tags[] = $this->styles();
        $tags[] = $this->scripts();
        $tags[] = $this->customHeadTags();

        return implode("\r\n", $tags);
    }
}
