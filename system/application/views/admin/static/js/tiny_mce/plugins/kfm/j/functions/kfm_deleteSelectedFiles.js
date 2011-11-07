
window.kfm_deleteSelectedFiles=function(){if(!kfm_vars.permissions.file.rm)return kfm.alert(_("permission denied: cannot delete files"));kfm_deleteFiles(selectedFiles);}