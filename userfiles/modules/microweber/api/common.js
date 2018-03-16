$(document).ready(function(){
    var skin = 'basic';
    var overlay = true;
    $(mwd.body).on('click', '[data-mw-dialog]', function(e){
        var data = this.getAttribute('data-mw-dialog');
        if(data){
            e.preventDefault();
            data = data.trim();
            var arr = data.split('.');
            var ext = arr[arr.length-1];
            if(data.indexOf('http') === 0){
                if(ext && /(gif|png|jpg|jpeg|bpm|tiff)$/i.test(ext)){
                    mw.image.preload(data, function(w,h){
                        var html = "<img src='"+data+"'>";
                        mw.modal({
                            width:w,
                            height:h,
                            content:html,
                            template:skin,
                            overlay:overlay
                        });
                    });
                }
                else{
                    mw.modalFrame({
                        url:data,
                        width:'90%',
                        height:'90%',
                        template:skin,
                        overlay:overlay
                    })
                }
            }
            else if(data.indexOf('#') === 0 || data.indexOf('.') === 0){
                mw.modal({
                    content:$(data)[0].outerHTML,
                    template:skin,
                    overlay:overlay
                });
            }
        }
    });
})