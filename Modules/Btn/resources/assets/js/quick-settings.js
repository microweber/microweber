function getInitialAlignIcon(moduleId) {
    const alignBtnCenterIcon = `<svg viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10 20L10 2H12L12 20H10Z" fill="currentColor"/><path d="M17.3284 12H22V10L17.3285 10L20.3285 7L17.5 7L13.5 11L17.5 15H20.3284L17.3284 12Z" fill="currentColor"/><path d="M0 12H4.67162L1.67162 15H4.50005L8.5 11L4.49995 7L1.67153 7L4.67153 10H0V12Z" fill="currentColor"/></svg>`;
    const alignBtnLeftIcon = `<svg viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2 2V20H4L4 2H2Z" fill="currentColor"/><path d="M16 12H9.32838L12.3284 15H9.49995L5.5 11L9.50005 7L12.3285 7L9.32847 10L16 10V12Z" fill="currentColor"/></svg>`;
    const alignBtnRightIcon = `<svg viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M20 20L20 2H18L18 20H20Z" fill="currentColor"/><path d="M6 10H12.6716L9.67162 7L12.5 7L16.5 11L12.5 15H9.67153L12.6715 12H6V10Z" fill="currentColor"/></svg>`;

    try {
        const moduleData = window.mw.top().app.modules.getModuleInlineViewData(moduleId);
        if (moduleData && moduleData.options && moduleData.options.align) {
            switch (moduleData.options.align) {
                case 'left':
                    return alignBtnLeftIcon;
                case 'right':
                    return alignBtnRightIcon;
                case 'center':
                default:
                    return alignBtnCenterIcon;
            }
        }
    } catch (error) {
        console.warn('Could not get module inline view data:', error);
    }

    return alignBtnCenterIcon;
}

let moduleButtonSettings = [

/*    {
        title: 'Button',
        titleVisible: true,

        icon: function (module) {
            if (window.mw?.top()?.app?.modules) {
                return window.mw.top().app.modules.getModuleIcon('btn');
            }
        }, action: function (el) {
            window.mw.top().app.editor.dispatch('onModuleSettingsRequest', el);
        }
    },*/

    {


        title: 'Align Settings',
        icon: `<svg viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10 20L10 2H12L12 20H10Z" fill="currentColor"/><path d="M17.3284 12H22V10L17.3285 10L20.3285 7L17.5 7L13.5 11L17.5 15H20.3284L17.3284 12Z" fill="currentColor"/><path d="M0 12H4.67162L1.67162 15H4.50005L8.5 11L4.49995 7L1.67153 7L4.67153 10H0V12Z" fill="currentColor"/></svg>`,

        iconxx: function (el) {
            let moduleId = el.getAttribute('id');
            return getInitialAlignIcon(moduleId);
        },

        menu: [
            {
                name: 'leftAlign',
                nodes: [
                    {
                        title: 'Align left',
                        text: '',
                        icon: `<svg viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2 2V20H4L4 2H2Z" fill="currentColor"/><path d="M16 12H9.32838L12.3284 15H9.49995L5.5 11L9.50005 7L12.3285 7L9.32847 10L16 10V12Z" fill="currentColor"/></svg>`,
                        action: function (el) {
                            saveBtnAlign(el, 'left');
                        }
                    },
                ]
            },
            {
                name: 'centerAlign',
                nodes: [
                    {
                        title: 'Align center',
                        text: '',
                        icon: `<svg viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10 20L10 2H12L12 20H10Z" fill="currentColor"/><path d="M17.3284 12H22V10L17.3285 10L20.3285 7L17.5 7L13.5 11L17.5 15H20.3284L17.3284 12Z" fill="currentColor"/><path d="M0 12H4.67162L1.67162 15H4.50005L8.5 11L4.49995 7L1.67153 7L4.67153 10H0V12Z" fill="currentColor"/></svg>`,
                        action: function (el) {
                            saveBtnAlign(el, 'center');
                        }
                    },
                ]
            },
            {
                name: 'rightAlign',
                nodes: [
                    {
                        title: 'Align right',
                        text: '',
                        icon: `<svg viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M20 20L20 2H18L18 20H20Z" fill="currentColor"/><path d="M6 10H12.6716L9.67162 7L12.5 7L16.5 11L12.5 15H9.67153L12.6715 12H6V10Z" fill="currentColor"/></svg>`,
                        action: function (el) {
                            saveBtnAlign(el, 'right');
                        }
                    },
                ]
            },
        ]
    }
];

