
function plugin_extract_zip(){this.name='extract_zip';this.title=kfm.lang.ExtractZippedFile;this.mode=0;this.writable=2;this.extensions=['zip'];this.doFunction=function(files){fid=files[0];kfm_extractZippedFile(fid);}}
if(kfm_vars.permissions.file.mk)kfm_addHook(new plugin_extract_zip());