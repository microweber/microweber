
function plugin_rename(){this.name="rename";this.title=kfm.lang["rename file"];this.category="edit";this.mode=0;this.extensions="all";this.writable=1;this.doFunction=function(files){kfm_renameFile(files[0]);}
this.nocontextmenu=false;}
if(kfm_vars.permissions.file.ed)kfm_addHook(new plugin_rename());