function setCaretPosition(ctrl, pos){
    if (pos == -1) 
        return;
    var range = document.createRange();
    
    pos = ctrl.childNodes.length;
    range.setStart(ctrl, 0);
    range.setEnd(ctrl, pos);
    var selection = window.getSelection();
    selection.removeAllRanges();
    selection.addRange(range);
    
}

var FileLine = $.Class.create({
	template: $.template("<div class='b-file'>"+
							"<div class='link'><a>${name}</a></div>"+
							"<div contenteditable='true' class='url'></div>"+
							"<div class='loader'><div></div></div>"+
							"<i class='del'></i>"+
						 "</div>"),
	
	onDelete: function(file){},
	
	init: function(parent, name)
	{
		this.parent = parent;
		this.name = name;
	},
	render: function()
	{
		var html = this.template.apply({name:this.name});
		this.parent.prepend(html);
		
		this.bind();
	},
	bind: function()
	{
		if(!this.el)
		this.el = $(this.parent.children()[0]);
		this.txtUrl = this.el.find(".url");
		this.txtUrl.focus(function(e){
			setCaretPosition(this, this.innerHTML.length - 1);
			document.execCommand('copy', null, null);
		});
		this.loaderEl = this.el.find(".loader");
		this.progressEl = this.el.find(".loader div");
		this.linkEl = this.el.find("a");
		this.el.find(".del").click(this.onDelete.bind(this, this));
	},
	showProgress: function(loaded, total)
	{
		this.progressEl.css({width: Math.round(loaded/total*100)+"%"});
	},
	finishLoading: function(newName)
	{
		var url = FILES_URL+newName;
		this.linkEl.attr("href", url).html(newName);
		this.loaderEl.hide();
				
		this.txtUrl.html(url);
	},
	remove: function()
	{
		this.el.fadeOut(500, function() {
			this.el.remove();
  		}.bind(this));
		
	}
	
});
