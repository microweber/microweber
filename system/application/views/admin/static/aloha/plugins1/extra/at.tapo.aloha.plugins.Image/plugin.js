/*
    Aloha Image Plugin - Allow image manipulation in Aloha Editor
    Copyright (C) 2010 by TaPo-IT OG (Developed by Herbert Poul)
    http://tapo-it.at

    This program is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/


GENTICS.Aloha.Image = new GENTICS.Aloha.Plugin('at.tapo.aloha.plugins.Image');
GENTICS.Aloha.Image.languages = ['en', 'de'];

GENTICS.Aloha.Image.getImage = function() {
    var range = GENTICS.Aloha.Selection.getRangeObject();
    var rangeTree = range.getRangeTree();
    for (var i = 0 ; i < rangeTree.length ; i++) {
        if (rangeTree[i].type == 'full' && rangeTree[i].domobj.nodeName.toLowerCase() == 'img') {
            return rangeTree[i].domobj;
        }
    }
    return undefined;
}

var TAPO = function(){
    var getImage = GENTICS.Aloha.Image.getImage;
    GENTICS.Aloha.Image.init = function(){
        var buttonleft = new GENTICS.Aloha.ui.Button({
            'iconClass': 'GENTICS_button TAPO_image_align_left',
            'size': 'small',
            'onclick' : function() {
                var image = getImage();
                jQuery(image).css('float', 'left');
            },
            'tooltip': GENTICS.Aloha.i18n(GENTICS.Aloha.Image, 'align.left')
        });
        var buttonnone = new GENTICS.Aloha.ui.Button({
            'iconClass': 'GENTICS_button TAPO_image_align_none',
            'size': 'small',
            'onclick' : function() {
                var image = getImage();
                jQuery(image).css('float', '');
            },
            'tooltip': GENTICS.Aloha.i18n(GENTICS.Aloha.Image, 'align.none')
        });
        var buttonright = new GENTICS.Aloha.ui.Button({
            'iconClass': 'GENTICS_button TAPO_image_align_right',
            'size': 'small',
            'onclick' : function() {
                var image = getImage();
                jQuery(image).css('float', 'right');
            },
            'tooltip': GENTICS.Aloha.i18n(GENTICS.Aloha.Image, 'align.right')
        });
        var buttonborder = new GENTICS.Aloha.ui.Button({
            'iconClass': 'GENTICS_button TAPO_image_border',
            'size': 'small',
            'onclick' : function() {
                var image = getImage();
                if (jQuery(image).css('border')) {
                    jQuery(image).css('border', '');
                } else {
                    jQuery(image).css('border', '2px solid red');
                }
            },
            'toggle': true,
            'tooltip': GENTICS.Aloha.i18n(GENTICS.Aloha.Image, 'border')
        });
        var buttontitle = new GENTICS.Aloha.ui.Button({
            'iconClass': 'GENTICS_button TAPO_image_title',
            'size': 'small',
            'onclick' : function() {
                var image = getImage();
                var title = jQuery(image).attr('title');
                title = prompt(GENTICS.Aloha.i18n(GENTICS.Aloha.Image, 'image.title.prompt'), title);
                jQuery(image).attr('title', title);
                buttontitle.setPressed(title);
            },
            'tooltip': GENTICS.Aloha.i18n(GENTICS.Aloha.Image, 'title'),
            'toggle': true
        });
        GENTICS.Aloha.FloatingMenu.createScope(
            'image'
        );
        GENTICS.Aloha.FloatingMenu.addButton(
            'image',
            buttonleft,
            GENTICS.Aloha.i18n(GENTICS.Aloha.Image, 'floatingmenu.tab.image'),
            1
        );
        GENTICS.Aloha.FloatingMenu.addButton(
            'image',
            buttonright,
            GENTICS.Aloha.i18n(GENTICS.Aloha.Image, 'floatingmenu.tab.image'),
            1
        );
        GENTICS.Aloha.FloatingMenu.addButton(
            'image',
            buttonnone,
            GENTICS.Aloha.i18n(GENTICS.Aloha.Image, 'floatingmenu.tab.image'),
            1
        );
        GENTICS.Aloha.FloatingMenu.addButton(
            'image',
            buttonborder,
            GENTICS.Aloha.i18n(GENTICS.Aloha.Image, 'floatingmenu.tab.image'),
            2
        );
        GENTICS.Aloha.FloatingMenu.addButton(
            'image',
            buttontitle,
            GENTICS.Aloha.i18n(GENTICS.Aloha.Image, 'floatingmenu.tab.image'),
            3
        );
        GENTICS.Aloha.EventRegistry.subscribe(GENTICS.Aloha, 'selectionChanged', function(event, rangeObject) {
            //alert("found: " + found);
            var found = getImage();
            if (found) {
                GENTICS.Aloha.FloatingMenu.setScope('image');//button.show();
                buttonborder.setPressed(jQuery(found).css('border'));
                //debugger;
            } else {
                buttonborder.setPressed(false);
            }
            GENTICS.Aloha.FloatingMenu.doLayout();
        });

        GENTICS.Aloha.EventRegistry.subscribe(GENTICS.Aloha, 'editableCreated', function(event, editable) {
            jQuery(editable.obj).bind('drop', function(event){
                var e = event.originalEvent;
                var files = e.dataTransfer.files;
                var count = files.length;
 
                // if no files where dropped, use default handler
                if (count < 1) {
                    return true;
                }

                for (var i = 0 ; i < files.length ; i++) {
                    //alert("testing " + files[i].name);
                    var reader = new FileReader();
                    reader.onloadend = function(readEvent) {
                        var img = jQuery('<img src="" alt="xyz" />');
                        img.attr('src', readEvent.target.result);
                        //GENTICS.Aloha.Selection.changeMarkupOnSelection(img);
                        GENTICS.Utils.Dom.insertIntoDOM(
                            img,
                            GENTICS.Aloha.Selection.getRangeObject(),
                            jQuery(GENTICS.Aloha.activeEditable.obj));
                    };
                    reader.readAsDataURL(files[i]);
                }

                return false;
            });
        });
    };
}();
