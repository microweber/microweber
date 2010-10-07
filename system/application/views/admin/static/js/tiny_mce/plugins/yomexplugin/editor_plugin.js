/*

    Div Plugin - by Peter Wilson (petew@yellowhawk.co.uk)

    Adds a button that will 'wrap' a selection in an outer 'div' element to which you can
      then apply a style (eg a border).

      At the moment this also inserts an paragraph after the new <div to avoid the problem
      of not being able to get out of the new div if it's the last thing in your document. Not
      ideal - but works :-)

      Code pulled together by hacking away at the blockquote code in the core TinyMCE
      software without a huge amount of knowledge so could very possibly be improved!

*/


(function() {

    tinymce.create("tinymce.plugins.yomex", {

        init : function(editor, url) {

            editor.addCommand("mcewrapdiv", function() {
        var ed = this, s = ed.selection, dom = ed.dom, sb, eb, n, div, bm, r, i;

        // Get start/end block
        sb = dom.getParent(s.getStart(), dom.isBlock);
        eb = dom.getParent(s.getEnd(), dom.isBlock);

        // If the document is empty then there can't be anything to wrap.
        if (!sb && !eb) {
          return;
        }

        // If empty paragraph node then do not use bookmark
        if (sb != eb || sb.childNodes.length > 1 || (sb.childNodes.length == 1 && sb.firstChild.nodeName != 'BR'))
          bm = s.getBookmark();

        // Move selected block elements into a new DIV - positioned before the first block
        tinymce.each(s.getSelectedBlocks(s.getStart(), s.getEnd()), function(e) {

          // If this is the first node then we need to create the DIV along with the following dummy paragraph.
          if (!div) {
            div = dom.create('div');
            div.className = 'Yomex';
            e.parentNode.insertBefore(div, e);

            // Insert an empty dummy paragraph to prevent people getting stuck in a nested block. The dummy has a '-'
            // in it to prevent it being removed as an empty paragraph.

          }

          // Move this node to the new DIV
          if (div!=null)
            div.appendChild(dom.remove(e));
        });

        if (!bm) {
          // Move caret inside empty block element
          if (!tinymce.isIE) {
            r = ed.getDoc().createRange();
            r.setStart(sb, 0);
            r.setEnd(sb, 0);
            s.setRng(r);
          } else {
            s.select(sb);
            s.collapse(1);
          }
        } else
          s.moveToBookmark(bm);
            });

            editor.addButton("wrapdiv", {
                title: "Добави Блок",
                image: url+"/ab.png",
                cmd: "mcewrapdiv"
            });


        }

    });

    tinymce.PluginManager.add("yomexplugin", tinymce.plugins.yomex);

})();