// used on editmode and in admin

var css = document.createElement("link");
css.rel = "stylesheet";
css.type = "text/css";
css.href = "<? print ADMIN_STATIC_FILES_URL ?>css/api.css";

document.getElementsByTagName("head")[0].appendChild(css);

function isEmpty(obj) {
    for(var prop in obj) {
        if(obj.hasOwnProperty(prop))
            return false;
    }

    return true;
}
$.fn.insertAtCaret = function (myValue) {
	  return this.each(function(){
	  //IE support
	  if (document.selection) {
	    this.focus();
	    sel = document.selection.createRange();
	    sel.text = myValue;
	    this.focus();
	  }
	  //MOZILLA / NETSCAPE support
	  else if (this.selectionStart || this.selectionStart == '0') {
	    var startPos = this.selectionStart;
	    var endPos = this.selectionEnd;
	    var scrollTop = this.scrollTop;
	    this.value = this.value.substring(0, startPos)+ myValue+ this.value.substring(endPos,this.value.length);
	    this.focus();
	    this.selectionStart = startPos + myValue.length;
	    this.selectionEnd = startPos + myValue.length;
	    this.scrollTop = scrollTop;
	  } else {
	    this.value += myValue;
	    this.focus();
	  }
	  });
	};