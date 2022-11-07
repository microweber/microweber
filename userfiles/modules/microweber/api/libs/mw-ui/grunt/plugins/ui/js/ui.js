/* UI */

$(document).ready(function () {
    $('[data-toggle="tooltip"]').tooltip();
    $('.selectpicker').selectpicker({
        container: 'body'
    });

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

    $('body').on('change', 'select.js-change-color', function () {
        var color = $(this).find('option:selected').data('change-color');
        $(this).parent().removeClass('btn-primary').removeClass('btn-secondary').removeClass('btn-success').removeClass('btn-warning').removeClass('btn-danger').addClass(color);
        $(this).parent().find('.dropdown-toggle').removeClass('btn-primary').removeClass('btn-secondary').removeClass('btn-success').removeClass('btn-warning').removeClass('btn-danger').addClass(color);
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

    $(".js-show-password", '.input-group-password').on("click", function (m) {
        var field = $(this).parent().parent().find('input');

        if ('password' == field.attr('type')) {
            field.prop('type', 'text');
            $(this).find('i').removeClass('text-muted').addClass('text-primary');
        } else {
            field.prop('type', 'password');
            $(this).find('i').removeClass('text-primary').addClass('text-muted');
        }
    });

    $('.dropdown .dropdown-toggle', 'aside').on('click', function () {
        $(this).parent().toggleClass('show');
        $(this).parent().find('.dropdown-menu').toggleClass('show');
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

    /*    $('button, a, input, textarea', '.collapse').on('click', function (e) {
     e.stopPropagation();
     e.preventDefault();
     });*/

    $('body').on('click', '[data-toggle="collapse-mw"]', function (e) {
        var has = mw.tools.firstMatchesOnNodeOrParent(e.target, [
            'a', 'button', '.btn', '.mw-ui-btn', 'input', 'select', 'textarea', 'img', 'label', '.mdi'
        ]);
        if (!has) {
            var target = $(this).data('target');
            $(target).toggleClass('show');
        }
    });


    $(".selectpicker").each(function (index) {
        if ($(this).data('style')) {
            $(this).parent().addClass($(this).data('style') + '-dropdown');
        }
    });

    //Custom checkboxes and radios in .collapse fix
    $('.collapse .custom-control-label').on('click', function () {
        // var checkBox = $(this).prev('input');
        //
        // if ($(checkBox).attr('checked')) {
        //     $(checkBox).removeAttr('checked');
        // } else {
        //     $(checkBox).attr('checked', 'checked');
        // }
        // $(checkBox).trigger('change');
        // return;
    });

    $('input, textarea', '.js-count-letters').on('keyup', function () {
        var countLettersParent = $(this).closest('.js-count-letters');
        var countLettersNumber = countLettersParent.find('.js-typed-letters').text($(this).val().length);
    });

    theProductHasVariants();

    $('.js-product-has-variants').on('change', function () {
        theProductHasVariants($(this));
    });

    $("input[data-role=tagsinput], select[multiple][data-role=tagsinput]").tagsinput();
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

/**
 * Replace all SVG images with inline SVG
 */
function SVGtoCode() {
    $('img.svg[src*=".svg"]').each(function () {
        var $img = $(this);
        var imgID = $img.attr('id');
        var imgClass = $img.attr('class');
        var imgURL = $img.attr('src');


        $.ajax({
            url: imgURL,
            cache: true,
            success: function(data) {

                var $svg = $(data).find('svg');

                // Add replaced image's ID to the new SVG
                if (typeof imgID !== 'undefined') {
                    $svg = $svg.attr('id', imgID);
                }
                // Add replaced image's classes to the new SVG
                if (typeof imgClass !== 'undefined') {
                    $svg = $svg.attr('class', imgClass + ' replaced-svg');
                }

                // Remove any invalid XML tags as per http://validator.w3.org
                $svg = $svg.removeAttr('xmlns:a');

                // Replace image with new SVG
                $img.replaceWith($svg);


            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {

            }
        });




      /*  $.get({
            url: imgURL,
            cache: true
        }).fail(function() {

        }).done(function (data) {
            var $svg = $(data).find('svg');

            // Add replaced image's ID to the new SVG
            if (typeof imgID !== 'undefined') {
                $svg = $svg.attr('id', imgID);
            }
            // Add replaced image's classes to the new SVG
            if (typeof imgClass !== 'undefined') {
                $svg = $svg.attr('class', imgClass + ' replaced-svg');
            }

            // Remove any invalid XML tags as per http://validator.w3.org
            $svg = $svg.removeAttr('xmlns:a');

            // Replace image with new SVG
            $img.replaceWith($svg);
        });*/
    });
}
