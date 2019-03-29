/*

 * CollapseNav.js - v1.0
 * jQuery Responsive Navigation
 * MIT License
 * by Petko Yovchevski

 Website: https://www.plumtex.com
 Docs: http://pyovchevski.github.io/collapse-nav
 Repo: https://github.com/PYovchevski/collapse-nav
 Issues: https://github.com/PYovchevski/collapse-nav/issues

 */

function collapseNav(selector, config) {
    var navigation = selector;
    var original_navigation = selector;

    var responsive = config.responsive;
    if (!responsive) {
        responsive = 1;
    }

    var mobile_break = config.mobile_break;
    if (!mobile_break) {
        mobile_break = 992;
    }

    var li_class = config.li_class;
    if (!li_class && li_class != '') {
        li_class = 'dropdown';
    }

    var li_a_class = config.li_a_class;
    if (!li_a_class && li_a_class != '') {
        li_a_class = 'dropdown-toggle';
    }

    var li_ul_class = config.li_ul_class;
    if (!li_ul_class && li_ul_class != '') {
        li_ul_class = 'dropdown-menu';
    }


    var more_text = config.more_text;
    if (!more_text) {
        more_text = 'More';
    }

    var caret = config.caret;
    if (!caret && caret != '') {
        caret = '<span class="caret"></span>';
    }

    var ul_width = $(navigation).outerWidth(true);
    var li_width = 0;
    var possible_buttons = -2;

    //The New More Button Width
    $(navigation).children().first().clone().appendTo(navigation);
    $(navigation).children().last().find('a').html(more_text + ' ' + caret).css({'vissibility': 'hidden'});
    var the_more_button_width = $(navigation).children().last();
    li_width = li_width + the_more_button_width.outerWidth(true) + 50;
    the_more_button_width.remove();

    $(navigation).children("li").each(function (i) {
        if (ul_width >= li_width) {
            possible_buttons = possible_buttons + 1;
        }
        li_width = li_width + $(this).outerWidth(true);
    });

    if (responsive == 1) {
        number_of_buttons = possible_buttons;
    } else {
        var number_of_buttons = config.number_of_buttons - 1;
        if (!number_of_buttons) {
            number_of_buttons = 4 - 1;
        }
    }

    if ($(window).width() < mobile_break) {
        return;
    }

    var ul = '<ul class="' + li_ul_class + '">'
    $(navigation).children("li").each(function (i) {
        if (i > number_of_buttons) {
            ul += this.outerHTML;
        }
    })
    ul += '</ul>';

    $(navigation).children("li:gt(" + number_of_buttons + ")").remove();
    $(navigation).append('<li class="' + li_class + '"><a href="javascript:;" class="' + li_a_class + '" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">' + more_text + caret + '</a>' + ul + '</li>')
}

$.fn.collapseNav = function (config) {
    if (!config) {
        config = {
            responsive: 1, //Automatically count the possible buttons in the navigation
            number_of_buttons: 4, //Allowable number of buttons in the navigation. Works only if 'responsive' = 0
            more_text: 'More', //The text on the Drop Down Button
            mobile_break: 992, //With this resolution and higher the script will be init
            li_class: 'dropdown',
            li_a_class: 'dropdown-toggle',
            li_ul_class: 'dropdown-menu',
            caret: '<span class="caret"></span>' //Element append immediately after the More text
        }
    }

    return this.each(function () {
        collapseNav(this, config);
    })
}
