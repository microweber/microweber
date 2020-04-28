</div>
<!-- Optional JavaScript -->
<!-- jQuery first, then Popper.js, then Bootstrap JS -->
<script src="grunt/dist/plugins/js/plugins.js"></script>
<script>
    $(document).ready(function () {
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
    });
</script>

</body>
</html>