<?php
namespace MicroweberPackages\Editor\TemplateSettingsV2\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class EditorTemplateSettingsV2ServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->loadRoutesFrom(__DIR__ . '/../routes/api.php');
    }
}
