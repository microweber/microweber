
 const alignBtnCenterIcon = `
<svg viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M10 20L10 2H12L12 20H10Z" fill="currentColor"/>
<path d="M17.3284 12H22V10L17.3285 10L20.3285 7L17.5 7L13.5 11L17.5 15H20.3284L17.3284 12Z" fill="currentColor"/>
<path d="M0 12H4.67162L1.67162 15H4.50005L8.5 11L4.49995 7L1.67153 7L4.67153 10H0V12Z" fill="currentColor"/>
</svg>
`;
const alignBtnLeftIcon = `
<svg   viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M2 2V20H4L4 2H2Z" fill="currentColor"/>
<path d="M16 12H9.32838L12.3284 15H9.49995L5.5 11L9.50005 7L12.3285 7L9.32847 10L16 10V12Z" fill="currentColor"/>
</svg>
`;
const alignBtnRightIcon = `
<svg viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M20 20L20 2H18L18 20H20Z" fill="currentColor"/>
<path d="M6 10H12.6716L9.67162 7L12.5 7L16.5 11L12.5 15H9.67153L12.6715 12H6V10Z" fill="currentColor"/>
</svg>
`;

let currentAlignBtnIcon = alignBtnCenterIcon;

let moduleButtonSettings = [
    {
        title: 'Align Settings',
        icon: currentAlignBtnIcon,

        menu: [
            {
                name: 'leftAlign',
                nodes: [
                    {
                        title: 'leftAlign',
                        text: '',
                        icon: alignBtnLeftIcon,
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
                        title: 'centerAlign',
                        text: '',
                        icon: alignBtnCenterIcon,
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
                        title: 'rightAlign',
                        text: '',
                        icon: alignBtnRightIcon,
                        action: function (el) {
                            saveBtnAlign(el, 'right');
                        }
                    },
                ]
            },
        ]
    }
];

function saveBtnAlign(el, align) {

    let moduleId = el.getAttribute('id');
    let moduleType = el.getAttribute('data-type');
    if (!moduleType) {
        moduleType = el.getAttribute('type');
    }

    if (align == 'left') {
        moduleButtonSettings[0].icon = alignBtnLeftIcon;
    }
    if (align == 'center') {
        moduleButtonSettings[0].icon = alignBtnCenterIcon;
    }
    if (align == 'right') {
        moduleButtonSettings[0].icon = alignBtnRightIcon;
    }

    var data = {
        option_group: moduleId,
        option_key: 'align',
        option_value: align,
        module: moduleType,
    };

    console.log(data);

    mw.options.saveOption(data, function () {

        mw.app.liveEdit.moduleHandleContent.menu.setMenu('dynamic',  moduleButtonSettings);

        // Saved
        mw.app.editor.dispatch('onModuleSettingsChanged', {
            'moduleId': moduleId
        });

    });
}

mw.quickSettings.btn = moduleButtonSettings;


