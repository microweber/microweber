tinyMCEPopup.requireLangPack();

var SyntaxHLDialog = {
  init : function() {
  },

  insert : function() {
    var f = document.forms[0], textarea_output, options1 = '';

    //If no code just return.
    if(f.syntaxhl_code.value == '') {
      tinyMCEPopup.close();
      return false;
    }

    if(f.syntaxhl_nogutter.checked) {
      options1 += 'gutter: false; ';
    }
    if(f.syntaxhl_light.checked) {
      options1 += 'light: true; ';
    }
    if(f.syntaxhl_collapse.checked) {
      options1 += 'collapse: true; ';
    }
    if(f.syntaxhl_fontsize.value != '') {
      var fontsize=parseInt(f.syntaxhl_fontsize.value);
      options1 += 'fontsize: ' + fontsize + '; ';
    }

    if(f.syntaxhl_firstline.value != '') {
      var linenumber = parseInt(f.syntaxhl_firstline.value);
      options1 += 'first-line: ' + linenumber + '; ';
    }
    if(f.syntaxhl_highlight.value != '') {
      options1 += 'highlight: [' + f.syntaxhl_highlight.value + ']; ';
    }

f.syntaxhl_code.value = f.syntaxhl_code.value.replace(/</g,'<');
f.syntaxhl_code.value = f.syntaxhl_code.value.replace(/>/g,'>');
textarea_output = '<pre name="code" ';
textarea_output += 'class="' + f.syntaxhl_language.value + options1 + '" cols="50" rows="15">';
textarea_output +=  f.syntaxhl_code.value;
textarea_output += '</pre> '; /* note space at the end, had a bug it was inserting twice? */
tinyMCEPopup.editor.execCommand('mceInsertContent', false, textarea_output);
tinyMCEPopup.close();

  //  textarea_output = '<pre class="brush: ';
//    textarea_output += f.syntaxhl_language.value + ';' + options1 + '">';
//    textarea_output +=  tinyMCEPopup.editor.dom.encode(f.syntaxhl_code.value);
//    textarea_output += '</pre> '; /* note space at the end, had a bug it was inserting twice? */
//    tinyMCEPopup.editor.execCommand('mceInsertContent', false, textarea_output);
//    tinyMCEPopup.close();
  }
};

tinyMCEPopup.onInit.add(SyntaxHLDialog.init, SyntaxHLDialog);