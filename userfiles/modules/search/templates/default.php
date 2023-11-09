<?php

/*

type: layout

name: Default

description: Default Search template

*/

?>
<script>mw.moduleCSS("<?php print modules_url(); ?>search/search.css"); </script>
<div class="mw-search mw-search-default" id="search_box_holder_<?php print $params['id'] ?>">
    <div class="row">
        <div class="col">
            <input type="text"
                   id="search_field_<?php print $params['id'] ?>"
                   class="form-control"
                   oninput="mw.url.windowHashParam('keyword<?php print $seach_prefix ?>', this.value);"
                   placeholder="<?php _e("Search"); ?>"/>
        </div>

    </div>
    <div class="mw-search-results" id="search_results_holder_<?php print $params['id'] ?>"></div>
</div>


<script>
    $(document).ready(function () {

        mw.on.hashParam("keyword<?php print $seach_prefix ?>", function (pval) {
            mw.$("#search_box_holder_<?php  print $params['id'] ?>").addClass("loading");
            var key = pval;
            if (key != '') {
                var holder = mw.$('#search_results_holder_<?php  print $params['id'] ?>');
                mw.search(key, holder, {
                    done: function () {
                        mw.$("#search_box_holder_<?php  print $params['id'] ?>").removeClass("loading");
                    }
                });
            } else {
                mw.$('#search_results_holder_<?php  print $params['id'] ?>').hide();
            }
        });


        mw.$("#search_field_<?php  print $params['id'] ?>").on("keyup", function (e) {
            if (e.keyCode == 13) {
                mw.url.windowHashParam('keyword<?php print $seach_prefix ?>', this.value);
            }
        });

        var s = mw.url.windowHashParam("keyword<?php print $seach_prefix ?>");
        if (!!s) {
            document.getElementById('search_field_<?php  print $params['id'] ?>').value = decodeURIComponent(s);
        }

    });
</script>
