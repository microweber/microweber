/*
*
*  interface data {
        target: Element,
        component: Element,
        isImage: boolean,
        event: Event
    };
*
*
* */

mw.Editor.interactionControls = {
    image: function (rootScope) {
        this.render = function () {
            var scope = this;
            var el = mw.element({
                props: {
                    className: 'mw-editor-image-handle-wrap'
                }
            });
            var changeButton = mw.element({
                props: {
                    innerHTML: rootScope.lang('Change'),
                    className: 'mw-ui-btn'
                }
            });
            changeButton.$node.on('click', function () {
                mw.top().fileWindow({
                    type: 'images',
                    change: function (url) {
                        scope.$target.attr('src', url);
                    }
                });
            });
            var editButton = mw.element({
                props: {
                    innerHTML: '<i class="mdi mdi-image-edit"></i>',
                    className: 'mw-ui-btn tip',
                    dataset: {
                        tip: rootScope.lang('Edit image')
                    }
                }
            });
            el.append(changeButton);
            el.append(editButton);
            return el;
        };
        this.interact = function (data) {
            if (data.isImage) {
                var $target = $(data.target);
                this.$target = $target;
                var css = $target.offset();
                css.width = $target.outerWidth();
                css.height = $target.outerHeight();
                this.element.$node.css(css).show();
            } else {
                this.element.$node.hide();
            }
        };
        this.element = this.render();
    }
};
