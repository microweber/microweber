const i18n =  {
    en: {
        "Layout": "Layout",
        "Add layout": "Add layout",
        "Title": "Title",
        "Settings": "Settings",
        "Paragraph": "Paragraph",
        "Text": "Text",
    },
    bg: {

    }
}

export const lang = (label, lang) => {
    if(!lang || !i18n[lang]) {
        lang = 'en';
    }
    return i18n[lang][label] || label;
}
