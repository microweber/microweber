// Menu module — Live Edit quick-settings panel (kit-based).
// Loaded AFTER the quick-settings kit (core LiveEditServiceProvider registers
// the kit before this module script), so mw.quickSettingsKit is available.
// Registers under mw.quickSettings.menu; the handle opens it via .action(el).
(function () {
    function register() {
        if (!(window.mw && mw.quickSettingsKit && mw.quickSettingsKit.register)) { return false; }
        mw.quickSettingsKit.register({
            type: 'menu',
            title: 'Menu',
            badge: 'Me',
            tabs: [
                {
                    name: 'Menu',
                    sections: [
                        { type: 'menuselect', key: 'menu_name', label: 'Menu' },
                        { type: 'menuitems', label: 'Items', addLabel: 'Add menu item' },
                        { type: 'advanced', label: 'Advanced', hint: 'Manage all menus' }
                    ]
                },
                {
                    name: 'Design',
                    sections: [
                        { type: 'advanced', label: 'Design settings', hint: 'Skins, style & full options' }
                    ]
                }
            ]
        });
        return true;
    }

    // Register now if the kit is ready; otherwise poll briefly (guards against a
    // future load-order change without hard-failing).
    if (!register()) {
        var tries = 0;
        var t = setInterval(function () {
            if (register() || ++tries > 40) { clearInterval(t); }
        }, 50);
    }
})();
