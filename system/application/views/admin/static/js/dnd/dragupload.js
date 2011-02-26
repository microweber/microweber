var DragUpload = $.Class.create({
	
	onProgress: function(e, fileName){},
	onComplete: function(e, fileName){},
	onDropFile: function(e, fileName){},
	url: "",
	mousein_class: "dragover",
	
	init: function(id, options)
	{
		this._el = document.getElementById(id);
		this.el = $(this._el);
		this.el.bind("dragover", this._over.bind(this))
			   .bind("dragenter", function(){return false;})
			   .bind('dragleave', this._leave.bind(this))
			   .bind("drop", this._drop.bind(this))
		
		
		this.blockDocumentDrop();
		
		
		$.extend(this, options);
		 
	},
	blockDocumentDrop: function()
	{
		$(document)
		    .bind('dragenter', function(e) {return false;})
		    .bind('dragleave', function(e) {return false;})
		    .bind('dragover', function(e) {
		        var dt = e.originalEvent.dataTransfer;
		        if (!dt) { return; }
		        dt.dropEffect = 'none';
		        return false;
		    }.bind(this));
	},
	hideHighlight: function()
	{
		this.el.removeClass(this.mousein_class);
		return;
	},
	_leave:function(e)
	{
		return this.hideHighlight();
	},
	_over: function(e)
	{
		 var dt = e.originalEvent.dataTransfer;
		 if(!dt) return;
		 //FF
		 if(dt.types.contains&&!dt.types.contains("Files")) return;
		 //Chrome
		 if(dt.types.indexOf&&dt.types.indexOf("Files")==-1) return;
		 
		 if($.browser.webkit) dt.dropEffect = 'copy';
		 
		 this.el.addClass(this.mousein_class);	

		 return false;
	},
	_drop:function(e)
	{
		var dt = e.originalEvent.dataTransfer;
		if(!dt&&!dt.files) return;

		this.hideHighlight();
		
		var files = dt.files;
		 for (var i = 0; i < files.length; i++) {
            var file = files[i];
			this.onDropFile(e, file.fileName);
			this.upload(file);
		 }
		return false;
	},
	
	
	upload: function(file)
	{
		var xhr = new XMLHttpRequest();
		
		xhr.upload.addEventListener("progress", function(e){this.onProgress(e, file.fileName)}.bind(this), false);
		
		xhr.onload = function(e){this.onComplete(e, file.fileName)}.bind(this);
		
		xhr.open('POST', this.url/*+"?file="+file.fileName*/, true);
		xhr.send(file);
		
	}
	
	
});
