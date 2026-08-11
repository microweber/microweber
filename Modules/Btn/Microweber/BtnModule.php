<?php

namespace Modules\Btn\Microweber;

use MicroweberPackages\ModuleRegistry\Abstract\BaseModule;
use Modules\Btn\Filament\BtnModuleSettings;

class BtnModule extends BaseModule
{
    public static string $name = 'Button';
    public static string $module = 'btn';
    public static string $icon = 'modules.btn-icon';
    public static string $categories = 'other';
    public static int $position = 2;
    public static string $settingsComponent = BtnModuleSettings::class;

    public static string $templatesNamespace = 'modules.btn::templates';
    public static array $translatableOptions = ['text'];

    public function getViewData(): array
    {
        $viewData = parent::getViewData();

        $viewData['id'] = $this->params['id'];
        $viewData['btnId'] = 'link-' . $this->params['id'];
        // AI-56 / TICKET-CW (cycle-63 2026-05-08): popupFunctionId is
        // interpolated into a `href="javascript:{id}()"` URI in
        // templates/bootstrap.blade.php and as `function {id}()` in
        // components/popup.blade.php. md5() emits hex-only today so
        // the value is safe — but a future refactor could trivially
        // break that invariant (e.g. someone tries to use the param
        // id directly as a "human-readable" function name). Lock the
        // value to a strict JS-identifier shape with an explicit
        // preg_replace so the safety property is guaranteed at the
        // source and cannot drift.
        $popupFunctionRaw = 'mwPopupBtn' . md5($this->params['id']);
        $viewData['popupFunctionId'] = preg_replace(
            '/[^A-Za-z0-9_]/',
            '',
            $popupFunctionRaw
        );

        $viewData['style'] = $this->params['button_style'] ?? '';
        $viewData['size'] = $this->params['button_size'] ?? $params['size'] ?? '';
        $viewData['class'] = $this->params['link_class'] ?? '';
        $viewData['popupContent'] = '';
        $viewData['url'] = '';
        $viewData['blank'] = '';
        $viewData['text'] = $this->params['button_text'] ?? $this->params['text'] ?? $this->params['data-text'] ?? 'Button';
        $viewData['icon'] = $this->params['icon'] ?? '';
        $viewData['iconPosition'] = $this->params['iconPosition'] ?? 'left';
        $viewData['action'] = $this->params['button_action'] ?? $this->params['button-action'] ?? $this->params['action'] ?? '';
        $viewData['attributes'] = '';
        $viewData['align'] = '';

        $viewData['backgroundColor'] = '';
        $viewData['color'] = '';
        $viewData['borderColor'] = '';
        $viewData['borderWidth'] = '';
        $viewData['borderRadius'] = '';
        $viewData['customSize'] = '';
        $viewData['shadow'] = '';
        $viewData['hoverbackgroundColor'] = '';
        $viewData['hovercolor'] = '';
        $viewData['hoverborderColor'] = '';

        $hasCustomStyles = false;

        $moduleOptions = $this->getOptions();

        if (!empty($moduleOptions)) {
            foreach ($moduleOptions as $btnOptionKey => $btnOptionValue) {
                $viewData[$btnOptionKey] = $btnOptionValue;
            }
        }
        if (isset($viewData['link'])) {
            $btnOptionsLink = json_decode($viewData['link'], true);
            if (isset($btnOptionsLink['url'])) {
                $viewData['url'] = $btnOptionsLink['url'];
            }
            if (isset($btnOptionsLink['data']['id']) && isset($btnOptionsLink['data']['type']) && $btnOptionsLink['data']['type'] == 'category') {
                $viewData['url'] = category_link($btnOptionsLink['data']['id']);
            } else {
                if (isset($btnOptionsLink['data']['id'])) {
                    $viewData['url'] = content_link($btnOptionsLink['data']['id']);
                }
            }
        }
        $lookForCustomStylesIn = ['backgroundColor', 'color', 'borderColor', 'borderWidth', 'borderRadius', 'customSize', 'shadow', 'hoverbackgroundColor', 'hovercolor', 'hoverborderColor'];

        foreach ($lookForCustomStylesIn as $customStyleKey) {
            if (isset($viewData[$customStyleKey]) && $viewData[$customStyleKey] != '') {
                $hasCustomStyles = true;
            }
        }
        $viewData['hasCustomStyles'] = $hasCustomStyles;



        return $viewData;
    }

    public function render()
    {
        $viewData = $this->getViewData();

        $viewName = $this->getViewName($viewData['template'] ?? 'default');
        return view($viewName, $viewData);
    }

}
