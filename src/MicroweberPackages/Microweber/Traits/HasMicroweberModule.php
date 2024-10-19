<?php

namespace MicroweberPackages\Microweber\Traits;

trait HasMicroweberModule
{
//    public static function getSettingsView(): string
//    {
//        return '';
//    }
//    public static function getSettingsComponent(): string
//    {
//        return '';
//    }
//    public static function getSettingsResource(): string
//    {
//        return '';
//    }

    public array $params = [];

    public function getParams()
    {
        return $this->params;
    }

    public function setParams(array $params = [])
    {
        $this->params = $params;
    }

    public static function getTitle(): string
    {
        return '';
    }

    public static function getIcon(): string
    {
        return '';
    }

    public static function getPosition(): int
    {
        return 0;
    }

    /**
     * Render the frontend view of the module.
     */
    public function render(array $params = [])
    {
        return ''; // This should return the view for the frontend display
    }
    public function admin(array $params = [])
    {
        return ''; // This should return the view for the frontend display
    }

    /**
     * Render the frontend view of the module.
     */
    public function view(array $params = [])
    {
        return ''; // This should return the view for the frontend display
    }

    /**
     * Render the settings view for the module.
     * @deprecated  This is deprecated and will be removed in the future.
     */
    // move to module settings class
    public function renderSettings(array $params = [])
    {
        return ''; // This should return the view for the admin settings display
    }

    /**
     * Render the settings view for the module.
     * @deprecated  This is deprecated and will be removed in the future.
     */
    // move to module settings class
    public function getLiveEditActions(array $params = [])
    {
        return [
            'autoplay' => [
                'title' => 'Autoplay',
                'icon' => 'mdi mdi-play',
                'type' => 'toggle',
                'value' => '1',
            ],
            'mute' => [
                'title' => 'Mute',
                'icon' => 'mdi mdi-volume-mute',
                'type' => 'toggle',
                'value' => '1',
            ],
            'volume' => [
                'title' => 'Volume',
                'icon' => 'mdi mdi-volume-high',
                'type' => 'slider',
                'value' => '100',
            ],

        ];
    }


//getOptions(){
//
//}
//
//render(){
//
//}
//
//
//
//	admin(){
//
//    }


//    /**
//     * Admin page rendering logic (if needed separately).
//     */
//    public function admin(array $params = [])
//    {
//        return ''; // Optional: If admin needs to handle special functionality.
//    }
}
