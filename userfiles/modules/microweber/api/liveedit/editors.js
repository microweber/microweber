mw.liveedit.editors = {
  prepare: function() {
      mw.$(window).on("mouseup touchend", function(e) {

          var sel = getSelection();
          if (sel.rangeCount > 0) {
              var range = sel.getRangeAt(0),
                  common = mw.wysiwyg.validateCommonAncestorContainer(range.commonAncestorContainer);

              if (mw.tools.hasClass(common, 'edit') || mw.tools.hasParentsWithClass(common, 'edit')) {
                  var nodrop_state = !mw.tools.hasClass(common, 'nodrop') && !mw.tools.hasParentsWithClass(common, 'nodrop');
                  if (nodrop_state) {
                      mw.wysiwyg.enableEditors();
                  } else {
                      mw.wysiwyg.disableEditors();
                  }
              } else {
                  mw.wysiwyg.disableEditors();
              }
          }

          sel = window.getSelection();
          if (sel.rangeCount > 0) {
              var r = sel.getRangeAt(0);
              var cac = mw.wysiwyg.validateCommonAncestorContainer(r.commonAncestorContainer);
          }
          if (mw.tools.hasAnyOfClassesOnNodeOrParent(cac, ['edit', 'mw-admin-editor-area']) && (sel.rangeCount > 0 && !sel.getRangeAt(0).collapsed)) {

              if ($.contains(e.target, cac) || $.contains(cac, e.target) || cac === e.target) {
                  setTimeout(function() {
                      var ep = mw.event.page(e);
                      if (cac.isContentEditable && !sel.isCollapsed && !mw.tools.hasClass(cac, 'plain-text') && !mw.tools.hasClass(cac, 'safe-element')) {
                          if (typeof(window.getSelection().getRangeAt(0).getClientRects()[0]) == 'undefined') {
                              return;
                          }
                          mw.smallEditorCanceled = false;
                          var top = ep.y - mw.smallEditor.height() - window.getSelection().getRangeAt(0).getClientRects()[0].height;
                          mw.smallEditor.css({
                              visibility: "visible",
                              opacity: 0.7,
                              top: (top > 55 ? top : 55),
                              left: ep.x + mw.smallEditor.width() < mw.$(window).width() ? ep.x : ($(window).width() - mw.smallEditor.width() - 5)
                          });

                      } else {
                          mw.smallEditorCanceled = true;
                          mw.smallEditor.css({
                              visibility: "hidden"
                          });
                      }

                  }, 33);
              }
          } else {
              if (mw.smallEditor && !mw.tools.hasParentsWithClass(e.target, 'mw_small_editor')) {
                      mw.smallEditorCanceled = true;
                      mw.smallEditor.css({
                          visibility: "hidden"
                      });
              }
          }
          setTimeout(function() {
              if (window.getSelection().rangecount > 0 && window.getSelection().getRangeAt(0).collapsed) {
                  if (typeof(mw.smallEditor) != 'undefined') {
                      mw.smallEditorCanceled = true;
                      mw.smallEditor.css({
                          visibility: "hidden"
                      });
                  }
              }
          }, 39);
      });
      mw.smallEditorOff = 150;

      mw.$(window).on("mousemove touchmove touchstart", function(e) {
          if (!!mw.smallEditor && !mw.isDrag && !mw.smallEditorCanceled && !mw.smallEditor.hasClass("editor_hover")) {
              var off = mw.smallEditor.offset();
              var ep = mw.event.page(e);
              if (typeof off !== 'undefined') {
                  if (
                      ((ep.x - mw.smallEditorOff) > (off.left + mw.smallEditor.width()))
                      || ((ep.y - mw.smallEditorOff) > (off.top + mw.smallEditor.height()))
                      || ((ep.x + mw.smallEditorOff) < (off.left)) || ((ep.y + mw.smallEditorOff) < (off.top))) {
                      if (typeof mw.smallEditor !== 'undefined') {
                          mw.smallEditor.css("visibility", "hidden");
                          mw.smallEditorCanceled = true;
                      }
                  }
              }
          }
      });
      mw.$(window).on("scroll", function(e) {
          if (typeof(mw.smallEditor) !== "undefined") {
              mw.smallEditor.css("visibility", "hidden");
              mw.smallEditorCanceled = true;
          }
      });
      mw.$("#live_edit_toolbar, #mw_small_editor").on("mousedown touchstart", function(e) {
          e.preventDefault();
          mw.$(".wysiwyg_external").empty();
          if (e.target.nodeName !== 'INPUT' && e.target.nodeName !== 'SELECT' && e.target.nodeName !== 'OPTION' && e.target.nodeName !== 'CHECKBOX') {
              e.preventDefault();
          }
          if (typeof(mw.smallEditor) !== "undefined") {
              if (!mw.tools.hasParentsWithClass(e.target, 'mw_small_editor')) {
                  mw.smallEditor.css("visibility", "hidden");
                  mw.smallEditorCanceled = true;
              }
          }
      });
  }
};
