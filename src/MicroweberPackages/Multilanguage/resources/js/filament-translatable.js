document.addEventListener('alpine:init', () => {

    Alpine.store('translationLocale', {

        locale: window.filamentData.multilanguage.translationLocale,
        flagIcons: window.filamentData.multilanguage.flagIcons,
        flagUrl: window.filamentData.multilanguage.flagIcons[window.filamentData.multilanguage.translationLocale],
        supportedLocales: window.filamentData.multilanguage.supportedLocales,

        setLocale(locale) {
            this.locale = locale;
            this.flagUrl = this.flagIcons[locale];
        },

    })

})
