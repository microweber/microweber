document.addEventListener('alpine:init', () => {

    Alpine.store('translationLocale', {

        locale: window.filamentData?.multilanguage?.translationLocale?.locale ?? 'en',
        shortLocale: window.filamentData?.multilanguage?.translationLocale?.abr ?? 'en',
        flagUrl: window.filamentData?.multilanguage?.translationLocale?.iconUrl ?? '',
        supportedLocales: window.filamentData?.multilanguage?.supportedLocales ?? [],


        getFlagUrl(locale) {
            let flagUrl = '';
            this.supportedLocales.forEach((supportedLocale) => {
                if (supportedLocale.locale == locale) {
                    flagUrl = supportedLocale.iconUrl;
                }
            });
            return flagUrl;
        },
        getShortLocale(locale) {
            let shortLocale = '';
            this.supportedLocales.forEach((supportedLocale) => {
                if (supportedLocale.locale == locale) {
                    shortLocale = supportedLocale.abr;
                }
            });
            return shortLocale;
        },

        setLocale(locale) {
            this.locale = locale;
            this.flagUrl = this.getFlagUrl(locale);
            this.shortLocale = this.getShortLocale(locale);
        },

    })

})
