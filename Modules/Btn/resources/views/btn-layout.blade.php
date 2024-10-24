@php

$btnId = 'btn-' . $params['id'];

$btnOptions = [];
$btnOptions['buttonStyle'] = '';
$btnOptions['buttonSize'] = '';
$btnOptions['buttonAction'] = '';
$btnOptions['popupContent'] = '';
$btnOptions['url'] = '';
$btnOptions['urlBlank'] = '';
$btnOptions['text'] = '';
$btnOptions['icon'] = '';
$btnOptions['iconPosition'] = '';
$btnOptions['buttonId'] = '';

$getBtnOptions = get_module_options($params['id']);
if (!empty($getBtnOptions)) {
    foreach ($getBtnOptions as $getBtnOption) {
        $btnOptions[$getBtnOption['option_key']] = $getBtnOption['option_value'];
    }
}
if (isset($btnOptions['link'])) {
    $btnOptionsLink = json_decode($btnOptions['link'], TRUE);
    if (isset($btnOptionsLink['url'])) {
        $btnOptions['url'] = $btnOptionsLink['url'];
    }
    if (isset($btnOptionsLink['data']['id']) and isset($btnOptionsLink['data']['type']) and $btnOptionsLink['data']['type'] == 'category') {
        $btnOptions['url'] = category_link($btnOptionsLink['data']['id']);
    } else if (isset($btnOptionsLink['data']['id'])) {
        $btnOptions['url'] = content_link($btnOptionsLink['data']['id']);
    }
}

$align = get_module_option('align', $params['id']);

$backgroundColor = get_module_option('backgroundColor', $params['id']);
$color = get_module_option('color', $params['id']);
$borderColor = get_module_option('borderColor', $params['id']);
$borderWidth = get_module_option('borderWidth', $params['id']);
$borderRadius = get_module_option('borderRadius', $params['id']);
$customSize = get_module_option('customSize', $params['id']);
$shadow = get_module_option('shadow', $params['id']);


$hoverBackgroundColor = get_module_option('hoverbackgroundColor', $params['id']);
$hoverColor = get_module_option('hovercolor', $params['id']);
$hoverBorderColor = get_module_option('hoverborderColor', $params['id']);


$style = $btnOptions['buttonStyle'];
$size = $btnOptions['buttonSize'];
$action = $btnOptions['buttonAction'];
$actionContent = $btnOptions['popupContent'];
$url = $btnOptions['url'];
$blank = $btnOptions['urlBlank'] == '1';
$text = $btnOptions['text'];
if ($btnOptions['icon']) {
    $icon = $btnOptions['icon'];
} elseif (isset($params['icon'])) {
    $icon = $params['icon'];
} else {
    $icon = '';
}

$icon = html_entity_decode($icon);

if (isset($params['buttonId'])) {
    $btnId = $params['buttonId'];
}

$attributes = '';
if (isset($params['buttonOnclick'])) {
    $attributes .= 'onclick="' . $params['buttonOnclick'] . '"';
}

if (isset($params['buttonText']) && !empty($params['buttonText']) && empty($text)) {
    $text = $params['buttonText'];
}

$popupFunctionId = 'btn_popup' . uniqid();
if ($text == false and isset($params['text'])) {
    $text = $params['text'];
} elseif ($text == '') {
    $text = lang('Button', 'btn');
}
if ($text === '$notext') {
    $text = '';
}

if ($icon) {

    if ($btn_options['icon_position'] == 'right') {
        $text = $text .  ($text !== '' ? '&nbsp;' : '') . $icon;
    } else {
        $text = $icon . ($text !== '' ? '&nbsp;' : '') . $text;
    }

}

if ($url == false and isset($params['url'])) {
    $url = $params['url'];
} elseif ($url == '') {
    $url = 'javascript:void(0);';
}

$urlDisplay = false;


$linkToContentById = 'content:';
$linkToCategoryById = 'category:';

$urlToContentId = get_module_option('url_to_content_id', $params['id']);
$urlToCategoryId = get_module_option('url_to_category_id', $params['id']);

if ($urlToContentId) {
    $urlDisplay = content_link($urlToContentId);
} else if ($urlToCategoryId) {
    $urlDisplay = category_link($urlToCategoryId);

}


if ($urlDisplay) {
    $url = $urlDisplay;
}


if ($style == false and isset($params['buttonStyle'])) {
    $style = $params['buttonStyle'];
}
if ($style == '') {
    $style = 'btn-default';
}

if ($action == false and isset($params['buttonAction'])) {
    $action = $params['buttonAction'];
}

if ($size == false and isset($params['buttonSize'])) {
    $size = $params['buttonSize'];
}


if ($action == 'popup') {
    $url = 'javascript:' . $popupFunctionId . '()';
}


$moduleTemplate = get_option('data-template', $params['id']);
if ($moduleTemplate == false and isset($params['template'])) {
    $moduleTemplate = $params['template'];
}


@endphp

@include('modules.btn::templates.default')


@php

$cssWrapper = '';
$cssButton = '';
$cssHoverButton = '';


if ($backgroundColor) {
    $cssButton .= 'background-color:' . $backgroundColor . '!important;';
}
if ($color) {
    $cssButton .= 'color:' . $color . '!important;';
}

if ($borderColor) {
    $cssButton .= 'border-color:' . $borderColor . '!important;';
}

if ($borderWidth) {
    $cssButton .= 'border-width:' . $borderWidth . 'px!important;';
}

if ($borderRadius) {
    $cssButton .= 'border-radius:' . $borderRadius . 'px!important;';
}

if ($customSize) {
    $cssButton .= 'font-size: ' . (intval($customSize)) . 'px!important;padding: .9em 2em!important;';
}

if ($shadow) {
    $cssButton .= 'box-shadow:' . $shadow . '!important;';
}

if ($align) {
    if (_lang_is_rtl()) {
        if ($align == 'left') {
            $align = 'right';
        } elseif ($align == 'right') {
            $align = 'left';
        }
    }
    $cssWrapper .= 'text-align:' . $align . ' !important;';
}

if ($hoverBackgroundColor) {
    $cssHoverButton .= 'background-color:' . $hoverBackgroundColor . ' !important;';
}

if ($hoverColor) {
    $cssHoverButton .= 'color:' . $hoverColor . ' !important;';
}

if ($hoverBorderColor) {
    $cssHoverButton .= 'border-color:' . $hoverBorderColor . ' !important;';
}


@endphp
<style>
    #{{ $params['id'] }} {
        {!! $cssWrapper !!}
        }

        #{{ $params['id'] }} > #{{ $btnId }}, #{{ $params['id'] }} > a, #{{ $params['id'] }} > button {
                                                                                                            {!! $cssButton !!}
}

    #{{ $params['id'] }} > #{{ $btnId }}:hover, #{{ $params['id'] }} > a:hover, #{{ $params['id'] }} > button:hover {
                                                                                                                {!! $cssHoverButton !!}
}

</style>
@php

if ($action == 'popup') { @endphp

<script type="text/microweber" id="area{{ $btnId }}">
        {!! $actionContent !!}
</script>

<script>
    function {{ $popupFunctionId }}() {
        mw.dialog({
            name: 'frame{{ $btnId }}',
            content: $(document.getElementById('area{{ $btnId }}')).html(),
            template: 'basic',
            title: "{{ addslashes($text) }}"
        });
    }
</script>
@php }
