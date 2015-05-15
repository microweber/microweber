mw.iframecallbacks = {
    insert_link: function (url, target) {
        if (!url.contains('mailto:')) {
            var url = url.indexOf("http") === 0 ? url : "http://" + url;
        }
        var target = target || '_self';


        //if(typeof(mw.iframecallbacks.insert_link_selected_node) == null){
        //
        //}

        //mw.wysiwyg.restore_selection();

        var range = window.getSelection().getRangeAt(0);


        mw.wysiwyg.change(range.startContainer);

        if (!!mw.current_element && mw.current_element.nodeName === 'IMG') {
            if (mw.current_element.parentNode.nodeName !== 'A') {
                $(mw.current_element).wrap("<a href='" + url + "' target='" + target + "'></a>");
            }
            else {
                mw.current_element.parentNode.href = url;
                mw.current_element.parentNode.target = target;
            }
        }


        if (range.commonAncestorContainer.nodeName === 'A') {
            $(range.commonAncestorContainer).attr("href", url).attr("target", target);
            return false;
        }


        var start = range.startContainer;


        if (window.getSelection().isCollapsed) {

            if (!!mw.current_element && mw.current_element.nodeName !== 'A') {
                if (mw.tools.hasChildrenWithTag(mw.current_element, 'a')) {
                    $(mw.tools.firstChildWithTag(mw.current_element, 'a')).attr("href", url).attr("target", target);
                    return false;
                }
            } else if (!!mw.current_element && mw.current_element.nodeName === 'A') {
                $(mw.current_element).attr("href", url).attr("target", target);
                return false;
            }

            if (mw.tools.hasParentsWithTag(start, 'a')) {
                $(mw.tools.firstParentWithTag(start, 'a')).attr("href", url).attr("target", target);
                return false;
            }
            if (mw.tools.hasChildrenWithTag(start, 'a')) {
                $(mw.tools.firstChildWithTag(start, 'a')).attr("href", url).attr("target", target);
                return false;
            }

        }


        var link = mw.wysiwyg.findTagAcrossSelection('a');
        if (!!link) {
            $(link).attr("href", url);
            $(link).attr("target", target);
        }
        else {
            if (!window.getSelection().isCollapsed) {
                var a = mwd.createElement('a');
                a.href = url;
                a.target = target;
                var sel = window.getSelection();
                var range = sel.getRangeAt(0);
                try {
                    range.surroundContents(a);
                }
                catch (e) {
                    mw.wysiwyg.execCommand("CreateLink", false, url);
                }
            }
            else {
                var html = " <a href='" + url + "' target='" + target + "'>" + url + "</a> ";
                mw.wysiwyg.insert_html(html);
            }
        }

    },
    insert_html: function (html) {
        return mw.wysiwyg.insert_html(html);
    },
    insert_image: function (url) {
        return mw.wysiwyg.insert_image(url);
    },
    set_bg_image: function (url) {
        return mw.wysiwyg.set_bg_image(url);
    },
    fontColor: function (color) {
        return mw.wysiwyg.fontColor(color);
    },
    fontbg: function (color) {
        return mw.wysiwyg.fontbg(color);
    },
    change_bg_color: function (color) {
        return mw.wysiwyg.change_bg_color(color);
    },
    change_border_color: function (color) {
        return mw.wysiwyg.change_border_color(color);
    },
    change_shadow_color: function (color) {
        return mw.wysiwyg.change_shadow_color(color);
    },
    editimage: function (url) {

        mw.image.currentResizing.attr("src", url);
        mw.image.currentResizing.css('height', 'auto');
        mw.wysiwyg.change(mw.image.currentResizing[0]);
        parent.mw.image.currentResizing.load(function () {
            parent.mw.image.resize.resizerSet(this);
        });

    },
    add_link_to_menu: function () {

    },
    editlink: function (a, b) {
        mw.wysiwyg.restore_selection();
        var link = mw.wysiwyg.findTagAcrossSelection('a');
        link.href = a;

        mw.wysiwyg.change(link);

    }

}







