mw.tools.createAutoHeight = function() {
    if(window.thismodal && thismodal.iframe) {
        mw.tools.iframeAutoHeight(thismodal.iframe);
    }
    else if(window.top.frameElement && window.top.frameElement.contentWindow === window) {
        mw.tools.iframeAutoHeight(window.top.frameElement);
    } else if(window.top !== window) {
        top.mw.$('iframe').each(function(){
            try{
                if(this.contentWindow === window) {
                    mw.tools.iframeAutoHeight(this);
                }
            } catch(e){}
        })
    }
};

mw.tools.iframeAutoHeight = function(frame, opt){

    frame = mw.$(frame)[0];
    if(!frame) return;

    var _detector = document.createElement('div');
    _detector.className = 'mw-iframe-auto-height-detector';
    _detector.id = mw.id();

    var insertDetector = function() {
        if(frame.contentWindow && frame.contentWindow.document && frame.contentWindow.document.body){
            var det = frame.contentWindow.document.querySelector('.mw-iframe-auto-height-detector');
            if(!det){
                frame.contentWindow.document.body.appendChild(_detector);
            } else if(det !== frame.contentWindow.document.body.lastChild){
                frame.contentWindow.document.body.appendChild(det);
            }
            if(frame.contentWindow.mw) {
                frame.contentWindow.mw._iframeDetector = _detector;
            }

        }
    };



    setTimeout(function(){
        insertDetector();
    }, 100);
    frame.scrolling="no";
    frame.style.minHeight = 0 + 'px';
    mw.$(frame).on('load resize', function(){

        if(!mw.tools.canAccessIFrame(frame)) {
            console.log('Iframe can not be accessed.', frame);
            return;
        }
        if(!frame.contentWindow.document.body){
            return;
        }
        if(!!frame.contentWindow.document.querySelector('.mw-iframe-auto-height-detector')){
            return;
        }

        insertDetector();
    });
    frame._intPause = false;
    frame._int = setInterval(function(){
        if(!frame._intPause && frame.parentNode && frame.contentWindow  && frame.contentWindow.$){
            var offTop = frame.contentWindow.$(_detector).offset().top;
            var calc = offTop + _detector.offsetHeight;
            //calc = Math.max(calc, mw.tools.nestedFramesHeight(frame));
            frame._currHeight = frame._currHeight || 0;
            if(calc && calc !== frame._currHeight ){

                frame._currHeight = calc;
                frame.style.height = calc + 'px';
                mw.$(frame).trigger('bodyResize');
            }
        }
        else {
            //clearInterval(frame._int);
        }
    }, 77);

};
