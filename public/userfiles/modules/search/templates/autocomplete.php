<?php

/*

type: layout

name: Autocomplete

description: Autocomplete Search template

*/

?>


<script>mw.moduleCSS("<?php print modules_url(); ?>search/search.css"); </script>
<?php $rand = uniqid(); ?>
<div class="mw-search mw-search-autocomplete w-100 mb-4" id="search_box_holder_<?php print $params['id'] . $rand ?>">
    <div class="mw-search-autocomplete-field-holder w-100">
        <input type="text"
               id="search_field_<?php print $params['id'] . $rand ?>"
               class="form-control  w-100"
               placeholder="<?php _e("Search"); ?>"
               onkeyup="mw.autocompleteSearch(document.getElementById('search_box_holder_<?php print $params['id'] . $rand ?>'), this, event, 'search_results_holder_<?php print $params['id'] . $rand ?>');"
               onpaste="mw.autocompleteSearch(document.getElementById('search_box_holder_<?php print $params['id'] . $rand ?>'), this, event, 'search_results_holder_<?php print $params['id'] . $rand ?>');"
        />
    </div>

    <div class="mw-autocomplete-search-results" style="display: none" id="search_results_holder_<?php print $params['id'] . $rand ?>"></div>

</div>

<script>

    mw.autocompleteSearch = function (parent, el, e, holder_id) {
        var parent = $(parent);
        if (e.type == 'keyup') {
            if (e.keyCode == 38) {
                mw.acnav('up', parent);
            }
            else if (e.keyCode == 40) {
                mw.acnav('down', parent);
            }
            else if (e.keyCode == 13) {
                mw.acnav('enter', parent);
            }
            else if (e.keyCode == 37) {

            }
            else if (e.keyCode == 39) {

            }
            else {

                parent.addClass("loading");

                el.timeo = el.timeo || null;
                clearTimeout(el.timeo);
                el.timeo = setTimeout(function () {
                    if (el.value == '') {
                        $(document.getElementById(holder_id)).hide();
                        parent.removeClass("loading");
                        return false;
                    }
                    $(document.getElementById(holder_id)).show();
                    mw.search(el.value, document.getElementById(holder_id), {
                        template: 'search',
                        done: function () {
                            parent.removeClass("loading");
                        }
                    });

                }, 600);
            }
        }
    }

    mw.acnav = function (a, parent) {
        var parent = $(parent)[0];

        var lis = mw.$('.module-posts-template-search > ul > li', parent);
        var active = mw.$('.module-posts-template-search > ul li.active', parent);
        if (a == 'up') {
            if (active.length > 0) {
                if (active.prev().length > 0) {
                    active.removeClass("active");
                    active.prev().addClass("active");
                }
                else {
                    active.removeClass("active");
                    lis.eq(lis.length - 1).addClass("active")
                }
            }
            else {
                lis.eq(lis.length - 1).addClass("active")
            }
        }
        else if (a == 'down') {
            if (active.length > 0) {
                if (active.next().length > 0) {
                    active.removeClass("active");
                    active.next().addClass("active");
                }
                else {
                    active.removeClass("active");
                    lis.eq(0).addClass("active")
                }
            }
            else {
                lis.eq(0).addClass("active")
            }
        }
        else if (a == 'enter') {
            if (active.length > 0) {
                window.location.href = active.find("a").attr("href");
            }
        }
    }

    if (!mw.autocompleteBinded) {
        mw.autocompleteBinded = true;
        $(document.body).on('keyup mousedown', function (e) {
            if (!mw.tools.hasParentsWithClass(e.target, 'mw-search-autocomplete')) {
                mw.$('.mw-autocomplete-search-results').hide();
            }
        });
    }


</script>
