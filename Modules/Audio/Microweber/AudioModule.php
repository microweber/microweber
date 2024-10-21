<?php

namespace Modules\Audio\Microweber;

use MicroweberPackages\Microweber\Abstract\BaseModule;
use Modules\Audio\Filament\AudioModuleSettings;

//class AudioModule  extends Component implements MicroweberModuleContract
class AudioModule extends BaseModule
{
    public static string $title = 'Audio module';
    public static string $icon = 'mdi mdi-cube-outline';
    public static string $categories = 'media, music';
    public static int $position = 2;
    public static string $settingsComponent = AudioModuleSettings::class;


    public function render()
    {

        $template = $this->getTemplate();
        $viewData = $this->getViewData();
//        if ($template) {
//            return view($template, $viewData);
//        }

        return view('modules.audio::templates.default', $viewData);


    }




}
