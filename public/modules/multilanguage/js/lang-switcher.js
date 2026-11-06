/*
 * Language switcher behaviour — self-contained toggle, no Bootstrap dropdown
 * dependency, so the switcher works on any template (Plain / Base / Big) and
 * never throws when Bootstrap's JS isn't present or the markup isn't its
 * expected shape. Shipped by the Multilanguage module and injected into the page
 * <head> by MultilanguageServiceProvider only when multilanguage is active.
 */
(function () {
    try { if (window.mw && mw.lib && mw.lib.require) { mw.lib.require('flag_icons'); } } catch (e) {}

    // Guard against double-wiring if the asset is included more than once.
    if (window.__mwLangSwitcherWired) { return; }
    window.__mwLangSwitcherWired = true;

    function closeAll(except) {
        document.querySelectorAll('.mw-lang-switcher__menu.show').forEach(function (m) {
            if (m !== except) {
                m.classList.remove('show');
                var t = m.parentElement && m.parentElement.querySelector('.mw-lang-switcher__toggle');
                if (t) { t.setAttribute('aria-expanded', 'false'); }
            }
        });
    }

    document.addEventListener('click', function (e) {
        var toggle = e.target.closest ? e.target.closest('.mw-lang-switcher__toggle') : null;
        if (toggle) {
            e.preventDefault();
            var menu = toggle.parentElement.querySelector('.mw-lang-switcher__menu');
            if (!menu) { return; }
            var willShow = !menu.classList.contains('show');
            closeAll(willShow ? menu : null);
            menu.classList.toggle('show', willShow);
            toggle.setAttribute('aria-expanded', willShow ? 'true' : 'false');
            return;
        }
        // click outside any switcher → close
        if (!(e.target.closest && e.target.closest('.mw-lang-switcher'))) {
            closeAll(null);
        }
    });

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') { closeAll(null); }
    });
})();
