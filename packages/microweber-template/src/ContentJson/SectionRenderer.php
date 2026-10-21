<?php

namespace MicroweberPackages\Template\ContentJson;

use DateTimeImmutable;

/**
 * Renders a content.json "sections" array into HTML for a page body.
 *
 * The per-section-type render methods below are GENERATED — ported verbatim from
 * the external designs renderer (the shared index.php that authored the JSON
 * designs) via scripts/designs/extract-renderer.php, with internal calls rewritten
 * to $this->method(). Regenerate after changing the renderer; do not hand-edit the
 * generated block. Only the form-POST handling, CLI/page-shell and CSS of the
 * original are intentionally omitted.
 *
 * The hand-written pieces are render()/renderSections() (the public entry points),
 * e()/asset()/asset_exists()/web_base() (adapted to Microweber's published template
 * asset path) and render_body() (replicates the original render_page() body order:
 * navbar / <main> sections / footer).
 *
 * $tpl carries the content.json page/data array plus two Microweber keys the
 * installer injects:
 *   - 'mw_asset_base' : public URL base for this template's assets, e.g. "/templates/alfa"
 *   - 'mw_asset_dir'  : absolute public dir, e.g. "/var/www/public/templates/alfa"
 */
class SectionRenderer
{
    /**
     * Render an ordered list of sections into a page body (navbar + <main> + footer).
     *
     * @param array $sections The page's "sections" array.
     * @param array $data     Design-level data (name/title/preview/messages/…) plus the
     *                        Microweber asset keys (mw_asset_base / mw_asset_dir).
     */
    public function render(array $sections, array $data = []): string
    {
        $tpl = $data;
        $tpl['sections'] = $sections;

        // Several ported methods use $tpl['slug'] for element ids / body class.
        // Default it from the asset base so callers never hit an undefined key.
        if (empty($tpl['slug'])) {
            $base = (string) ($tpl['mw_asset_base'] ?? '');
            $tpl['slug'] = $base !== '' ? basename($base) : 'design';
        }

        return $this->render_body($tpl, $sections, []);
    }

    /** Alias kept for readability where a single section list is rendered. */
    public function renderSections(array $sections, array $data = []): string
    {
        return $this->render($sections, $data);
    }

