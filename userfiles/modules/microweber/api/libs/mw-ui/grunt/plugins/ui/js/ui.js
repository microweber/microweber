/* UI */
$(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();
    $('.selectpicker').selectpicker();

    $(".js-range").each(function (index) {
        var from = $(this).find('.js-from');
        var to = $(this).find('.js-to');
        var slider = $(this).find('.js-slider');

        slider.ionRangeSlider({
            onChange: function (data) {
                from.val(data.from);
                to.val(data.to);
            }
        });

        this_range = slider.data("ionRangeSlider");

        from.on('change', function () {
            this_range.update({
                from: from.val()
            });
        });

        to.on('change', function () {
            this_range.update({
                to: to.val()
            });
        });
    });

    $('.js-change-color').on('change', function () {
        var color = $(this).find('option:selected').data('change-color');
        $(this).find('.dropdown-toggle').removeClass('btn-primary btn-secondary btn-success btn-warning btn-danger').addClass(color);
    });

    $(".btn-minus", '.btn-group-quantity').on("click", function (m) {
        var i = $(this).parent().find('input[type="text"]');
        if (i.val() <= 1) {
            i.val("1").change();
        } else {
            var l = i.val() - 1;
            i.val(l).change();
        }
    });

    $(".btn-plus", '.btn-group-quantity').on("click", function (m) {
        var i = $(this).parent().find('input[type="text"]');
        if (i.val() <= 100) {
            var l = +i.val() + +1;
            i.val(l).change();
        }
    });

    $(".minus", '.input-group-quantity').on("click", function (m) {
        var i = $(this).parent().parent().parent().find('input[type="text"]');
        if (i.val() <= 1) {
            i.val("1").change();
        } else {
            var l = i.val() - 1;
            i.val(l).change();
        }
    });

    $(".plus", '.input-group-quantity').on("click", function (m) {
        var i = $(this).parent().parent().parent().find('input[type="text"]');
        if (i.val() <= 100) {
            var l = +i.val() + +1;
            i.val(l).change();
        }
    });

    $('.dropdown .dropdown-toggle', 'aside').on('click', function () {
        $(this).parent().toggleClass('show');
        $(this).parent().find('.dropdown-menu').toggleClass('show');
    });

    $('.js-toggle-mobile-nav').on('click', function () {
        $(this).toggleClass('opened');
        $('body').find('aside').toggleClass('opened');
        $('body').find('.tree').toggleClass('opened');
        $('html, body').toggleClass('prevent-scroll');
    });

    $('.js-show-more').on('click', function (e) {
        e.stopPropagation();
        e.preventDefault();
        var replaceWhat = $(this).attr('href');

        if ($(replaceWhat).is(":visible")) {
            $(replaceWhat).hide();
        } else {
            $(replaceWhat).show();
        }
    });

    $('button, a, input, textarea', '.collapse').on('click', function (e) {
        e.stopPropagation();
        e.preventDefault();
    });

    $(".selectpicker").each(function (index) {
        if ($(this).data('style')) {
            $(this).parent().addClass($(this).data('style') + '-dropdown');
        }
    });


    //Custom checkboxes and radios in .collapse fix
    $('.collapse .custom-control-label').on('click', function () {
        var checkBox = $(this).prev('input');

        if ($(checkBox).attr('checked')) {
            $(checkBox).removeAttr('checked');
        } else {
            $(checkBox).attr('checked', 'checked');
        }

        return false;
    });

    $('input, textarea', '.js-count-letters').on('keyup', function () {
        var countLettersParent = $(this).closest('.js-count-letters');
        var countLettersNumber = countLettersParent.find('.js-typed-letters').text($(this).val().length);
    });

    theProductHasVariants();

    $('.js-product-has-variants').on('change', function () {
        theProductHasVariants($(this));
    });
});

function theProductHasVariants(field) {
    if (typeof field !== 'undefined') {
        console.log($(this).closest('.js-product-variants'));
        var theProductHasVariant = $('.js-product-has-variants').prop("checked");
        if (theProductHasVariant) {
            $(this).closest('.js-product-variants').show('slow');
        } else {
            $(this).closest('.js-product-variants').hide('slow');
        }
    } else {
        $(".js-product-has-variants").each(function (index) {
            theProductHasVariants($(this));
        });
    }
}