// task-2026-09-06-quickactions — generic quick-setting saver for the btn module.
// Mirrors the pagy/framer button popover (color chip → applies instantly).
// Persists a single module option via mw.options.saveOption and refreshes the
// module on the canvas, same round-trip saveBtnAlign uses.
function saveBtnOption(el, key, value) {
    let moduleId = el.getAttribute('id');
    let moduleType = el.getAttribute('data-type') || el.getAttribute('type');
    mw.options.saveOption({
        option_group: moduleId,
        option_key: key,
        option_value: value,
        module: moduleType,
    }, function () {
        mw.app.editor.dispatch('onModuleSettingsChanged', { 'moduleId': moduleId });
    });
}

// task-2026-09-06-quickactions — Color quick-setting: a swatch palette that
// sets the button's background (and a contrasting text colour) in one click.
function swatchIcon(color) {
    return '<svg viewBox="0 0 22 22" xmlns="http://www.w3.org/2000/svg">'
        + '<circle cx="11" cy="11" r="8" fill="' + color + '" stroke="rgba(0,0,0,.18)" stroke-width="1"/></svg>';
}

// [background, contrasting text] pairs; last one clears back to the theme default.
var BTN_QUICK_COLORS = [
    ['#0d6efd', '#ffffff', 'Blue'],
    ['#2fb344', '#ffffff', 'Green'],
    ['#dc2626', '#ffffff', 'Red'],
    ['#f59e0b', '#182433', 'Amber'],
    ['#7c3aed', '#ffffff', 'Purple'],
    ['#182433', '#ffffff', 'Ink'],
    ['#ffffff', '#182433', 'White'],
];

var moduleButtonColorSetting = {
    title: 'Color',
    icon: '<svg viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M11 3a8 8 0 1 0 0 16c1.1 0 2-.9 2-2 0-.5-.2-1-.5-1.3-.3-.4-.5-.8-.5-1.2 0-.9.7-1.5 1.5-1.5H15a4 4 0 0 0 4-4c0-3.9-3.6-7-8-7Zm-4.5 8a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3Zm3-4a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3Zm5 0a1.5 1.5 0 1 1 0-3 1.5 1.5 0 0 1 0 3Z" fill="currentColor"/></svg>',
    menu: BTN_QUICK_COLORS.map(function (c) {
        return {
            name: 'color-' + c[2].toLowerCase(),
            nodes: [{
                title: c[2],
                text: '',
                icon: swatchIcon(c[0]),
                action: function (el) {
                    saveBtnOption(el, 'backgroundColor', c[0]);
                    saveBtnOption(el, 'color', c[1]);
                },
            }],
        };
    }),
};

moduleButtonSettings.push(moduleButtonColorSetting);

// task-2026-09-07-btnquick — Type (style) + Size quick-settings, matching the
// reference button panel. Both persist a dedicated module option ('style' /
// 'size') that the btn template applies directly as a class: `btn {style} {size}`.
var BTN_TYPES = [
    ['Solid', 'btn-primary'],
    ['Outline', 'btn-outline-primary'],
    ['Soft', 'btn-light'],
];
function btnTypeIcon(kind) {
    if (kind === 'btn-primary') { return '<svg viewBox="0 0 22 14" xmlns="http://www.w3.org/2000/svg"><rect x="1" y="1" width="20" height="12" rx="6" fill="currentColor"/></svg>'; }
    if (kind === 'btn-outline-primary') { return '<svg viewBox="0 0 22 14" xmlns="http://www.w3.org/2000/svg"><rect x="1.6" y="1.6" width="18.8" height="10.8" rx="5.4" fill="none" stroke="currentColor" stroke-width="1.6"/></svg>'; }
    return '<svg viewBox="0 0 22 14" xmlns="http://www.w3.org/2000/svg"><rect x="1" y="1" width="20" height="12" rx="6" fill="currentColor" opacity="0.28"/></svg>';
}
var moduleButtonTypeSetting = {
    title: 'Type',
    icon: '<svg viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg"><rect x="3" y="7" width="16" height="8" rx="4" fill="currentColor"/></svg>',
    menu: BTN_TYPES.map(function (t) {
        return {
            name: 'type-' + t[0].toLowerCase(),
            nodes: [{ title: t[0], text: '', icon: btnTypeIcon(t[1]), action: function (el) { saveBtnOption(el, 'button_style', t[1]); } }],
        };
    }),
};
moduleButtonSettings.push(moduleButtonTypeSetting);

