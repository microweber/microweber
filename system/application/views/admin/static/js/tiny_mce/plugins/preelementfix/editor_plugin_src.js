/**
 * PreElementFix plugin
 * Version: 0.1
 * Author: tan@enonic.com
 */
(function() {
  tinymce.create('tinymce.plugins.PreElementFix', {
    init : function(ed, url) {
      var t = this;
      // MSIE nad WebKit inserts a new PRE tag each time the user hits enter.
      // Gecko and Opera inserts a br element. This will make sure that IE and WebKit has the same behaviour as Fx and Opera.
      if (tinymce.isIE || tinymce.isWebKit) {
        ed.onKeyDown.add(function(ed, e) {
          var n, s = ed.selection;
          if (e.keyCode == 13 && s.getNode().nodeName == 'PRE') {
            var br = '<br id="__preElementFix" /> ';
            s.setContent(br, {format : 'raw'});
            n = ed.dom.get('__preElementFix');
            n.removeAttribute('id');
            s.select(n);
            s.collapse();
            return tinymce.dom.Event.cancel(e);
          }
        });
      }
  
      // Inserts a tab in Gecko when the user hits the tab key.
      if (tinymce.isGecko) {
        ed.onKeyDown.add(function(ed, e) {
          var n, s = ed.selection;
          if (e.keyCode == 9 && s.getNode().nodeName == 'PRE') {
            s.setContent('\t', {format : 'raw'});
            return tinymce.dom.Event.cancel(e);
          }
        });
      }

      ed.onBeforeGetContent.add(function(ed, o) {
        t._replaceBrElementsWithNewlines(ed);
        t._removeSpanElementsInPreElementsForWebKit(ed);
      });
    },

    getInfo : function() {
      return {
        longname : 'Pre Element Fix',
        author : 'tan@enonic.com',
        authorurl : 'http://www.enonic.com',
        infourl : 'http://www.enonic.com',
        version : "1.0"
      };
  		},

    _replaceBrElementsWithNewlines : function(ed) {
      var brElements = ed.dom.select('pre br');
      for (var i = 0; i < brElements.length; i++) {
        var nlChar;
        if (tinymce.isIE)
          nlChar = '\r';
        else
          nlChar = '\n';

        var nl = ed.getDoc().createTextNode(nlChar);
        ed.dom.insertAfter(nl, brElements[i]);
        ed.dom.remove(brElements[i]);
      }
    },

    _removeSpanElementsInPreElementsForWebKit : function(ed) {
      // WebKit inserts a span element each time the users hits the tab key.
      // This removes the element.
      if (tinymce.isWebKit) {
        var spanElements = ed.dom.select('pre span');
        for (var i = 0; i < spanElements.length; i++) {
          var space = ed.getDoc().createTextNode(spanElements[i].innerHTML);
          ed.dom.insertAfter(space, spanElements[i]);
          ed.dom.remove(spanElements[i]);
        }
      }
    },
  });

  tinymce.PluginManager.add('preelementfix', tinymce.plugins.PreElementFix);
})();