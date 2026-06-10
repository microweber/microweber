$(document).ready(function () {
    var e = '<div class="switcher-container">' +
        '<h2>STYLE SWITCHER<a href="#" class="switcher-toggle"><i class="fa fa-cog"></i></a></h2>' +
        '<div class="selector-box">' +
        '<div class="clearfix"></div>' +

        '<div class="sw-odd" style="display: none;">' +
        '<h3>SCHEME COLOR</h3>' +
        '<div class="ws-colors">' +
        '<a href="#color1" class="styleswitch" id="color1">' +
        '&nbsp;<span class="cl1"></span>' +
        '</a>' +
        '<a href="#color2" class="styleswitch" id="color2">' +
        '&nbsp;<span class="cl2"></span>' +
        '</a>' +
        '<a href="#color3" class="styleswitch" id="color3">' +
        '&nbsp;<span class="cl3"></span>' +
        '</a>' +
        '<a href="#color4" class="styleswitch" id="color4">' +
        '&nbsp;<span class="cl4"></span>' +
        '</a>' +
        '<a href="#color5" class="styleswitch" id="color5">' +
        '&nbsp;<span class="cl5"></span>' +
        '</a>' +
        '</div></div>' +


        '<div class="sw-even">' +
        '<h3>Sticky Navigation:</h3>' +
        '<a href="javascript:;" class="sw-light" data-sticky-navigation="false">Normal</a>' +
        '<a href="javascript:;" class="sw-light active" data-sticky-navigation="true">Sticky</a>' +
        '<br><br>' +

        '<h3>Member Navigation Color:</h3>' +
        '<a href="javascript:;" class="sw-light" data-navigation-color="normal">Normal</a>' +
        '<a href="javascript:;" class="sw-light active" data-navigation-color="inverse">Inverse</a>' +
        '<br><br>' +

        '<h3>Titles color:</h3>' +
        '<a href="javascript:;" class="sw-light" data-titles-inverse="normal">Normal</a>' +
        '<a href="javascript:;" class="sw-light active" data-titles-inverse="inverse">Black</a>' +
        '<br><br>' +

        '<h3>Buttons border radius:</h3>' +
        '<a href="javascript:;" class="sw-light active" data- -buttons="normal">Normal</a>' +
        '<a href="javascript:;" class="sw-light" data- -buttons=" "> </a><br />' +
        '<a href="javascript:;" class="sw-light" data- -buttons="squared">Squared</a>' +
        '</div>' +

        '<div class="clearfix"></div>' +
        '</div>' +
        '</div>';

    $('body').append(e);
    switchAnimate.loadEvent();
});


var switchAnimate = {
    loadEvent: function () {
        $(".switcher-container a.switcher-toggle").on('click', function (e) {
            $(this).addClass('active');
            var t = $(".switcher-container");

            if (t.css("right") === "-270px") {
                $(".switcher-container").css({'right': "0"})
            } else {
                $(this).removeClass('active');
                $(".switcher-container").css({'right': "-270px"})
            }

            e.preventDefault();
        });

        $('[data-sticky-navigation]').on('click', function () {
            $('[data-sticky-navigation]').removeClass('active');
            $(this).addClass('active');

            if ($(this).attr("data-sticky-navigation") == 'true') {
                $('body').addClass('sticky-nav');
            } else {
                $('body').removeClass('sticky-nav');
            }
        });

        $('[data-navigation-color]').on('click', function () {
            $('[data-navigation-color]').removeClass('active');
            $(this).addClass('active');

            if ($(this).attr("data-navigation-color") == 'inverse') {
                $('body').addClass('member-nav-inverse');
            } else {
                $('body').removeClass('member-nav-inverse');
            }
        });

        $('[data-titles-inverse]').on('click', function () {
            $('[data-titles-inverse]').removeClass('active');
            $(this).addClass('active');

            if ($(this).attr("data-titles-inverse") == 'inverse') {
                $('body').addClass('titles-inverse');
            } else {
                $('body').removeClass('titles-inverse');
            }
        });

        $('[data- -buttons]').on('click', function () {
            $('[data- -buttons]').removeClass('active');
            $(this).addClass('active');

            if ($(this).attr("data- -buttons") == ' ') {
                $('body').removeClass('squared-buttons');
                $('body').addClass(' -buttons');
            } else if ($(this).attr("data- -buttons") == 'squared') {
                $('body').removeClass(' -buttons');
                $('body').addClass('squared-buttons');
            } else {
                $('body').removeClass(' -buttons');
                $('body').removeClass('squared-buttons');
            }
        });
    }
};
