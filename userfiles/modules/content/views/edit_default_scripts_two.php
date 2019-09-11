<script>

    var thetree = mwd.querySelector(".mw-ui-category-selector-abs .module")


    CreateCategoryForPost = function (step) {

        mw.$("#category-not-found-name").html(mw.$('#quick-tag-field').val());
        if (step === 0) {
            mw.$("#category-tree-not-found-message").hide();
            mw.$("#parent-category-selector-block").hide();
        }
        if (step === 1) {
            mw.$(".mw-ui-category-selector-abs").scrollTop(0);
            mw.$("#category-tree-not-found-message").show();
            mw.$("#parent-category-selector-block").hide();
        }
        else if (step === 2) {
            if (mw.$(".mw-tag-selector .mw-ui-btn-small").length === 0) {
                mw.$("#category-tree-not-found-message").hide();
                mw.$("#parent-category-selector-block").show();
            }
            else {
                CreateCategoryForPost(3);
            }
        }
        else if (step == 3) {
            var checked = mwd.querySelector('#categoryparent input:checked');
            if (checked == null) {
                var checked = mwd.querySelector('#pages_edit_container input[type=radio]:checked');
            }
            if (checked == null) {
                return;
            }
            var parent = "content_id"
            //  var parent = mw.tools.firstParentWithTag(checked, 'li');
            //  var parent = mw.tools.hasClass(parent, 'is_page') ? 'content_id' : 'parent_id';
            var data = {
                title: mw.$('#quick-tag-field').val()
            };
            data[parent] = checked.value;
            //data[parent] = checked.value;
            $.post(mw.settings.api_url + "category/save", data, function () {
                mw.reload_module("categories/selector", function (el) {
                    mw.$("#category-tree-not-found-message").hide();
                    mw.$("#parent-category-selector-block").show();
                })
            });
        }
    }

</script>
