
var FileManager = $.Class.create({
	
	init: function(parent, name)
	{
		this.el = $("#filesList");
		this.files = {};
		this.url = FILES_URL;;
		var uploader = new DragUpload("dropZone", 
									{
										url: this.url,
										mousein_class: "dragover",
										onDropFile: this.showFileInList.bind(this),
										onProgress: this.onProgress.bind(this),
										onComplete: this.onLoad.bind(this)
									});
									
		this.initFiles();
	},
	createFile: function(name)
	{
		var file = new FileLine(this.el, name);
		file.onDelete = this.deleteFile.bind(this);
		this.files[name] = file;
		return file;
	},
	initFiles: function()
	{
		this.el.find(".b-file").each(function(i, el){
			var fileName = $(el).find("a").html();
			var file = this.createFile(fileName);
			file.el = $(el);
			file.bind();
			
			
		}.bind(this))
	},
	deleteFile: function(file)
	{
		var name = file.name;
		delete this.files[name];
		file.remove();
		this.sendDelete(name);		
	},
	sendDelete: function(name)
	{
		$.ajax({
			  type: 'POST',
			  url: this.url+"?delete="+name,
			  
			  complete: function(r, textStatus, errorThrown){ 
			 	 
			  }
			});
	},
	showFileInList: function(e, fileName){
			
		var file = this.createFile(fileName);
		file.render();
		
	},
	onProgress: function(e, fileName){ 
		this.files[fileName].showProgress(e.loaded, e.total);
			
	},
	onLoad: function(e, fileName){
		var data = $.parseJSON(e.target.responseText);
		var newFileName = data.file;
		this.files[fileName].finishLoading(newFileName);
		
	}
		
	
	
});
