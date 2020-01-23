(function(expose){
   var fullscreen = {
    fullscreen: function (el) {
        if (el.requestFullScreen) {
            el.requestFullScreen();
        }
        else if (el.webkitRequestFullScreen) {
            el.webkitRequestFullScreen();
        }
        else if (el.mozRequestFullScreen) {
            el.mozRequestFullScreen();
        }
        else if (el.msRequestFullscreen) {
            el.msRequestFullscreen();
        }
    },
    isFullscreenAvailable: function () {
        var b = mwd.body;
        return 'requestFullScreen' in b || 'webkitRequestFullScreen' in b || 'mozRequestFullScreen' in b || 'msRequestFullscreen' in b || false;
    },
    cancelFullscreen: function () {
        if (mwd.exitFullscreen) {
            mwd.exitFullscreen();
        }
        else if (mwd.mozCancelFullScreen) {
            mwd.mozCancelFullScreen();
        }
        else if (mwd.webkitExitFullscreen) {
            mwd.webkitExitFullscreen();
        }
        else if (mwd.msExitFullscreen) {
            mwd.msExitFullscreen();
        }
    },
    toggleFullscreen: function (el) {
        var infullscreen = mwd.fullScreen || mwd.webkitIsFullScreen || mwd.mozFullScreen || false;
        if (infullscreen) {
            mw.tools.cancelFullscreen();
        }
        else {
            mw.tools.fullscreen(el)
        }
    },
    fullscreenChange: function (c) {
        if ('addEventListener' in document) {
            document.addEventListener("fullscreenchange", function () {
                c.call(document.fullscreen);
            }, false);
            document.addEventListener("mozfullscreenchange", function () {
                c.call(document.mozFullScreen);
            }, false);
            document.addEventListener("webkitfullscreenchange", function () {
                c.call(document.webkitIsFullScreen);
            }, false);
        }
    }
   };
    Object.assign(expose, fullscreen);

})(mw.tools);
