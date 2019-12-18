mw.tools.notification = function (body, tag, icon) {
    if (!body) return;
    var n = window.Notification || window.webkitNotification || window.mozNotification;
    if (typeof n == 'undefined') {
        return false;
    }
    if (n.permission == 'granted') {
        new n("MW Update", {
            tag: tag || "Microweber",
            body: body,
            icon: icon || mw.settings.includes_url + "img/logomark.png"
        });
    }
    else if (n.permission == 'default') {
        n.requestPermission(function (result) {

            if (result == 'granted') {
                return mw.tools.notification(body, tag, icon)
            }
            else if (result == 'denied') {
                mw.notification.error('Notifications are blocked')
            }
            else if (result == 'default') {
                mw.notification.warn('Notifications are canceled')

            }
        });
    }
}
