
const _CommandDialogs = [];
export const CommandDialog = function (className) {
    _CommandDialogs.push(this);
    this.dialog = mw.element({
        props: {
            className: 'mw-le-dialog-block ' + className
        }
    });
    this.overlay = mw.element({
        props: {
            className: 'mw-le-overlay'
        }
    });
    this.open = function () {
        this.dialog.addClass('active');
        this.overlay.addClass('active');
        this.closeButton.addClass('active');
    };

    this.close = function () {
        this.dialog.removeClass('active');
        this.overlay.removeClass('active');
        this.closeButton.removeClass('active');
    };

    this.remove = function () {
        this.close();
        setTimeout(() => {
            this.dialog.remove();
            this.overlay.remove();
            this.closeButton.remove();
        }, 400);
    };

    this.closeButton = mw.element({
        props: {
            className: 'mw-le-dialog-close'
        }
    });
    this.closeButton.on('click', e => {
        this.remove()
    });

    mw.element(document.body).append(this.overlay);
    mw.element(document.body).append(this.dialog);
    mw.element(document.body).append(this.closeButton);
    setTimeout(() => {
        this.open();
    }, 100);

};

document.addEventListener('keydown', function (e) {
    if ((e.key === 'Escape' || e.keyCode === 27) && _CommandDialogs.length > 0) {
        _CommandDialogs[0].remove();
        _CommandDialogs.splice(0, 1);
    }
});

