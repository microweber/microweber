mw.iframecallbacks = {
    noop: function() {

    },
    insert_link: function (url, target, link_content) {
        if(url.callee){
            target = url[1];
            link_content = url[2];
            url = url[0];
        }
        url = url.trim();
        var contains = false;
        var arr = ['mailto:', 'tel:', 'skype:', 'sms:', 'geopoint:', 'whatsapp:'],
            i = 0;
        for( ; i < arr.length; i++ ){
            if(url.indexOf(arr[i]) === 0){
                contains = true;
            }
        }
        if (!contains && !!url) {
            //url = url.indexOf("http") === 0 ? url : (location.protocol + "//" + url);
        }
        target = target || '_self';

        var link_inner_text = false;
        if(link_content){
            link_inner_text = link_content;
        }

        var sel, range, a;

        sel = getSelection();
        if(!sel.rangeCount){
            return;
        }

        range = sel.getRangeAt(0);
        var jqAction = url ? 'attr' : 'removeAttr';

        mw.wysiwyg.change(range.startContainer);

        if (!!mw.current_element && mw.current_element.nodeName === 'IMG') {
            if (mw.current_element.parentNode.nodeName !== 'A') {
                a = mwd.createElement('a');
                if(url){
                    a.href = url;
                }
                a.target = target;

                mw.$(mw.current_element).wrap(a);
            }
            else {
                mw.$(mw.current_element.parentNode)[jqAction]('href', url);

                mw.current_element.parentNode.target = target;
            }
        }


        if (range.commonAncestorContainer.nodeName === 'A') {
            mw.$(range.commonAncestorContainer)[jqAction]("href", url).attr("target", target);
            if(link_inner_text){
                mw.$(range.commonAncestorContainer).html(link_inner_text);
            }
            return false;
        }


        var start = range.startContainer;


        if (window.getSelection().isCollapsed) {

            if (!!mw.current_element && mw.current_element.nodeName !== 'A') {
                if (mw.tools.hasChildrenWithTag(mw.current_element, 'a')) {
                    mw.$(mw.tools.firstChildWithTag(mw.current_element, 'a'))[jqAction]("href", url).attr("target", target);
                    if(link_inner_text){
                        mw.$(mw.tools.firstChildWithTag(mw.current_element, 'a')).html(link_inner_text);
                    }
                    return false;
                }
            } else if (!!mw.current_element && mw.current_element.nodeName === 'A') {
                mw.$(mw.current_element).attr("href", url).attr("target", target);
                if(link_inner_text){
                    mw.$(mw.current_element).html(link_inner_text);
                }
                return false;
            }

            if (mw.tools.hasParentsWithTag(start, 'a')) {
                mw.$(mw.tools.firstParentWithTag(start, 'a'))[jqAction]("href", url).attr("target", target);
                if(link_inner_text){
                    mw.$(mw.tools.firstParentWithTag(start, 'a')).html(link_inner_text);
                }
                return false;
            }
            if (mw.tools.hasChildrenWithTag(start, 'a')) {
                mw.$(mw.tools.firstChildWithTag(start, 'a'))[jqAction]("href", url).attr("target", target);
                if(link_inner_text){
                    mw.$(mw.tools.firstChildWithTag(start, 'a')).html(link_inner_text);
                }
                return false;
            }

        }


        var link = mw.wysiwyg.findTagAcrossSelection('a');
        if (!!link) {
            mw.$(link)[jqAction]("href", url);
            mw.$(link).attr("target", target);
            if(link_inner_text){
                mw.$(link).html(link_inner_text);
            }
        }
        else {
            if (!window.getSelection().isCollapsed) {
                a = mwd.createElement('a');
                a.href = url;
                a.target = target;
                sel = window.getSelection();
                range = sel.getRangeAt(0);
                try {
                    range.surroundContents(a);
                }
                catch (e) {
                    mw.wysiwyg.execCommand("CreateLink", false, url);
                }
            }
            else {

                var html = '<a href="' + url + '" target="' + target + '">' + (link_inner_text ? link_inner_text : url) + '</a>';
                mw.wysiwyg.insert_html(html);
            }
        }
        if(link_content && a) {
            a.innerHTML = link_content
        }
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

    add_link_to_menu: function () {

    },
    editlink: function (a, b) {
        mw.wysiwyg.restore_selection();
        var link = mw.wysiwyg.findTagAcrossSelection('a');
        link.href = a;

        mw.wysiwyg.change(link);

    }

}







