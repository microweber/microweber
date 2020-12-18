mw.liveedit.manageContent = {
    w: '1220px',
    page: function() {
        var modal = mw.dialogIframe({
            url: mw.settings.api_url + "module/?type=content/edit_page&live_edit=true&quick_edit=false&id=mw-quick-page&recommended_parent=" + mw.settings.page_id,
            width: this.w,
            height: 'auto',
            autoHeight:true,
            name: 'quick_page',
            overlay: true,
            title: 'New Page',
            scrollMode: 'inside'
        });
        mw.$(modal.main).addClass('mw-add-content-modal');
    },
    category: function() {
        var modal = mw.dialogIframe({
            url: mw.settings.api_url + "module/?type=categories/edit_category&live_edit=true&quick_edit=false&id=mw-quick-category&recommended_parent=" + mw.settings.page_id,
            width: this.w,
            height: 'auto',
            autoHeight:true,
            name: 'quick_page',
            overlay: true,
            title: 'New Category',
            scrollMode: 'inside'
        });
        mw.$(modal.main).addClass('mw-add-content-modal');
    },
    edit: function(id, content_type, subtype, parent, category) {
        var str = "";

        if (parent) {
            str = "&recommended_parent=" + parent;
        }

        if (content_type) {
            str = str + '&content_type=' + content_type;
        }

        if (category) {
            str = str + '&category=' + category;
        }

        if (subtype) {
            str = str + '&subtype=' + subtype;
        }

        var actionType = '';

        if(id === 0){
            actionType = 'Add';
        }else{
            actionType = 'Edit';
        }

        var actionOf = 'Content';
        if(content_type === 'post'){
            actionOf = 'Post'
        }else if(content_type === 'page'){
            actionOf = 'Page'
        }else if(content_type === 'product'){
            actionOf = 'Product'
        }else if(content_type === 'category'){
            actionOf = 'Category'
        }

        var modal = mw.dialogIframe({
            url: mw.settings.api_url + "module/?type=content/edit&live_edit=true&quick_edit=false&is-current=true&id=mw-quick-page&content-id=" + id + str,
            width: this.w,
            height: 'auto',
            autoHeight:true,
            name: 'quick_page',
            id: 'quick_page',
            overlay: true,
            title: actionType + ' ' + actionOf,
            scrollMode: 'inside'
        });
        mw.$(modal.main).addClass('mw-add-content-modal');
    },
    page_2: function() {
        var modal = mw.dialogIframe({
            url: mw.settings.api_url + "module/?type=content/quick_add&live_edit=true&id=mw-new-content-add-ifame",
            width: this.w,
            height: 'auto',
            name: 'quick_page',
            overlay: true,
            title: 'New Page',
            scrollMode: 'inside'
        });
        mw.$(modal.main).addClass('mw-add-content-modal');
    },
    post: function() {
        var modal = mw.dialogIframe({
            url: mw.settings.api_url + "module/?type=content/edit_page&live_edit=true&quick_edit=false&id=mw-quick-post&subtype=post&parent-page-id=" + mw.settings.page_id + "&parent-category-id=" + mw.settings.category_id,
            width: this.w,
            height: 'auto',
            autoHeight:true,
            name: 'quick_post',
            overlay: true,
            title: 'New Post'
        });
        mw.$(modal.main).addClass('mw-add-content-modal');
    },
    product: function() {
        var modal = mw.dialogIframe({
            url: mw.settings.api_url + "module/?type=content/edit_page&live_edit=true&quick_edit=false&id=mw-quick-product&subtype=product&parent-page-id=" + mw.settings.page_id + "&parent-category-id=" + mw.settings.category_id,
            width: this.w,
            height: 'auto',
            autoHeight:true,
            name: 'quick_product',
            overlay: true,
            title: 'New Product'
        });
        mw.$(modal.main).addClass('mw-add-content-modal');
    }
}
