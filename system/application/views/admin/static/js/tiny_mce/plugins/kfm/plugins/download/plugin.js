
function plugin_download(){this.name="download";this.title="download file";this.category="main";this.mode=0;this.extensions="all";this.writable=2;this.doFunction=function(files){kfm_downloadSelectedFiles(files[0]);}
this.nocontextmenu=false;}
kfm_addHook(new plugin_download());if(!window.ie){kfm_addHook(new plugin_download(),{mode:1,title:"download selected files",doFunction:function(files){kfm_downloadSelectedFiles();}});}