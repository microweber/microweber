<?php

namespace Modules\Audio\Microweber;

use Livewire\Component;
use MicroweberPackages\Microweber\Contracts\MicroweberModuleContract;
use MicroweberPackages\Module\Abstract\BaseModule;

//class AudioModule  extends Component implements MicroweberModuleContract
class AudioModule3  extends Component implements MicroweberModuleContract
{

    public static string $settingsModule = AudioModuleSettings::class;
    public static string $settingsClass = AudioModuleSettings::class;
    public static string $adminClass = AudioModuleSettings::class;
    public static string $adminModule = AudioModuleSettings::class;

    public static string $admin = AudioModuleAdmin::class;


//    public static function getModuleDetails() : array
//    {
//        return [
//            'name' => 'Audio',
//            'position' => 2,
//            'description' => 'Audio module',
//            'icon' => 'mdi mdi-cube-outline',
//            'categories' => 'media, music',
//        ];
//    }


    public static string $title = 'Audio module';

    public static string $type = 'audio';


    public array $params = [];

    public function __construct($params)
    {
        $this->params = $params;
    }


    public static function getIcon()
    {
        return '<i class="mdi mdi-cube-outline"></i>';
    }
    public function render()
    {



        return view('googlemaps.skin1',$this->params);
        return view('modules.audio.templates.skin1',$this->params);
        return view('modules.users.forgot-password.templates.skin1',$this->params);
        return view('modules.users.register.templates.skin1',$this->params);
        return view('modules.billing.templates.dashboard',$this->params);
        return livewire(AudioModule3::class,$this->params)->render();
    }

    public function admin()
    {
        return livewire(AudioModuleAdmin::class,$this->params)->render();

        return 'audio module';
    }




//    public function getModuleDefinition()
//    {
//        return [
//            'index' => AudioModuleIndex::class,
//            'edit' => AudioModuleSettings::class,
//            'admin' => AudioModuleAdminResource::class,
//        ];
//    }

}