// Size — Small / Medium / Large → Bootstrap btn-sm / (default) / btn-lg.
function btnSizeIcon(px) {
    return '<svg viewBox="0 0 22 22" xmlns="http://www.w3.org/2000/svg"><text x="11" y="16" text-anchor="middle" font-size="' + px + '" font-family="sans-serif" font-weight="700" fill="currentColor">A</text></svg>';
}
var moduleButtonSizeSetting = {
    title: 'Size',
    icon: '<svg viewBox="0 0 22 22" xmlns="http://www.w3.org/2000/svg"><text x="4" y="16" font-size="9" font-family="sans-serif" font-weight="700" fill="currentColor">A</text><text x="12" y="16" font-size="14" font-family="sans-serif" font-weight="700" fill="currentColor">A</text></svg>',
    menu: [
        { name: 'size-small', nodes: [{ title: 'Small', text: '', icon: btnSizeIcon(9), action: function (el) { saveBtnOption(el, 'button_size', 'btn-sm'); } }] },
        { name: 'size-medium', nodes: [{ title: 'Medium', text: '', icon: btnSizeIcon(12), action: function (el) { saveBtnOption(el, 'button_size', ''); } }] },
        { name: 'size-large', nodes: [{ title: 'Large', text: '', icon: btnSizeIcon(16), action: function (el) { saveBtnOption(el, 'button_size', 'btn-lg'); } }] },
    ],
};
moduleButtonSettings.push(moduleButtonSizeSetting);

function saveBtnAlign(el, align) {

    let moduleId = el.getAttribute('id');
    let moduleType = el.getAttribute('data-type');
    if (!moduleType) {
        moduleType = el.getAttribute('type');
    }

    const alignBtnLeftIcon = `<svg viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M2 2V20H4L4 2H2Z" fill="currentColor"/><path d="M16 12H9.32838L12.3284 15H9.49995L5.5 11L9.50005 7L12.3285 7L9.32847 10L16 10V12Z" fill="currentColor"/></svg>`;
    const alignBtnCenterIcon = `<svg viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M10 20L10 2H12L12 20H10Z" fill="currentColor"/><path d="M17.3284 12H22V10L17.3285 10L20.3285 7L17.5 7L13.5 11L17.5 15H20.3284L17.3284 12Z" fill="currentColor"/><path d="M0 12H4.67162L1.67162 15H4.50005L8.5 11L4.49995 7L1.67153 7L4.67153 10H0V12Z" fill="currentColor"/></svg>`;
    const alignBtnRightIcon = `<svg viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg"><path d="M20 20L20 2H18L18 20H20Z" fill="currentColor"/><path d="M6 10H12.6716L9.67162 7L12.5 7L16.5 11L12.5 15H9.67153L12.6715 12H6V10Z" fill="currentColor"/></svg>`;

    if (align == 'left') {
        moduleButtonSettings[0].icon = function () {
            return alignBtnLeftIcon;
        };
    }
    if (align == 'center') {
        moduleButtonSettings[0].icon = function () {
            return alignBtnCenterIcon;
        };
    }
    if (align == 'right') {
        moduleButtonSettings[0].icon = function () {
            return alignBtnRightIcon;
        };
    }

    var data = {
        option_group: moduleId,
        option_key: 'align',
        option_value: align,
        module: moduleType,
    };


    mw.options.saveOption(data, function () {

        mw.app.liveEdit.moduleHandleContent.menu.setMenu('dynamic', moduleButtonSettings);

        // Saved
        mw.app.editor.dispatch('onModuleSettingsChanged', {
            'moduleId': moduleId
        });

    });
}

mw.quickSettings.btn = moduleButtonSettings;


