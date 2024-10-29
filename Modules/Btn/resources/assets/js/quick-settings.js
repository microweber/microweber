
const alignBtnCenterIcon = '<img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjIiIGhlaWdodD0iMjIiIHZpZXdCb3g9IjAgMCAyMiAyMiIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTEwIDIwTDEwIDJIMTJMMTIgMjBIMTBaIiBmaWxsPSIjMEUwRTBFIi8+CjxwYXRoIGQ9Ik0xNy4zMjg0IDEySDIyVjEwTDE3LjMyODUgMTBMMjAuMzI4NSA3TDE3LjUgN0wxMy41IDExTDE3LjUgMTVIMjAuMzI4NEwxNy4zMjg0IDEyWiIgZmlsbD0iIzBFMEUwRSIvPgo8cGF0aCBkPSJNMCAxMkg0LjY3MTYyTDEuNjcxNjIgMTVINC41MDAwNUw4LjUgMTFMNC40OTk5NSA3TDEuNjcxNTMgN0w0LjY3MTUzIDEwSDBWMTJaIiBmaWxsPSIjMEUwRTBFIi8+Cjwvc3ZnPgo=" style="width: 22px; height: 22px;">';
const alignBtnLeftIcon = '<img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjIiIGhlaWdodD0iMjIiIHZpZXdCb3g9IjAgMCAyMiAyMiIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTIgMlYyMEg0TDQgMkgyWiIgZmlsbD0iIzBFMEUwRSIvPgo8cGF0aCBkPSJNMTYgMTJIOS4zMjgzOEwxMi4zMjg0IDE1SDkuNDk5OTVMNS41IDExTDkuNTAwMDUgN0wxMi4zMjg1IDdMOS4zMjg0NyAxMEwxNiAxMFYxMloiIGZpbGw9IiMwRTBFMEUiLz4KPC9zdmc+Cg==" style="width: 22px; height: 22px;">';
const alignBtnRightIcon = '<img src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjIiIGhlaWdodD0iMjIiIHZpZXdCb3g9IjAgMCAyMiAyMiIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZD0iTTIwIDIwTDIwIDJIMThMMTggMjBIMjBaIiBmaWxsPSIjMEUwRTBFIi8+CjxwYXRoIGQ9Ik02IDEwSDEyLjY3MTZMOS42NzE2MiA3TDEyLjUgN0wxNi41IDExTDEyLjUgMTVIOS42NzE1M0wxMi42NzE1IDEySDZWMTBaIiBmaWxsPSIjMEUwRTBFIi8+Cjwvc3ZnPgo=" style="width: 22px; height: 22px;">';

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


