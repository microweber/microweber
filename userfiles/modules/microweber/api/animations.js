;(function (){




    mw.__pageAnimations = mw.__pageAnimations || [];



    var prefix = 'animate__';
    var suffix = 'animated';
    var __initialHiddenClass = 'mw-anime--InitialHidden';

    var stop = function(target){
        if(!target) {
            return;
        }

        Array.from( target.classList )
            .filter(function (cls){
                return cls.indexOf(prefix) === 0;
            })
            .forEach(function (cls){
                target.classList.remove(cls)
            })
    };
    var animateCSS = function(options){

        if(!options || !options.animation || options.animation === 'none') {
            return;
        }

        var selector = options.selector,
            removeAtEnd = options.animation,
            animation = options.animation,
            speed = options.speed;
        var cb = options.callback;
        if(typeof speed === 'number') {
            speed = speed + 's'
        }


        var animationName = prefix + animation;
        var node = selector;
        if(typeof selector === 'string') {
            node = document.querySelector(selector);
        }
        if(!node) {
            return;
        }
        node.classList.remove(__initialHiddenClass)
        if (speed) {
            node.style.setProperty('--animate-duration', speed);
        }
        var isInline = getComputedStyle(node).display === 'inline';

        if(isInline) {
            node.style.display = 'inline-block';
            var ms = parseFloat(speed) * 1000;
            setTimeout(function (){
                node.style.display = '';
            }, ms+10)
        }
        node.classList.add(prefix + suffix, animationName);
        function handleAnimationEnd(event) {
            event.stopPropagation();
            node.classList.remove(prefix + suffix, animationName);
            if (cb) {
                cb.call();
            }
        }
        node.addEventListener('animationend', handleAnimationEnd, { once: true });
    };

    mw.__animate = animateCSS;

    var __animationTypes = {
        onAppear: function (data) {
            if ('IntersectionObserver' in window) {
                var filter = function (item) {
                    return item.when === 'onAppear';
                }
                var nodes = [];
                ;(data || []).filter(filter).forEach(function (item) {
                    var node = document.querySelector(item.selector);
                    if(node) {
                        if(!node.$$mwAnimations) {
                            node.$$mwAnimations = [];
                        }
                        var has = node.$$mwAnimations.find(filter);
                        if (!has) {
                            node.$$mwAnimations.push(item);
                            nodes.push(node);
                        }
                    }

                });

                if (!mw.settings.liveEdit && nodes.length) {
                    var observer = new IntersectionObserver(function(entries, observer) {
                        entries.forEach(function(el) {
                            if(!el.target.$$mwAnimationDone && el.isIntersecting) {
                                el.target.$$mwAnimationDone = true;
                                animateCSS(el.target.$$mwAnimations.find(filter));
                            }
                        });
                    });
                    nodes.forEach(function(el) {
                        observer.observe(el);
                    });
                }
            }
        },
        onHover: function (data) {
            var filter = function (item) {
                    return item.when === 'onHover';
                }
            ;(data || []).filter(filter).forEach(function (item){
                var node = document.querySelector(item.selector);
                if(node) {
                    if (!node.$$mwAnimations) {
                        node.$$mwAnimations = [];
                    }
                    var has = node.$$mwAnimations.find(filter);
                    if (  !has) {
                        node.$$mwAnimations.push(item);
                        if(!mw.settings.liveEdit) {
                            node.addEventListener('mouseenter', function (){
                                animateCSS(this.$$mwAnimations.find(filter))
                            })
                        }
                    }
                }

            });
        },
        onClick: function (data) {
            var filter = function (item) {
                    return item.when === 'onClick';
                }
            ;(data || []).filter(filter).forEach(function (item){
                var node = document.querySelector(item.selector);
                if(node) {
                    if (!node.$$mwAnimations) {
                        node.$$mwAnimations = [];
                    }
                    var has = node.$$mwAnimations.find(filter);
                    if (!has) {
                        node.$$mwAnimations.push(item)
                        if(!mw.settings.liveEdit) {
                            node.addEventListener('click', function (){
                                animateCSS(this.$$mwAnimations.find(filter))
                            });
                        }

                    }
                }

            });
        }
    }


    var _animateInit = false;
    window.animateInit = function (data) {

        if(!_animateInit) {
            _animateInit = true;
            var style = document.createElement('style');
            style.innerHTML = '.' + __initialHiddenClass + '{ opacity:0; pointer-events: none; }';
            document.getElementsByTagName('head')[0].appendChild(style);
        }

        data.forEach(function (item) {
            if(item.hidden) {
                var node = document.querySelector(item.selector);
                if (node) {
                    node.classList.add(__initialHiddenClass)
                }
            }
        });
        for (let i in __animationTypes) {
            if (__animationTypes.hasOwnProperty(i)){
                __animationTypes[i](data);
            }
        }
    };

    addEventListener('DOMContentLoaded', function (){
        animateInit(mw.__pageAnimations);
    })
    addEventListener('load', function (){
        animateInit(mw.__pageAnimations);
    });


})();
