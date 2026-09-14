// task-2026-09-14-qskit — Per-module quick-settings configs.
//
// Each config drives the shared kit (quick-settings-kit.js). Every control key
// is the SAME option the module's real settings form + template already use, so
// a change on the canvas round-trips exactly like the full settings dialog.
//
// Batch 1 (verified template consumption): Marquee, Spacer, Breadcrumb.
// Remaining modules are added here incrementally, one verified config at a time.
(function () {
    if (!window.mw || !mw.quickSettingsKit) { return; }
    var K = mw.quickSettingsKit;

    // ── Marquee ── options: text, fontSize, animationSpeed, textWeight,
    //    textStyle, textColor (all rendered directly by the template). ────────
    K.register({
        type: 'marquee',
        title: 'Marquee',
        badge: 'Mq',
        sections: [
            { type: 'text', label: 'Text', key: 'text', placeholder: 'Your text here' },
            { type: 'swatches', label: 'Color', key: 'textColor' },
            {
                type: 'segmented', label: 'Size', key: 'fontSize',
                options: [{ label: 'S', value: 24 }, { label: 'M', value: 46 }, { label: 'L', value: 72 }]
            },
            {
                type: 'segmented', label: 'Speed', key: 'animationSpeed',
                options: [{ label: 'Slow', value: 50 }, { label: 'Normal', value: 100 }, { label: 'Fast', value: 200 }]
            },
            {
                type: 'segmented', label: 'Weight', key: 'textWeight',
                options: [{ label: 'Normal', value: 'normal' }, { label: 'Bold', value: 'bold' }]
            },
            {
                type: 'segmented', label: 'Style', key: 'textStyle',
                options: [{ label: 'Normal', value: 'normal' }, { label: 'Italic', value: 'italic' }]
            },
            { type: 'advanced', label: 'Advanced', hint: 'All marquee settings' }
        ]
    });

    // ── Spacer ── option: height (free-form CSS length, fed to the style attr).
    K.register({
        type: 'spacer',
        title: 'Spacer',
        badge: 'Sp',
        sections: [
            {
                type: 'segmented', label: 'Height', key: 'height',
                options: [{ label: 'S', value: '24px' }, { label: 'M', value: '48px' }, { label: 'L', value: '96px' }]
            },
            { type: 'text', label: 'Custom height', key: 'height', placeholder: '50px', suffix: 'px / rem / vh' }
        ]
    });

    // ── Breadcrumb ── option: data-start-from ('' | page | category). ────────
    K.register({
        type: 'breadcrumb',
        title: 'Breadcrumb',
        badge: 'Br',
        sections: [
            {
                type: 'segmented', label: 'Start from', key: 'data-start-from',
                options: [{ label: 'Default', value: '' }, { label: 'Page', value: 'page' }, { label: 'Category', value: 'category' }]
            },
            { type: 'advanced', label: 'Advanced', hint: 'All breadcrumb settings' }
        ]
    });
})();
