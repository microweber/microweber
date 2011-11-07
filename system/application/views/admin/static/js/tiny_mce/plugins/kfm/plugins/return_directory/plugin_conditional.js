
function plugin_return_directory(){this.name="return_directory";this.title="Send to CMS";this.category="returning";this.mode=4;this.writable=2;this.doFunction=function(dirs){setTimeout("window.close()",1);window.opener.SetUrl(kfm_directories[dirs[0]].realpath+'/');}
this.nocontextmenu=false;}
kfm_addHook(new plugin_return_directory());