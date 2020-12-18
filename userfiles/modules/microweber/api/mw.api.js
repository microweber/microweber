mw.app = {
    deleteContent:function(id){
        return $.post(mw.settings.site_url + "api/content/delete", {id: id});
    }
}

mw.api = {
    deleteContent:function(id){
        mw.tools.confirm(mw.msg.del, function () {
            var data = mw.app.deleteContent(id);
            data
                .done(function(){
                    mw.notification.success('Content deleted');
                })
                .fail(function(){
                    mw.notification.error('Error deleting');
                })
        });
    }
}