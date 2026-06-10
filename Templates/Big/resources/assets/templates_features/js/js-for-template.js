//Add class for navigation when scroll

$(function () {
    $(document).scroll(function () {
        var $nav = $(".main .navigation-holder.not-transparent");
        $nav.toggleClass('scrolled', $(this).scrollTop() > $nav.height());
    });
});

// checking for images and add class

var checkFirstSectionForNav = function () {
    var navIfHasBackground = $(".main .navigation-holder.not-transparent");
    var skip = $('.main-content').find('section').first().hasClass('mw-layout-overlay-wrapper');

    if (skip) {
        $(navIfHasBackground).removeClass('mw-nav-menu-not-transparent');
        $(navIfHasBackground).addClass('mw-nav-menu-transparent-with-image');

    } else {
        $(navIfHasBackground).removeClass('mw-nav-menu-transparent-with-image');
        $(navIfHasBackground).addClass('mw-nav-menu-not-transparent');
    }
};

$(window).on('load', function () {
    checkFirstSectionForNav();

    $(window).on('moduleLoaded', function () {
        checkFirstSectionForNav();
    });

});

//header menus js

$(window).on('load', function () {
    $(".navT-header-menu").on("click", function(){
        $(this).toggleClass("active-menu");
        $("#header-menu-skin-1").toggleClass("open");
        $(".content").toggleClass("shift");

    });

    $('button.menu-toggle').on('click', function(){
        $('body').toggleClass('open-header-menu');
    });

    $("button.header-menu-hamburger").click(function() {
        $(".header-menu-hamburger").toggleClass("header-menu-focus");
        $(".header-menu-content").toggleClass("header-menu-show");
    });

    var menuBtn = document.querySelector('.header-menu-btn');
    var nav = document.querySelector('nav');
    var lineOne = document.querySelector('nav .header-menu-btn .line--1');
    var lineTwo = document.querySelector('nav .header-menu-btn .line--2');
    var lineThree = document.querySelector('nav .header-menu-btn .line--3');
    var link = document.querySelector('nav .nav-links');
    if (menuBtn) {
        menuBtn.addEventListener('click', function () {
            nav.classList.toggle('nav-open');
            lineOne.classList.toggle('line-cross');
            lineTwo.classList.toggle('line-fade-out');
            lineThree.classList.toggle('line-cross');
            link.classList.toggle('fade-in');
        });
    }

});

$(document).ready(function(){
    var menu = $(".header-menu");
    var hamburger = $(".menu-header-hamburger");
    var line = $(".line");
    var menuOpen;

    function openMenu(){
        menu.css("left", "0px");
        // line.css("background", "#FFF");
        menuOpen = true;
    }

    function closeMenu(){
        menu.css("left", "-320px");
        // line.css("background", "#BCAD90");
        menuOpen = false;
    }

    function toggleMenu(){
        if (menuOpen) {
            closeMenu();
        } else {
            openMenu();
        }
    }

    hamburger.on({
        mouseenter: function(){
            openMenu();
        }
    });

    menu.on({
        mouseleave: function(){
            closeMenu();
        }

    });

    hamburger.on({
        click: function(){
            toggleMenu();
        }
    });
});

$(document).ready(function(){
    var headerMenuSkinBody = document.querySelector('.header-menu-nav-body'),
        headerMenuSkiNav = document.querySelector('.nav'),
        headerMenuSkiNavBar = document.querySelector('.nav--bar'),
        headerMenuSkinNavToggle = document.querySelector('.nav--toggle'),
        navMenuIsOpen = false,
        navMenu = document.querySelector('.nav--menu'),
        navMenuBG = document.querySelector('.nav--menu-bg');

    // TODO: scroll debounce
    var windowHeight = window.innerHeight;
    var navBarHeight = !!headerMenuSkiNavBar ? headerMenuSkiNavBar.offsetHeight : 0;
    var windowNavOffset = windowHeight - navBarHeight;

    // nav
    function showNav() {
        navMenuIsOpen = true;

        if (headerMenuSkinBody) {
            headerMenuSkinBody.classList.add('nav--is-visible');
        }
        if (headerMenuSkinNavToggle) {
            headerMenuSkinNavToggle.classList.add('header-menu-skin-active');
        }
        if (navMenu) {
            navMenu.classList.add('header-menu-skin-active');
        }
        if (navMenuBG) {
            navMenuBG.classList.add('header-menu-skin-active');
        }

    }

    function hideNav() {
        navMenuIsOpen = false;
        if (headerMenuSkinBody) {
            headerMenuSkinBody.classList.remove('nav--is-visible');
        }
        if (headerMenuSkinNavToggle) {
            headerMenuSkinNavToggle.classList.remove('header-menu-skin-active');
        }
        if (navMenu) {
            navMenu.classList.remove('header-menu-skin-active');
        }
        if (navMenuBG) {
            navMenuBG.classList.remove('header-menu-skin-active');
        }

    }

    // nav
    if (headerMenuSkinNavToggle) {
        headerMenuSkinNavToggle.addEventListener('click', function (e) {
            !navMenuIsOpen ? showNav() : hideNav();
            console.log('navMenuIsOpen', navMenuIsOpen);
        });
    }
    // show navbar on page scroll
    if(headerMenuSkiNav) {
        document.addEventListener('scroll', function (e) {
            var scrollPosition = pageYOffset;

            if (scrollPosition > windowNavOffset) {
                headerMenuSkiNav.classList.add('nav--solid');
            } else {
                headerMenuSkiNav.classList.remove('nav--solid');
            }
        });
    }

    // enable hover on touch
    document.addEventListener('touchstart', function(){}, true);

});

/* ###################### Counter ###################### */
var numbCount = function (el) {
    var html = el.innerHTML.trim();
    var to = parseInt(html, 10);
    var inc = 120;
    if (to > 20) inc = 60;
    if (to > 60) inc = 40;
    if (to > 120) inc = 10;
    if (to > 320) inc = 5;
    if (to > 1220) inc = 3;
    if (to > 5000) inc = 1;
    if (!isNaN(to)) {
        var time = 10;
        for (var i = 1; i <= to; i++) {
            time += inc;
            (function (time, i, el) {
                setTimeout(function () {
                    el.innerHTML = i;
                }, time)
            })(time, i, el)
        }
    }
}

$.fn.isOnScreen = function () {
    var win = $(window);

    var viewport = {
        top: win.scrollTop(),
        left: win.scrollLeft()
    };
    viewport.right = viewport.left + win.width();
    viewport.bottom = viewport.top + win.height();

    var bounds = this.offset();
    bounds.right = bounds.left + this.outerWidth();
    bounds.bottom = bounds.top + this.outerHeight();

    return (!(viewport.right < bounds.left || viewport.left > bounds.right || viewport.bottom < bounds.top || viewport.top > bounds.bottom));
};


$(window).on('load resize scroll', function () {
    setTimeout(function () {
        $('[data-counter]').each(function () {
            if ($(this).isOnScreen()) {
                $(this).css({'visibility': 'visible'});
                $(this).removeClass('js-start-from-zero');
                if (!this.__activated) {

                    this.__activated = true;
                    numbCount(this);
                }
            }
        });
    }, 10);

});
