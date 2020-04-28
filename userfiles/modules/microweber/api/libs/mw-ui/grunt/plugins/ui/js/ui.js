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
});