    private function e(mixed $value): string
    {
        return htmlspecialchars((string) $value, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    }

    private function web_base(): string
    {
        return '';
    }

    /** Microweber-adapted: resolve a design-relative path to the published template URL. */
    private function asset(array $tpl, string $path): string
    {
        $path = ltrim(str_replace('\\', '/', $path), '/');
        if ($path === '' || str_contains($path, '..')) {
            return '';
        }
        $parts = array_map('rawurlencode', explode('/', $path));
        $base = rtrim((string) ($tpl['mw_asset_base'] ?? ''), '/');
        return ($base !== '' ? $base . '/' : '/') . implode('/', $parts);
    }

    /** Microweber-adapted: check the published public dir for the asset. */
    private function asset_exists(array $tpl, string $path): bool
    {
        $path = ltrim(str_replace('\\', '/', $path), '/');
        if ($path === '' || str_contains($path, '..')) {
            return false;
        }
        $dir = rtrim((string) ($tpl['mw_asset_dir'] ?? ''), '/');
        return $dir !== '' && is_file($dir . '/' . $path);
    }

    /**
     * Assemble a page body from an ordered section list, mirroring the original
     * render_page(): navbar and footer sit outside <main>, everything else inside.
     */
    private function render_body(array $tpl, array $sections, array $state = []): string
    {
        $html = '';
        $main = false;
        foreach ($sections as $section) {
            if (!is_array($section)) {
                continue;
            }
            $type = (string) ($section['type'] ?? '');
            if ($type === 'navbar') {
                if ($main) { $html .= '</main>'; $main = false; }
                $html .= $this->render_navbar($tpl, $section);
                continue;
            }
            if ($type === 'footer') {
                if ($main) { $html .= '</main>'; $main = false; }
                $html .= $this->render_footer($tpl, $section);
                continue;
            }
            if (!$main) { $html .= '<main id="content">'; $main = true; }
            $html .= $this->render_section($tpl, $section, $state);
        }
        if ($main) { $html .= '</main>'; }
        return $html;
    }

    private function clip(string $value, int $length): string
{
    $value = trim($value);
    if (function_exists('mb_substr')) {
        return mb_substr($value, 0, $length);
    }
    return substr($value, 0, $length);
}

    private function arr(array $source, string $key): array
{
    return (isset($source[$key]) && is_array($source[$key])) ? $source[$key] : [];
}

    private function slugify(string $value): string
{
    $value = strtolower(trim($value));
    $value = preg_replace('/[^a-z0-9]+/', '-', $value) ?? '';
    return trim($value, '-');
}

    private function slug_from(string $value): string
{
    $value = strtolower(trim($value));
    return preg_match('/^[a-z0-9\-]+$/', $value) === 1 ? $value : '';
}

    private function rich(string $text): string
{
    $safe = nl2br($this->e($text), false);
    return preg_replace('/\*(.+?)\*/', '<em>$1</em>', $safe) ?? $safe;
}

    private function icon(string $name): string
{
    if ($name === '' || !str_starts_with($name, 'bi-')) {
        return '';
    }
    return '<i class="bi ' . $this->e($name) . '" aria-hidden="true"></i>';
}

    private function stars(int $count, int $max = 5): string
{
    $count = max(0, min($max, $count));
    $html = '<span class="stars" aria-label="' . $count . ' out of ' . $max . ' stars">';
    for ($i = 1; $i <= $max; $i++) {
        $html .= '<i class="bi ' . ($i <= $count ? 'bi-star-fill' : 'bi-star') . '" aria-hidden="true"></i>';
    }
    return $html . '</span>';
}

    private function default_value(string $token): string
{
    if (preg_match('/^today([+-]\d+)?$/', $token, $match) === 1) {
        $days = isset($match[1]) ? (int) $match[1] : 0;
        return (new DateTimeImmutable('today'))->modify(sprintf('%+d days', $days))->format('Y-m-d');
    }
    if (preg_match('/^time:(\d{2}:\d{2})$/', $token, $match) === 1) {
        return $match[1];
    }
    return $token;
}

    private function img_tag(array $tpl, string $path, string $alt, string $class = '', bool $eager = false): string
{
    if ($path === '' || !$this->asset_exists($tpl, $path)) {
        return '<div class="ph ' . $this->e($class) . '" role="img" aria-label="' . $this->e($alt) . '"></div>';
    }
    $loading = $eager ? ' loading="eager" fetchpriority="high"' : ' loading="lazy"';
    $classAttr = $class !== '' ? ' class="' . $this->e($class) . '"' : '';
    return '<img src="' . $this->e($this->asset($tpl, $path)) . '" alt="' . $this->e($alt) . '"' . $classAttr . $loading . '>';
}

    private function brand(string $key, string $wordmark = ''): string
{
    $built = match ($key) {
        'hola' => '<span class="brand brand-hola">hola</span>',
        'cleanup' => '<span class="brand brand-cleanup"><span class="brand-mark" aria-hidden="true"><i class="bi bi-stars"></i></span><span><strong>The Clean Up Man</strong><small>Cleaning</small></span></span>',
        'match' => '<span class="brand brand-match">Match<strong>Financial</strong></span>',
        'tangible' => '<span class="brand brand-tangible"><svg viewBox="0 0 48 48" aria-hidden="true"><path fill="currentColor" d="M6 8h36l-7 8H28v24h-8V16H13L6 8z"/></svg><span>Tangible</span></span>',
        'vacay' => '<span class="brand brand-vacay"><span>Vacay</span><i class="bi bi-airplane-fill" aria-hidden="true"></i><span>Hooray</span></span>',
        default => '',
    };
    if ($built !== '') {
        return $built;
    }
    $label = trim($wordmark) !== '' ? $wordmark : $key;
    return '<span class="brand brand-word">' . $this->e($label) . '</span>';
}

    private function button_link(array $cta, string $class = 'btnx'): string
{
    $label = (string) ($cta['label'] ?? '');
    if ($label === '') {
        return '';
    }
    $style = (string) ($cta['style'] ?? 'solid');
    $iconName = (string) ($cta['icon'] ?? '');
    $iconHtml = $iconName !== '' ? $this->icon($iconName) : '';
    $after = !empty($cta['iconAfter']);
    $inner = $after ? $this->e($label) . $iconHtml : $iconHtml . $this->e($label);
    if (($cta['type'] ?? '') === 'submit') {
        $attrs = '';
        if (!empty($cta['form'])) {
            $attrs .= ' form="' . $this->e((string) $cta['form']) . '"';
        }
        return '<button class="' . $this->e(trim($class . ' ' . $style)) . '" type="submit"' . $attrs . '>' . $inner . '</button>';
    }
    return '<a class="' . $this->e(trim($class . ' ' . $style)) . '" href="' . $this->e((string) ($cta['href'] ?? '#')) . '">' . $inner . '</a>';
}

    private function block_head(array $section, string $tag = 'h2'): string
{
    if ($tag !== 'h1' && $tag !== 'h2') {
        $tag = 'h2';
    }
    if (empty($section['title']) && empty($section['lede']) && empty($section['kicker'])) {
        return '';
    }
    $html = '<header class="section-head">';
    if (!empty($section['kicker'])) {
        $html .= '<p class="kicker">' . $this->e((string) $section['kicker']) . '</p>';
    }
    if (!empty($section['title'])) {
        $html .= '<' . $tag . '>' . $this->rich((string) $section['title']) . '</' . $tag . '>';
    }
    if (!empty($section['lede'])) {
        $html .= '<p class="lede">' . $this->rich((string) $section['lede']) . '</p>';
    }
    $html .= '</header>';
    return $html;
}

    private function render_field(array $field): string
{
    $type = (string) ($field['type'] ?? 'text');
    $name = (string) ($field['name'] ?? 'field');
    $label = (string) ($field['label'] ?? '');
    $placeholder = (string) ($field['placeholder'] ?? '');
    $required = !empty($field['required']);
    $iconName = (string) ($field['icon'] ?? '');
    $span = $field['span'] ?? null;
    $spanClass = '';
    if ($span === 0 || $span === 'all') {
        $spanClass = ' span-all';
    } elseif (is_numeric($span) && (int) $span > 1) {
        $spanClass = ' span-' . (int) $span;
    }
    $id = 'f-' . $this->slugify($name) . '-' . substr(md5($name . $label), 0, 5);
    $req = $required ? ' required' : '';
    if (!empty($field['requireWhenVisible'])) {
        $req = '';
    }
    $dataReq = !empty($field['requireWhenVisible']) ? ' data-req="1"' : '';
    $star = $required || !empty($field['requireWhenVisible']) ? ' <span class="req">*</span>' : '';
    $ph = $placeholder !== '' ? ' placeholder="' . $this->e($placeholder) . '"' : '';
    $auto = (string) ($field['autocomplete'] ?? '');
    $ac = $auto !== '' ? ' autocomplete="' . $this->e($auto) . '"' : '';
    $min = '';
    if (($field['min'] ?? '') === 'today') {
        $min = ' min="' . $this->e(date('Y-m-d')) . '"';
    } elseif (!empty($field['min'])) {
        $min = ' min="' . $this->e((string) $field['min']) . '"';
    }

    if ($type === 'checkbox') {
        return '<label class="check-line' . $spanClass . '"><input type="checkbox" name="' . $this->e($name) . '" value="1"' . $req . '> <span>' . $this->e($label) . '</span></label>';
    }

    $control = '';
    if ($type === 'textarea') {
        $rows = max(2, (int) ($field['rows'] ?? 4));
        $control = '<textarea class="form-control" id="' . $this->e($id) . '" name="' . $this->e($name) . '" rows="' . $rows . '"' . $ph . $req . $ac . '></textarea>';
    } elseif ($type === 'select') {
        $control = '<select class="form-select" id="' . $this->e($id) . '" name="' . $this->e($name) . '"' . $req . $ac . $dataReq . '>';
        $control .= '<option value="">' . $this->e($placeholder !== '' ? $placeholder : 'Select') . '</option>';
        foreach ($this->arr($field, 'options') as $option) {
            if (is_array($option)) {
                $value = (string) ($option['value'] ?? '');
                $text = (string) ($option['label'] ?? $value);
            } else {
                $value = (string) $option;
                $text = (string) $option;
            }
            $control .= '<option value="' . $this->e($value) . '">' . $this->e($text) . '</option>';
        }
        $control .= '</select>';
    } else {
        $inputType = in_array($type, ['email', 'tel', 'date', 'time', 'number', 'text'], true) ? $type : 'text';
        $value = '';
        if (isset($field['value'])) {
            $value = (string) $field['value'];
        } elseif (!empty($field['default'])) {
            $value = $this->default_value((string) $field['default']);
        }
        $valueAttr = $value !== '' ? ' value="' . $this->e($value) . '"' : '';
        $control = '<input class="form-control" type="' . $this->e($inputType) . '" id="' . $this->e($id) . '" name="' . $this->e($name) . '"' . $valueAttr . $ph . $req . $dataReq . $ac . $min . '>';
    }

    $iconHtml = $iconName !== '' ? '<span class="field-icon">' . $this->icon($iconName) . '</span>' : '';
    $labelHtml = $label !== '' ? '<label for="' . $this->e($id) . '">' . $this->e($label) . $star . '</label>' : '';
    return '<div class="field' . ($iconName !== '' ? ' has-icon' : '') . $spanClass . '">' . $labelHtml . $iconHtml . $control . '</div>';
}

    private function render_choices(array $choices): string
{
    $options = $this->arr($choices, 'options');
    if ($options === []) {
        return '';
    }
    $name = (string) ($choices['name'] ?? 'choice');
    $type = (string) ($choices['type'] ?? 'checkbox');
    if ($type !== 'radio') {
        $type = 'checkbox';
    }
    $html = '<fieldset class="choice-set"><legend>' . $this->e((string) ($choices['legend'] ?? '')) . '</legend><div class="choice-row">';
    foreach ($options as $option) {
        $label = is_array($option) ? (string) ($option['label'] ?? '') : (string) $option;
        $value = is_array($option) ? (string) ($option['value'] ?? $label) : (string) $option;
        $fieldName = $type === 'checkbox' ? $name . '[]' : $name;
        $html .= '<label class="choice"><input type="' . $this->e($type) . '" name="' . $this->e($fieldName) . '" value="' . $this->e($value) . '"> <span>' . $this->e($label) . '</span></label>';
    }
    return $html . '</div></fieldset>';
}

    private function render_form_grid(array $fields, int $columns = 2): string
{
    $columns = max(1, min(6, $columns));
    $html = '<div class="form-grid" style="--cols:' . $columns . '">';
    foreach ($fields as $field) {
        if (is_array($field)) {
            $html .= $this->render_field($field);
        }
    }
    return $html . '</div>';
}

    private function form_open(array $tpl, string $form, string $anchor, string $class = 'site-form'): string
{
    $action = 'index.php?' . http_build_query(['template' => $tpl['slug']]);
    return '<form class="' . $this->e($class) . '" id="form-' . $this->e($form) . '" method="post" action="' . $this->e($action) . '#' . $this->e($anchor) . '">'
        . '<input type="hidden" name="form" value="' . $this->e($form) . '">'
        . '<input type="hidden" name="anchor" value="' . $this->e($anchor) . '">'
        . '<input type="hidden" name="template" value="' . $this->e((string) $tpl['slug']) . '">';
}

    private function success_box(array $tpl, array $state, string $form): string
{
    if (($state['sent'] ?? '') !== $form) {
        return '';
    }
    $message = (string) ($this->arr($tpl, 'messages')[$form] ?? 'Saved.');
    return '<div class="alert alert-success" role="status">' . $this->e($message) . '</div>';
}

    private function social_list(array $items, string $style): string
{
    if ($items === []) {
        return '';
    }
    $html = '<ul class="social social-' . $this->e($style) . '">';
    foreach ($items as $item) {
        $html .= '<li><a href="' . $this->e((string) ($item['href'] ?? '#footer')) . '" aria-label="' . $this->e((string) ($item['label'] ?? 'Social')) . '">' . $this->icon((string) ($item['icon'] ?? 'bi-link-45deg')) . '<span>' . $this->e((string) ($item['label'] ?? '')) . '</span></a></li>';
    }
    return $html . '</ul>';
}

    private function contact_lines(array $items): string
{
    if ($items === []) {
        return '';
    }
    $html = '<ul class="contact-list">';
    foreach ($items as $item) {
        $inner = $this->icon((string) ($item['icon'] ?? '')) . '<span>' . $this->e((string) ($item['text'] ?? '')) . '</span>';
        if (!empty($item['href'])) {
            $html .= '<li><a href="' . $this->e((string) $item['href']) . '">' . $inner . '</a></li>';
        } else {
            $html .= '<li>' . $inner . '</li>';
        }
    }
    return $html . '</ul>';
}

    private function footer_badge(string $name): string
{
    if ($name === 'iata') {
        return '<div class="iata"><svg viewBox="0 0 64 64" aria-hidden="true"><circle cx="32" cy="28" r="12" fill="none" stroke="currentColor" stroke-width="2"/><path d="M10 28h44M32 8c8 8 8 32 0 40M32 8c-8 8-8 32 0 40" fill="none" stroke="currentColor" stroke-width="2"/><path d="M20 46l12-22 12 22" fill="none" stroke="currentColor" stroke-width="2"/></svg><strong>IATA</strong></div>';
    }
    if ($name === 'lender') {
        return '<div class="lender-badge"><i class="bi bi-house-door" aria-hidden="true"></i><span>Lender</span></div>';
    }
    return '';
}

    private function art_svg(string $name): string
{
    $strokes = match ($name) {
        'wheel' => '<circle cx="120" cy="92" r="54"/><circle cx="120" cy="92" r="7" fill="#243049" stroke="none"/><path d="M120 38v108M66 92h108M80 52l80 80M160 52 80 132"/>',
        'compass' => '<circle cx="120" cy="92" r="54"/><path d="M120 44l16 48-16 8-16-8 16-48z" fill="#243049" stroke="none"/><path d="M120 48v88M76 92h88"/>',
        'press' => '<path d="M48 130h144M64 130V78h112v52M78 78V58h84v20M92 58c0-18 56-18 56 0"/><circle cx="150" cy="104" r="10"/>',
        'bulb' => '<path d="M120 36a36 36 0 0 1 18 66c-6 6-8 10-8 18h-20c0-8-2-12-8-18a36 36 0 0 1 18-66z"/><path d="M104 126h32M108 136h24M112 146h16"/>',
        'phone' => '<path d="M78 112c18-6 28-16 40-28s22-22 28-40"/><path d="M74 118l18-8c4-2 6 2 8 6l6 14c2 4-2 8-6 10-22 10-48-16-58-36-2-4 2-8 6-8l14 4c4 2 8 4 6 8z"/><path d="M150 58c10 4 18 12 22 22"/>',
        'chip' => '<rect x="78" y="58" width="84" height="68" rx="6"/><path d="M96 58V40M120 58V40M144 58V40M96 126v18M120 126v18M144 126v18M78 76H60M78 96H60M78 112H60M162 76h18M162 96h18M162 112h18"/>',
        'globe' => '<circle cx="112" cy="90" r="46"/><path d="M112 44c14 16 14 76 0 92M112 44c-14 16-14 76 0 92M70 90h84M76 68h72M76 112h72"/><path d="M150 118l28 22-8-26z" fill="#243049" stroke="none"/>',
        'mark' => '<path d="M70 40h100l-18 22H128v70h-22V62H88L70 40z" fill="#c6a35a" stroke="none"/>',
        default => '<circle cx="120" cy="92" r="40"/>',
    };
    return '<svg class="art" viewBox="0 0 240 180" aria-hidden="true" fill="none" stroke="#243049" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">' . $strokes . '</svg>';
}

    private function render_navbar(array $tpl, array $section): string
{
    $layout = (string) ($section['layout'] ?? 'standard');
    $home = 'index.php?template=' . rawurlencode((string) $tpl['slug']) . '#top';
    $brandHtml = '<a class="navbar-brand" href="' . $this->e($home) . '">' . $this->brand((string) ($section['brand'] ?? $tpl['slug']), (string) ($section['wordmark'] ?? '')) . '</a>';
    $links = $this->arr($section, 'links');
    $linkHtml = '';
    foreach ($links as $link) {
        $linkHtml .= '<li class="nav-item"><a class="nav-link" href="' . $this->e((string) ($link['href'] ?? '#')) . '">' . $this->e((string) ($link['label'] ?? '')) . '</a></li>';
    }
    $actions = '';
    foreach ($this->arr($section, 'actions') as $action) {
        if (($action['style'] ?? '') === 'login') {
            $actions .= '<details class="login-pop"><summary class="btnx btn-nav outline">' . $this->e((string) ($action['label'] ?? 'Log in')) . '</summary><div class="login-card"><p>' . $this->e((string) ($action['note'] ?? 'This demo does not sign anyone in.')) . '</p></div></details>';
            continue;
        }
        $actions .= $this->button_link($action, 'btnx btn-nav');
    }
    $phone = $this->arr($section, 'phone');
    $phoneHtml = '';
    if ($phone !== []) {
        $phoneHtml = '<a class="nav-phone" href="' . $this->e((string) ($phone['href'] ?? '#')) . '"><i class="bi bi-telephone-fill" aria-hidden="true"></i><span>'
            . (!empty($phone['label']) ? '<small>' . $this->e((string) $phone['label']) . '</small>' : '')
            . '<strong>' . $this->e((string) ($phone['text'] ?? '')) . '</strong></span></a>';
    }
    $badge = $this->arr($section, 'badge');
    $badgeHtml = '';
    if ($badge !== []) {
        $badgeHtml = '<span class="lang-badge">' . $this->e((string) ($badge['flag'] ?? 'ES')) . '<span class="visually-hidden"> ' . $this->e((string) ($badge['label'] ?? 'Spanish')) . '</span></span>';
    }

    if ($layout === 'center') {
        $menu = '';
        if ($links !== []) {
            $menu = '<div class="dropdown nav-menu"><button class="btnx ghost menu-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="bi bi-list" aria-hidden="true"></i> Menu</button><ul class="dropdown-menu">';
            foreach ($links as $link) {
                $menu .= '<li><a class="dropdown-item" href="' . $this->e((string) ($link['href'] ?? '#')) . '">' . $this->e((string) ($link['label'] ?? '')) . '</a></li>';
            }
            $menu .= '</ul></div>';
        }
        $iconPhone = '';
        if (!empty($phone['href'])) {
            $iconPhone = '<a class="icon-btn" href="' . $this->e((string) $phone['href']) . '" aria-label="Call ' . $this->e((string) ($phone['text'] ?? 'the hotel')) . '"><i class="bi bi-telephone" aria-hidden="true"></i></a>';
        }
        return '<header class="site-header layout-center" id="top"><nav class="navbar navbar-vacay" aria-label="Primary"><div class="container nav-center"><div class="nav-left">'
            . $menu . $iconPhone . '</div>' . $brandHtml . '<div class="nav-right">' . $actions . '</div></div></nav></header>';
    }

    $collapse = '';
    if ($linkHtml !== '') {
        $collapseId = 'nav-' . $tpl['slug'];
        $collapse = '<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#' . $this->e($collapseId) . '" aria-controls="' . $this->e($collapseId) . '" aria-expanded="false" aria-label="Open menu"><span class="navbar-toggler-icon"></span></button>'
            . '<div class="collapse navbar-collapse" id="' . $this->e($collapseId) . '"><ul class="navbar-nav mx-auto">' . $linkHtml . '</ul></div>';
    }
    $overlay = $layout === 'overlay' ? ' is-overlay' : '';
    return '<header class="site-header layout-' . $this->e($layout) . '" id="top"><nav class="navbar navbar-expand-lg site-nav' . $overlay . '" aria-label="Primary"><div class="container">'
        . $brandHtml . $collapse . '<div class="nav-tools ms-auto">' . $badgeHtml . $actions . $phoneHtml . '</div></div></nav></header>';
}

    private function render_hero_search(array $tpl, array $section, array $state): string
{
    $image = $this->asset_exists($tpl, (string) ($section['image'] ?? '')) ? $this->asset($tpl, (string) $section['image']) : '';
    $html = '<section class="hero-search" id="' . $this->e((string) ($section['id'] ?? 'search')) . '" style="--hero:url(\'' . $this->e($image) . '\')"><div class="container">';
    $html .= '<div class="hero-top"><div class="hero-copy"><h1>' . $this->rich((string) ($section['title'] ?? '')) . '</h1>';
    if ($this->arr($section, 'points') !== []) {
        $html .= '<div class="hero-points">';
        foreach ($this->arr($section, 'points') as $point) {
            $html .= '<p>' . $this->e((string) $point) . '</p>';
        }
        $html .= '</div>';
    }
    $html .= '</div>';
    $trust = $this->arr($section, 'trust');
    if ($trust !== []) {
        $html .= '<aside class="trustpilot"><div class="trust-brand"><i class="bi bi-star-fill" aria-hidden="true"></i> ' . $this->e((string) ($trust['brand'] ?? 'Trustpilot')) . '</div>'
            . '<div class="trust-stars" aria-hidden="true">★★★★★</div>'
            . '<p class="trust-score"><strong>' . $this->e((string) ($trust['score'] ?? '')) . '</strong> ' . $this->e((string) ($trust['label'] ?? '')) . '</p>'
            . '<p>' . $this->e((string) ($trust['reviews'] ?? '')) . '</p></aside>';
    }
    $html .= '</div>';

    $html .= '<div class="search-card" data-tabs>';
    $tabs = $this->arr($section, 'tabs');
    if ($tabs !== []) {
        $html .= '<div class="search-tabs" role="tablist">';
        foreach ($tabs as $index => $tab) {
            $active = $index === 0 ? ' is-active' : '';
            $selected = $index === 0 ? 'true' : 'false';
            $html .= '<button type="button" role="tab" class="tab' . $active . '" data-tab="' . $this->e((string) ($tab['id'] ?? $index)) . '" aria-selected="' . $selected . '">' . $this->e((string) ($tab['label'] ?? '')) . '</button>';
        }
        $html .= '</div>';
    }
    $anchor = (string) ($section['id'] ?? 'search');
    $html .= $this->form_open($tpl, 'search', $anchor, 'search-form');
    $html .= '<div class="search-row">';
    foreach ($tabs as $index => $tab) {
        $hidden = $index === 0 ? '' : ' is-hidden';
        $html .= '<div data-panel="' . $this->e((string) ($tab['id'] ?? $index)) . '" class="search-grid' . $hidden . '">';
        foreach ($this->arr($tab, 'fields') as $field) {
            if (is_array($field)) {
                $html .= $this->render_field($field);
            }
        }
        $html .= '</div>';
    }
    $html .= '<button class="btnx solid search-submit" type="submit">' . $this->e((string) ($section['submit'] ?? 'Search')) . '</button></div>';
    $toggle = $this->arr($section, 'toggle');
    if ($toggle !== []) {
        $target = (string) ($toggle['target'] ?? 'other-city');
        $html .= '<label class="check-line"><input type="checkbox" name="' . $this->e((string) ($toggle['name'] ?? 'other_city')) . '" value="1" data-shows="#' . $this->e($target) . '"> <span>' . $this->e((string) ($toggle['label'] ?? '')) . '</span></label>';
        $html .= '<div id="' . $this->e($target) . '" class="is-hidden other-city">';
        if (is_array($toggle['field'] ?? null)) {
            $html .= $this->render_field($toggle['field']);
        }
        $html .= '</div>';
    }
    $html .= '</form></div>';

    if (($state['sent'] ?? '') === 'search') {
        $place = $state['place'] !== '' ? $state['place'] : 'Florida';
        $html .= '<div class="fleet" id="rates"><h2>Tarifas de ejemplo para ' . $this->e($place) . '</h2><p class="lede">Estas tarifas ilustran el layout. No reservan un auto.</p><div class="fleet-grid">';
        foreach ($this->arr($section, 'fleet') as $car) {
            $html .= '<article class="rate-card"><h3>' . $this->e((string) ($car['name'] ?? '')) . '</h3><p class="meta">' . $this->e((string) ($car['meta'] ?? '')) . '</p><p class="price">' . $this->e((string) ($car['price'] ?? '')) . ' <small>' . $this->e((string) ($car['unit'] ?? '')) . '</small></p><ul>';
            foreach ($this->arr($car, 'points') as $point) {
                $html .= '<li>' . $this->e((string) $point) . '</li>';
            }
            $html .= '</ul></article>';
        }
        $html .= '</div></div>';
    }
    return $html . '</div></section>';
}

    private function render_logo_cloud(array $section): string
{
    $html = '<section class="logo-cloud" aria-label="' . $this->e((string) ($section['label'] ?? 'Partners')) . '"><div class="container"><ul class="partners">';
    foreach ($this->arr($section, 'items') as $item) {
        $name = (string) $item;
        $html .= '<li class="partner partner-' . $this->e($this->slugify($name)) . '">' . $this->e($name) . '</li>';
    }
    return $html . '</ul></div></section>';
}

    private function render_info_card(array $tpl, array $item): string
{
    $html = '<article class="info-card">';
    if (!empty($item['image'])) {
        $html .= '<div class="info-card-media">' . $this->img_tag($tpl, (string) $item['image'], (string) ($item['alt'] ?? $item['title'] ?? '')) . '</div>';
    } elseif (!empty($item['icon'])) {
        $html .= '<div class="info-card-icon">' . $this->icon((string) $item['icon']) . '</div>';
    }
    $html .= '<div class="info-card-body">';
    if (!empty($item['meta'])) {
        $html .= '<p class="meta">' . $this->e((string) $item['meta']) . '</p>';
    }
    if (!empty($item['title'])) {
        $html .= '<h3>' . $this->rich((string) $item['title']) . '</h3>';
    }
    if (!empty($item['text'])) {
        $clamp = !empty($item['expand']) ? ' class="is-clamped" data-clamp' : '';
        $html .= '<p' . $clamp . '>' . $this->e((string) $item['text']) . '</p>';
    }
    if (!empty($item['link'])) {
        if (!empty($item['expand'])) {
            $html .= '<button type="button" class="text-link" data-expand data-more="' . $this->e((string) $item['link']) . '" data-less="Show less" aria-expanded="false">' . $this->e((string) $item['link']) . '</button>';
        } else {
            $html .= '<a class="text-link" href="' . $this->e((string) ($item['href'] ?? '#')) . '">' . $this->e((string) $item['link']) . '</a>';
        }
    }
    return $html . '</div></article>';
}

    private function render_cards(array $tpl, array $section): string
{
    $layout = (string) ($section['layout'] ?? 'feature');
    $cols = max(1, min(4, (int) ($section['columns'] ?? 3)));
    $id = (string) ($section['id'] ?? '');
    $html = '<section class="block-cards layout-' . $this->e($layout) . '"' . ($id !== '' ? ' id="' . $this->e($id) . '"' : '') . '><div class="container">';
    $html .= $this->block_head($section);
    $html .= '<div class="card-grid" style="--card-cols:' . $cols . '">';
    foreach ($this->arr($section, 'items') as $item) {
        if (is_array($item)) {
            $html .= $this->render_info_card($tpl, $item);
        }
    }
    if (!empty($section['cta']) && is_array($section['cta'])) {
        $html .= '</div><div class="section-action">' . $this->button_link($section['cta']) . '</div></div></section>';
        return $html;
    }
    return $html . '</div></div></section>';
}

    private function render_media_float(array $tpl, array $section): string
{
    $image = $this->asset_exists($tpl, (string) ($section['image'] ?? '')) ? $this->asset($tpl, (string) $section['image']) : '';
    $id = (string) ($section['id'] ?? '');
    $html = '<section class="media-float"' . ($id !== '' ? ' id="' . $this->e($id) . '"' : '') . ' style="--photo:url(\'' . $this->e($image) . '\')"><div class="container"><div class="float-card">';
    $html .= '<h2>' . $this->rich((string) ($section['title'] ?? '')) . '</h2>';
    foreach ($this->arr($section, 'paragraphs') as $paragraph) {
        $html .= '<p>' . $this->e((string) $paragraph) . '</p>';
    }
    if (!empty($section['cta']) && is_array($section['cta'])) {
        $html .= $this->button_link($section['cta']);
    }
    return $html . '</div></div></section>';
}

    private function render_reviews(array $tpl, array $section): string
{
    $id = (string) ($section['id'] ?? '');
    $html = '<section class="reviews"' . ($id !== '' ? ' id="' . $this->e($id) . '"' : '') . '><div class="container">';
    $html .= $this->block_head($section);
    if (!empty($section['score'])) {
        $html .= '<p class="review-score">' . $this->e((string) $section['score']) . ' ' . $this->stars(5) . ' <strong>' . $this->e((string) ($section['rating'] ?? '')) . '</strong> ' . $this->e((string) ($section['count'] ?? '')) . '</p>';
    }
    $html .= '<div class="rotator" data-rotator data-per="3"><div class="rotator-viewport">';
    foreach ($this->arr($section, 'items') as $item) {
        $html .= '<article class="review-card"><div class="review-top">' . $this->stars((int) ($item['stars'] ?? 5)) . '<time>' . $this->e((string) ($item['date'] ?? '')) . '</time></div>';
        $html .= '<p>' . $this->e((string) ($item['quote'] ?? '')) . '</p><div class="who">';
        if (!empty($item['avatar'])) {
            $html .= $this->img_tag($tpl, (string) $item['avatar'], '', 'avatar');
        }
        $html .= '<span>' . $this->e((string) ($item['name'] ?? '')) . '</span></div></article>';
    }
    return $html . '</div></div></div></section>';
}

    private function render_faq(array $section): string
{
    $id = (string) ($section['id'] ?? 'faq');
    $open = (int) ($section['open'] ?? 0);
    $html = '<section class="faq" id="' . $this->e($id) . '"><div class="container">' . $this->block_head($section);
    $html .= '<div class="accordion" id="acc-' . $this->e($id) . '">';
    foreach ($this->arr($section, 'items') as $index => $item) {
        $show = $index === $open;
        $collapse = 'c-' . $id . '-' . $index;
        $html .= '<div class="accordion-item faq-item"><h3 class="accordion-header"><button class="accordion-button' . ($show ? '' : ' collapsed') . '" type="button" data-bs-toggle="collapse" data-bs-target="#' . $this->e($collapse) . '" aria-expanded="' . ($show ? 'true' : 'false') . '" aria-controls="' . $this->e($collapse) . '"><span>' . $this->e((string) ($item['q'] ?? '')) . '</span><span class="mark-plus" aria-hidden="true"><span class="plus">+</span><span class="minus">−</span></span></button></h3>';
        $html .= '<div id="' . $this->e($collapse) . '" class="accordion-collapse collapse' . ($show ? ' show' : '') . '" data-bs-parent="#acc-' . $this->e($id) . '"><div class="accordion-body">' . $this->e((string) ($item['a'] ?? '')) . '</div></div></div>';
    }
    return $html . '</div></div></section>';
}

    private function render_hero_form(array $tpl, array $section, array $state): string
{
    $image = $this->asset_exists($tpl, (string) ($section['image'] ?? '')) ? $this->asset($tpl, (string) $section['image']) : '';
    $id = (string) ($section['id'] ?? 'contact');
    $html = '<section class="hero-form" id="' . $this->e($id) . '" style="--hero:url(\'' . $this->e($image) . '\')"><div class="container"><div class="hero-form-grid">';
    $html .= '<div class="hero-copy"><h1>' . $this->rich((string) ($section['title'] ?? '')) . '</h1>';
    if (!empty($section['lede'])) {
        $html .= '<p class="lede">' . $this->e((string) $section['lede']) . '</p>';
    }
    $html .= '</div><div class="form-card"><h2 class="form-card-title">' . $this->e((string) ($section['formTitle'] ?? 'Contact')) . '</h2>';
    $html .= $this->success_box($tpl, $state, 'contact');
    $html .= $this->form_open($tpl, 'contact', $id);
    $html .= $this->render_form_grid($this->arr($section, 'fields'), (int) ($section['columns'] ?? 2));
    if ($this->arr($section, 'choices') !== []) {
        $html .= '<div class="span-all">' . $this->render_choices($section['choices']) . '</div>';
    }
    $html .= '<button class="btnx danger" type="submit">' . $this->e((string) ($section['submit'] ?? 'Submit')) . '</button></form></div></div></div></section>';
    return $html;
}

    private function render_split(array $tpl, array $section): string
{
    $id = (string) ($section['id'] ?? '');
    $html = '<section class="split-block"' . ($id !== '' ? ' id="' . $this->e($id) . '"' : '') . '><div class="container"><div class="split-grid">';
    $html .= '<div class="blob">' . $this->img_tag($tpl, (string) ($section['image'] ?? ''), (string) ($section['imageAlt'] ?? '')) . '</div>';
    $html .= '<div><h2>' . $this->rich((string) ($section['title'] ?? '')) . '</h2>';
    if (!empty($section['lede'])) {
        $html .= '<p class="lede">' . $this->e((string) $section['lede']) . '</p>';
    }
    $html .= '<ul class="icon-list">';
    foreach ($this->arr($section, 'items') as $item) {
        $html .= '<li><span class="dot">' . $this->icon((string) ($item['icon'] ?? 'bi-check-lg')) . '</span><div><strong>' . $this->e((string) ($item['title'] ?? '')) . '</strong>';
        if (!empty($item['text'])) {
            $html .= '<p>' . $this->e((string) $item['text']) . '</p>';
        }
        $html .= '</div></li>';
    }
    return $html . '</ul></div></div></div></section>';
}

    private function render_cta(array $section): string
{
    $id = (string) ($section['id'] ?? '');
    $html = '<section class="cta-band"' . ($id !== '' ? ' id="' . $this->e($id) . '"' : '') . '><div class="container"><h2>' . $this->rich((string) ($section['title'] ?? '')) . '</h2>';
    if (!empty($section['lede'])) {
        $html .= '<p>' . $this->e((string) $section['lede']) . '</p>';
    }
    if (!empty($section['cta']) && is_array($section['cta'])) {
        $html .= $this->button_link($section['cta'], 'btnx');
    }
    return $html . '</div></section>';
}

    private function render_testimonial(array $tpl, array $section): string
{
    $id = (string) ($section['id'] ?? '');
    $html = '<section class="testimonial"' . ($id !== '' ? ' id="' . $this->e($id) . '"' : '') . '><div class="container"><div class="quote-layout" data-quote-rotator>';
    $html .= '<div class="quote-copy"><p class="quote-mark" aria-hidden="true">“</p><h2>' . $this->e((string) ($section['title'] ?? '')) . '</h2><div class="quote-slides">';
    $photos = '';
    foreach ($this->arr($section, 'items') as $index => $item) {
        $active = $index === 0 ? ' is-active' : '';
        $html .= '<article class="quote-slide' . $active . '"><p>' . $this->e((string) ($item['quote'] ?? '')) . '</p>' . $this->stars((int) ($item['stars'] ?? 5)) . '<p class="who">' . $this->e((string) ($item['name'] ?? '')) . '</p></article>';
        $photos .= '<div class="quote-photo' . $active . '">' . $this->img_tag($tpl, (string) ($item['image'] ?? ''), (string) ($item['alt'] ?? $item['name'] ?? '')) . '</div>';
    }
    return $html . '</div></div><div class="quote-photos">' . $photos . '</div></div></div></section>';
}

    private function render_hero_person(array $tpl, array $section): string
{
    $id = (string) ($section['id'] ?? 'top');
    $html = '<section class="hero-person" id="' . $this->e($id) . '"><div class="container"><div class="hero-person-grid"><div class="hero-copy">';
    $html .= '<h1>' . $this->rich((string) ($section['title'] ?? '')) . '</h1>';
    if (!empty($section['subtitle'])) {
        $html .= '<p class="subtitle">' . $this->rich((string) $section['subtitle']) . '</p>';
    }
    if (!empty($section['lede'])) {
        $html .= '<p class="lede">' . $this->e((string) $section['lede']) . '</p>';
    }
    if (!empty($section['cta']) && is_array($section['cta'])) {
        $html .= $this->button_link($section['cta']);
    }
    $html .= '</div><div class="hero-portrait">' . $this->img_tag($tpl, (string) ($section['image'] ?? ''), (string) ($section['imageAlt'] ?? ''), '', true) . '</div></div>';
    if ($this->arr($section, 'pills') !== []) {
        $html .= '<div class="pill-row">';
        foreach ($this->arr($section, 'pills') as $pill) {
            $html .= '<a class="pill tone-' . $this->e((string) ($pill['tone'] ?? 'blue')) . '" href="' . $this->e((string) ($pill['href'] ?? '#')) . '">' . $this->icon((string) ($pill['icon'] ?? '')) . '<span>' . $this->e((string) ($pill['label'] ?? '')) . '</span></a>';
        }
        $html .= '</div>';
    }
    return $html . '</div></section>';
}

    private function render_offer(array $tpl, array $section): string
{
    $id = (string) ($section['id'] ?? '');
    $html = '<section class="offer-block"' . ($id !== '' ? ' id="' . $this->e($id) . '"' : '') . '><div class="container"><div class="offer-grid">';
    $html .= '<div class="offer-photo">' . $this->img_tag($tpl, (string) ($section['image'] ?? ''), (string) ($section['imageAlt'] ?? '')) . '</div>';
    $html .= '<div class="offer-copy"><h2>' . $this->rich((string) ($section['title'] ?? '')) . '</h2>';
    if (!empty($section['note'])) {
        $html .= '<p class="meta">' . $this->e((string) $section['note']) . '</p>';
    }
    $html .= '<ul class="ticks">';
    foreach ($this->arr($section, 'points') as $point) {
        $html .= '<li>' . $this->icon('bi-check-circle-fill') . '<span>' . $this->rich((string) $point) . '</span></li>';
    }
    $html .= '</ul>';
    if (!empty($section['cta']) && is_array($section['cta'])) {
        $html .= $this->button_link($section['cta']);
    }
    if (!empty($section['disclaimer'])) {
        $html .= '<p class="fine-print">' . $this->e((string) $section['disclaimer']) . '</p>';
    }
    return $html . '</div></div></div></section>';
}

    private function render_promo(array $section): string
{
    $id = (string) ($section['id'] ?? '');
    $calc = $this->arr($section, 'calculator');
    $html = '<section class="promo-block"' . ($id !== '' ? ' id="' . $this->e($id) . '"' : '') . '><div class="container"><div class="promo-card"><div>';
    $html .= '<h2>' . $this->rich((string) ($section['title'] ?? '')) . '</h2>';
    if (!empty($section['text'])) {
        $html .= '<p>' . $this->e((string) $section['text']) . '</p>';
    }
    if ($calc !== []) {
        $min = (int) ($calc['min'] ?? 1000);
        $max = (int) ($calc['max'] ?? 100000);
        $step = (int) ($calc['step'] ?? 500);
        $value = (int) ($calc['value'] ?? 15000);
        $html .= '<label class="borrow" for="borrow-range">Amount <output data-borrow-out></output></label>';
        $html .= '<input id="borrow-range" type="range" min="' . $min . '" max="' . $max . '" step="' . $step . '" value="' . $value . '" data-borrow data-apr="' . $this->e((string) ($calc['apr'] ?? '3.84')) . '" data-months="' . $this->e((string) ($calc['months'] ?? '36')) . '">';
        $html .= '<p class="estimate">Sample payment <strong data-payment></strong> / month for ' . $this->e((string) ($calc['months'] ?? '36')) . ' months.</p>';
    }
    if (!empty($section['cta']) && is_array($section['cta'])) {
        $html .= $this->button_link($section['cta']);
    }
    if (!empty($section['disclaimer'])) {
        $html .= '<p class="fine-print">' . $this->e((string) $section['disclaimer']) . '</p>';
    }
    $html .= '</div><div class="phone-art" aria-hidden="true"><span class="bill">$</span><span class="hand"></span><span class="phone"><span></span><span></span><span></span></span></div></div></div></section>';
    return $html;
}

    private function render_checklist(array $tpl, array $section): string
{
    $id = (string) ($section['id'] ?? '');
    $html = '<section class="checklist-block"' . ($id !== '' ? ' id="' . $this->e($id) . '"' : '') . '><div class="container"><div class="split-grid">';
    $html .= '<ul class="check-list">';
    foreach ($this->arr($section, 'items') as $item) {
        $html .= '<li><span class="check-ico">' . $this->icon((string) ($item['icon'] ?? 'bi-check-lg')) . '</span><div><h3>' . $this->e((string) ($item['title'] ?? '')) . '</h3><p>' . $this->e((string) ($item['text'] ?? '')) . '</p></div></li>';
    }
    $html .= '</ul><div class="checklist-photo">' . $this->img_tag($tpl, (string) ($section['image'] ?? ''), (string) ($section['imageAlt'] ?? '')) . '</div>';
    return $html . '</div></div></section>';
}

    private function render_scores(array $section): string
{
    $id = (string) ($section['id'] ?? '');
    $html = '<section class="score-block"' . ($id !== '' ? ' id="' . $this->e($id) . '"' : '') . '><div class="container">' . $this->block_head($section) . '<div class="score-row">';
    foreach ($this->arr($section, 'items') as $item) {
        $html .= '<a class="score tone-' . $this->e((string) ($item['tone'] ?? 'good')) . '" href="' . $this->e((string) ($item['href'] ?? '#options')) . '"><strong>' . $this->e((string) ($item['label'] ?? '')) . '</strong><span>' . $this->e((string) ($item['range'] ?? '')) . '</span></a>';
    }
    return $html . '</div></div></section>';
}

    private function render_mosaic(array $tpl, array $section): string
{
    $id = (string) ($section['id'] ?? '');
    $items = $this->arr($section, 'items');
    $html = '<section class="mosaic-block"' . ($id !== '' ? ' id="' . $this->e($id) . '"' : '') . '><div class="container">' . $this->block_head($section);
    if ($items === []) {
        return $html . '</div></section>';
    }
    $feature = $items[0];
    $html .= '<div class="mosaic"><article class="mosaic-feature">' . $this->img_tag($tpl, (string) ($feature['image'] ?? ''), (string) ($feature['alt'] ?? '')) . '<div><h3>' . $this->e((string) ($feature['title'] ?? '')) . '</h3><p class="is-clamped" data-clamp>' . $this->e((string) ($feature['text'] ?? '')) . '</p><button type="button" class="text-link" data-expand data-more="Read more" data-less="Show less" aria-expanded="false">Read more</button></div></article><div class="mosaic-stack">';
    foreach (array_slice($items, 1) as $item) {
        $html .= '<article class="mosaic-mini">' . $this->img_tag($tpl, (string) ($item['image'] ?? ''), (string) ($item['alt'] ?? '')) . '<div><h3>' . $this->e((string) ($item['title'] ?? '')) . '</h3><p class="is-clamped" data-clamp>' . $this->e((string) ($item['text'] ?? '')) . '</p><button type="button" class="text-link" data-expand data-more="Read more" data-less="Show less" aria-expanded="false">Read more</button></div></article>';
    }
    $html .= '</div></div>';
    if (!empty($section['ctaLabel'])) {
        $html .= '<div class="section-action"><button type="button" class="btnx outline" data-expand-all>' . $this->e((string) $section['ctaLabel']) . '</button></div>';
    }
    return $html . '</div></section>';
}

    private function render_icons(array $section): string
{
    $id = (string) ($section['id'] ?? '');
    $html = '<section class="icon-block"' . ($id !== '' ? ' id="' . $this->e($id) . '"' : '') . '><div class="container">' . $this->block_head($section) . '<ul class="icon-grid">';
    foreach ($this->arr($section, 'items') as $item) {
        $html .= '<li><span class="icon-bubble">' . $this->icon((string) ($item['icon'] ?? 'bi-circle')) . '</span><span>' . $this->e((string) ($item['label'] ?? '')) . '</span></li>';
    }
    $html .= '</ul>';
    if (!empty($section['note']) || !empty($section['cta'])) {
        $html .= '<div class="help-line">';
        if (!empty($section['note'])) {
            $html .= '<p>' . $this->e((string) $section['note']) . '</p>';
        }
        if (!empty($section['cta']) && is_array($section['cta'])) {
            $html .= $this->button_link($section['cta']);
        }
        $html .= '</div>';
    }
    return $html . '</div></section>';
}

    private function render_newsletter(array $tpl, array $section, array $state): string
{
    $id = (string) ($section['id'] ?? 'newsletter');
    $layout = (string) ($section['layout'] ?? 'band');
    $form = (string) ($section['form'] ?? 'newsletter');
    $html = '<section class="news news-' . $this->e($layout) . '" id="' . $this->e($id) . '"><div class="container">';
    if (!empty($section['title'])) {
        $html .= '<h2>' . $this->rich((string) $section['title']) . '</h2>';
    }
    if (($state['sent'] ?? '') === $form) {
        $html .= $this->success_box($tpl, $state, $form);
        return $html . '</div></section>';
    }
    $html .= $this->form_open($tpl, $form, $id, 'news-form');
    $html .= '<label class="visually-hidden" for="news-' . $this->e($form) . '">' . $this->e((string) ($section['label'] ?? 'Email')) . '</label>';
    $html .= '<i class="bi bi-envelope" aria-hidden="true"></i>';
    $html .= '<input id="news-' . $this->e($form) . '" class="form-control" type="email" name="email" required autocomplete="email" placeholder="' . $this->e((string) ($section['placeholder'] ?? 'Email address')) . '">';
    $submit = (string) ($section['submit'] ?? 'Subscribe');
    if ($layout === 'line') {
        $html .= '<button class="icon-btn" type="submit" aria-label="' . $this->e($submit) . '"><i class="bi bi-arrow-right" aria-hidden="true"></i></button>';
    } elseif ($layout === 'band') {
        $html .= '<button class="btnx solid" type="submit" aria-label="' . $this->e($submit) . '">' . $this->icon('bi-send') . '</button>';
    } else {
        $html .= '<button class="btnx solid" type="submit">' . $this->e($submit) . '</button>';
    }
    return $html . '</form></div></section>';
}

    private function render_hero_product(array $section): string
{
    $id = (string) ($section['id'] ?? 'top');
    $html = '<section class="hero-product" id="' . $this->e($id) . '"><div class="container"><div class="hero-product-grid"><div class="hero-copy">';
    $html .= '<h1>' . $this->rich((string) ($section['title'] ?? '')) . '</h1>';
    if (!empty($section['lede'])) {
        $html .= '<p>' . $this->e((string) $section['lede']) . '</p>';
    }
    if (!empty($section['cta']) && is_array($section['cta'])) {
        $html .= $this->button_link($section['cta'], 'btnx');
    }
    $html .= '</div><div class="crypto-art" aria-hidden="true"><span class="spark s1"></span><span class="spark s2"></span><span class="chart"></span><span class="slab a"></span><span class="slab b"></span><span class="slab c"></span><span class="coin">₿</span></div></div></div>';
    $html .= '<svg class="hero-wave" viewBox="0 0 1440 110" preserveAspectRatio="none" aria-hidden="true"><path fill="#f7f8fb" d="M0 70c180 40 320-40 520-20s360 70 560 30 240-60 360-20v50H0z"/><path fill="#2f9e57" d="M980 78c80 20 140 8 220-8 40 18 90 28 160 18v22H900z"/></svg>';
    return $html . '</section>';
}

    private function render_timeline(array $section): string
{
    $id = (string) ($section['id'] ?? '');
    $html = '<section class="timeline-block"' . ($id !== '' ? ' id="' . $this->e($id) . '"' : '') . '><div class="orb o1"></div><div class="orb o2"></div><div class="orb o3"></div><div class="container"><ol class="timeline">';
    foreach ($this->arr($section, 'items') as $item) {
        $align = ($item['align'] ?? 'left') === 'right' ? 'right' : 'left';
        $html .= '<li class="align-' . $align . '" style="--node:' . $this->e((string) ($item['color'] ?? '#345ea8')) . '">';
        $html .= '<div class="t-copy"><h3>' . $this->e((string) ($item['title'] ?? '')) . '</h3><p>' . $this->e((string) ($item['text'] ?? '')) . '</p></div>';
        $html .= '<div class="t-node"><span>' . $this->e((string) ($item['year'] ?? '')) . '</span>';
        if (!empty($item['era'])) {
            $html .= '<small>' . $this->e((string) $item['era']) . '</small>';
        }
        $html .= '</div><div class="t-art">' . $this->art_svg((string) ($item['icon'] ?? 'globe')) . '</div></li>';
    }
    return $html . '</ol></div></section>';
}

    private function render_contact(array $tpl, array $section, array $state): string
{
    $id = (string) ($section['id'] ?? 'contact');
    $form = (string) ($section['form'] ?? 'tangible');
    $html = '<section class="contact-band" id="' . $this->e($id) . '"><div class="container"><h2>' . $this->e((string) ($section['title'] ?? 'Contact')) . '</h2>';
    $html .= $this->success_box($tpl, $state, $form);
    if (($state['sent'] ?? '') !== $form) {
        $html .= $this->form_open($tpl, $form, $id, 'contact-card');
        $html .= $this->render_form_grid($this->arr($section, 'fields'), (int) ($section['columns'] ?? 2));
        $html .= '<button class="btnx gold" type="submit">' . $this->e((string) ($section['submit'] ?? 'Submit')) . '</button></form>';
    }
    if (!empty($section['legal'])) {
        $html .= '<p class="foot-legal">' . $this->e((string) $section['legal']) . ' <a class="templates-link" href="index.php">All templates</a></p>';
    }
    return $html . '</div></section>';
}

    private function render_checkout(array $tpl, array $section, array $state): string
{
    $id = (string) ($section['id'] ?? 'checkout');
    $hotel = $this->arr($section, 'hotel');
    $summary = $this->arr($section, 'summary');
    $help = $this->arr($section, 'help');
    $form = $this->arr($section, 'form');
    $done = ($state['sent'] ?? '') === 'checkout';
    $html = '<section class="checkout" id="' . $this->e($id) . '"><div class="container"><div class="checkout-grid"><div class="checkout-main">';
    $html .= '<figure class="hotel-shot">' . $this->img_tag($tpl, (string) ($hotel['image'] ?? ''), (string) ($hotel['imageAlt'] ?? 'Hotel interior'), '', true) . '</figure>';
    $html .= '<div class="hotel-meta"><h1>' . $this->e((string) ($hotel['name'] ?? '')) . '</h1><p>' . $this->e((string) ($hotel['address'] ?? '')) . ' ' . $this->stars((int) ($hotel['stars'] ?? 0)) . '</p></div>';
    $seconds = (int) ($section['holdSeconds'] ?? 900);
    $html .= '<div class="hold-bar">' . $this->e((string) ($section['hold'] ?? 'We can hold this rate for')) . ' <strong data-countdown="' . $seconds . '" data-countdown-done="The hold expired">15:00</strong> <span>minutes</span></div>';

    if ($done) {
        $name = $state['first'] !== '' ? $state['first'] : 'there';
        $html .= '<div class="confirm-panel" role="status"><p class="kicker">Request received</p><h2>Thanks, ' . $this->e($name) . '.</h2><p>' . $this->e((string) ($this->arr($tpl, 'messages')['checkout'] ?? 'This demo did not charge a card.')) . '</p><a class="btnx solid" href="index.php?template=' . $this->e((string) $tpl['slug']) . '#checkout">Start another request</a></div>';
    } else {
        $html .= '<div class="book-card">';
        $html .= $this->success_box($tpl, $state, 'checkout');
        $html .= $this->form_open($tpl, 'checkout', $id);
        $html .= '<h2>' . $this->e((string) ($form['title'] ?? 'Confirm your booking')) . '</h2>';
        foreach ($this->arr($form, 'groups') as $group) {
            $secure = !empty($group['secure']) ? ' secure-box' : '';
            $html .= '<fieldset class="book-group' . $secure . '"><legend>' . $this->e((string) ($group['title'] ?? '')) . '</legend>';
            if (!empty($group['note'])) {
                $html .= '<p class="meta">' . $this->e((string) $group['note']) . '</p>';
            }
            $html .= $this->render_form_grid($this->arr($group, 'fields'), (int) ($group['columns'] ?? 2));
            $html .= '</fieldset>';
        }
        if (!empty($form['consent'])) {
            $html .= '<p class="fine-print">' . $this->e((string) $form['consent']) . '</p>';
        }
        $html .= '<button class="btnx solid" type="submit">' . $this->e((string) ($form['submit'] ?? 'Complete Reservation')) . '</button></form></div>';
    }
    $html .= '</div><aside class="checkout-side">';
    $html .= '<div class="summary-card">';
    if (!empty($summary['badge'])) {
        $html .= '<p class="jackpot">' . $this->e((string) $summary['badge']) . '</p>';
    }
    $html .= '<h2>' . $this->e((string) ($summary['title'] ?? 'Booking summary')) . '</h2><dl class="summary-rows">';
    foreach ($this->arr($summary, 'rows') as $row) {
        $emphasis = !empty($row['emphasis']) ? ' class="is-total"' : '';
        $html .= '<div' . $emphasis . '><dt>' . $this->e((string) ($row['label'] ?? '')) . '</dt><dd>' . $this->e((string) ($row['value'] ?? '')) . '</dd></div>';
    }
    $html .= '</dl>';
    if (!empty($summary['policy'])) {
        $html .= '<p class="fine-print">' . $this->e((string) $summary['policy']) . '</p>';
    }
    $html .= '</div><div class="help-card" id="help"><p>' . $this->icon('bi-telephone') . ' ' . $this->e((string) ($help['title'] ?? 'Need assistance?')) . '</p>';
    if (!empty($help['phone'])) {
        $html .= '<a class="help-phone" href="' . $this->e((string) ($help['href'] ?? '#')) . '">' . $this->e((string) $help['phone']) . '</a>';
    }
    if (!$done && !empty($help['button'])) {
        $html .= '<button class="btnx solid" type="submit" form="form-checkout">' . $this->e((string) $help['button']) . '</button>';
    }
    $html .= '</div></aside></div></div></section>';
    return $html;
}

    private function render_footer(array $tpl, array $section): string
{
    $layout = (string) ($section['layout'] ?? 'columns');
    $id = (string) ($section['id'] ?? 'footer');
    $html = '<footer class="site-footer layout-' . $this->e($layout) . '" id="' . $this->e($id) . '"><div class="container">';
    if (!empty($section['badge']) && $layout === 'center') {
        $html .= $this->footer_badge((string) $section['badge']);
    }
    if ($layout === 'center') {
        $html .= '<ul class="foot-links">';
        foreach ($this->arr($section, 'links') as $link) {
            $html .= '<li><a href="' . $this->e((string) ($link['href'] ?? '#')) . '">' . $this->e((string) ($link['label'] ?? '')) . '</a></li>';
        }
        $html .= '</ul>';
    } else {
        $html .= '<div class="foot-grid"><div class="foot-brand">' . $this->brand((string) ($section['brand'] ?? $tpl['slug']), (string) ($section['wordmark'] ?? ''));
        if (!empty($section['blurb'])) {
            $html .= '<p>' . $this->e((string) $section['blurb']) . '</p>';
        }
        $lines = [];
        if (!empty($section['phone']) && is_array($section['phone'])) {
            $lines[] = ['icon' => 'bi-telephone', 'text' => $section['phone']['text'] ?? '', 'href' => $section['phone']['href'] ?? ''];
        }
        if (!empty($section['email']) && is_array($section['email'])) {
            $lines[] = ['icon' => 'bi-envelope', 'text' => $section['email']['text'] ?? '', 'href' => $section['email']['href'] ?? ''];
        }
        $html .= $this->contact_lines($lines);
        $html .= $this->social_list($this->arr($section, 'social'), (string) ($section['socialStyle'] ?? 'icons'));
        $html .= '</div>';
        foreach ($this->arr($section, 'columns') as $column) {
            $html .= '<div><h3>' . $this->e((string) ($column['title'] ?? '')) . '</h3>';
            if ($this->arr($column, 'links') !== []) {
                $html .= '<ul>';
                foreach ($this->arr($column, 'links') as $link) {
                    $html .= '<li><a href="' . $this->e((string) ($link['href'] ?? '#')) . '">' . $this->e((string) ($link['label'] ?? '')) . '</a></li>';
                }
                $html .= '</ul>';
            }
            $html .= $this->contact_lines($this->arr($column, 'contacts'));
            $html .= '</div>';
        }
        $html .= '</div>';
    }
    if ($this->arr($section, 'payments') !== []) {
        $html .= '<ul class="pay-row" aria-label="Payment methods">';
        foreach ($this->arr($section, 'payments') as $payment) {
            $html .= '<li class="pay pay-' . $this->e($this->slugify((string) $payment)) . '">' . $this->e((string) $payment) . '</li>';
        }
        $html .= '</ul>';
    }
    if (!empty($section['disclosure'])) {
        $html .= '<p class="disclosure">' . $this->e((string) $section['disclosure']) . '</p>';
    }
    if (!empty($section['badge']) && $layout !== 'center') {
        $html .= $this->footer_badge((string) $section['badge']);
    }
    $html .= '<div class="foot-base"><p>' . $this->e((string) ($section['legal'] ?? '')) . '</p><a class="templates-link" href="index.php">All templates</a></div>';
    return $html . '</div></footer>';
}

    private function render_section(array $tpl, array $section, array $state): string
{
    return match ((string) ($section['type'] ?? '')) {
        'hero-search' => $this->render_hero_search($tpl, $section, $state),
        'logo-cloud' => $this->render_logo_cloud($section),
        'cards' => $this->render_cards($tpl, $section),
        'media-float' => $this->render_media_float($tpl, $section),
        'reviews' => $this->render_reviews($tpl, $section),
        'faq' => $this->render_faq($section),
        'hero-form' => $this->render_hero_form($tpl, $section, $state),
        'split' => $this->render_split($tpl, $section),
        'cta' => $this->render_cta($section),
        'testimonial' => $this->render_testimonial($tpl, $section),
        'hero-person' => $this->render_hero_person($tpl, $section),
        'offer' => $this->render_offer($tpl, $section),
        'promo' => $this->render_promo($section),
        'checklist' => $this->render_checklist($tpl, $section),
        'scores' => $this->render_scores($section),
        'mosaic' => $this->render_mosaic($tpl, $section),
        'icons' => $this->render_icons($section),
        'newsletter' => $this->render_newsletter($tpl, $section, $state),
        'hero-product' => $this->render_hero_product($section),
        'timeline' => $this->render_timeline($section),
        'contact' => $this->render_contact($tpl, $section, $state),
        'checkout' => $this->render_checkout($tpl, $section, $state),
        default => '',
    };
}
}
