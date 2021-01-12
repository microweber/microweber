mw.lang = function (key) {
    var camel = key.trim().replace(/(?:^\w|[A-Z]|\b\w)/g, function (letter, index) {
        return index == 0 ? letter.toLowerCase() : letter.toUpperCase();
    }).replace(/\s+/g, '');
    if (mw._lang[camel]) {
        return mw._lang[camel];
    }
    else {
        // console.warn('"' + key + '" is not present.');
        return key;
    }
};
mw.msg = mw._lang = {
    uniqueVisitors: 'Unique visitors',
    allViews: 'All views',
    date: 'Date',
    weekDays: {
        regular: [
            'Sunday',
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
            'Friday',
            'Saturday'
        ],
        short: [
            'Sun',
            'Mon',
            'Tue',
            'Wed',
            'Thu',
            'Fri',
            'Sat'
        ]
    },
    months: {
        regular: [
            'January',
            'February',
            'March',
            'April',
            'May',
            'June',
            'July',
            'August',
            'September',
            'October',
            'November',
            'December'
        ],
        short: [
            'Jan',
            'Feb',
            'Mar',
            'Apr',
            'May',
            'June',
            'July',
            'Aug',
            'Sept',
            'Oct',
            'Nov',
            'Dec'
        ]
    },
    ok: "OK",
    category: "Category",
    published: "Published",
    unpublished: "Unpublished",
    contentunpublished: "Content is unpublished",
    contentpublished: "Content is published",
    save: "Save",
    saving: "Saving",
    saved: "Saved",
    settings: "Settings",
    cancel: "Cancel",
    remove: "Remove",
    close: "Close",
    to_delete_comment: "Are you sure you want to delete this comment",
    del: "Are you sure you want to delete this?",
    save_and_continue: "Save &amp; Continue",
    before_leave: "Leave without saving",
    session_expired: "Your session has expired",
    login_to_continue: "Please login to continue",
    more: "More",
    templateSettingsHidden: "Template settings",
    less: "Less",
    product_added: "Your product is added to cart",
    no_results_for: "No results for",
    switch_to_modules: 'Switch to Modules',
    switch_to_layouts: 'Switch to Layouts',
    loading: 'Loading',
    edit: 'Edit',
    change: 'Change',
    submit: 'Submit',
    settingsSaved: 'Settings are saved',
    addImage: 'Add new image'
};
