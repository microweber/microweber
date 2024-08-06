mw.widget.tree = function (target, opt, mode) {


    if(typeof target === "string") {
        target = document.querySelector(target);
    }
    if(!target) {
        return;
    }
    if(!mode) {
        mode = 'treeTags';
    }
    if(!opt) {
        opt = {};
    }



    mw.spinner({element: target, size: 32, decorate: true});

    var tree;

    var params = opt.params, options = opt.options || {};

    var url = mw.settings.api_url + 'content/get_admin_js_tree_json';
    var treeEl = document.createElement('div');
    treeEl.className = 'mw--global-admin-tree';
    if(options.id) {
        treeEl.id = 'mw--parent-' + options.id;
    }



    if(!params) {
        params = {};
    }

    if(!options) {
        options = {};
    }


    var optionsDefaults;

    function _getTree() {
        return tree.tree || tree;
    }

    if(mode === 'tree') {
        optionsDefaults = {
            element: treeEl,
            sortable: false,
            selectable: true,
            singleSelect: true,
            searchInput: true
        };
    } else if(mode === 'treeTags') {
        var tags = mw.element();

        optionsDefaults = {
            selectable: true,
            multiPageSelect: false,
            tagsHolder: tags.get(0),
            treeHolder: treeEl,
            color: 'primary',
            size: 'sm',
            outline: true,
            saveState: false,
            searchInput: true,
            on: {
                selectionChange: function () {
                //    mw.askusertostay = true;

                }
            }
        };

        target.appendChild(tags.get(0));
    }

    target.appendChild(treeEl);

    var _serialize = function(obj) {
        var str = [];
        for (var p in obj){
            if (obj.hasOwnProperty(p)) {
                str.push(encodeURIComponent(p) + "=" + encodeURIComponent(obj[p]));
            }
        }
        return str.join("&");
    };

    var treeSettings = Object.assign({}, optionsDefaults, options);

    url = url + '?' + _serialize(params);

    return new Promise(function (resolve){
        $.get(url, function (data) {
            treeSettings.data = data;
            tree = new mw[mode](treeSettings);

            var res =  {
                tree: mode === 'tree' ? tree : tree.tree,
                tags: mode === 'tree' ? null : tree.tags,
                treeTags: mode === 'tree' ? null : tree
            };
            resolve(res);
            mw.spinner({element: target}).remove();
        });
    });
};
