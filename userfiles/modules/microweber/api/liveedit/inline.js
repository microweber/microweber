mw.liveedit.inline = {
    bar: function (id) {
        if (typeof id === 'undefined') {
            return false;
        }
        if (mw.$("#" + id).length === 0) {
            var bar = mwd.createElement('div');
            bar.id = id;
            mw.wysiwyg.contentEditable(bar, false);
            bar.className = 'mw-defaults mw-inline-bar';
            mwd.body.appendChild(bar);
            return bar;
        }
        else {
            return mw.$("#" + id)[0];
        }
    },
    tableControl: false,
    tableController: function (el, e) {
        if (typeof e !== 'undefined') {
            e.stopPropagation();
        }
        if (mw.liveedit.inline.tableControl === false) {
            mw.liveedit.inline.tableControl = mw.liveedit.inline.bar('mw-inline-tableControl');
            mw.liveedit.inline.tableControl.innerHTML = ''
                + '<ul class="mw-ui-box mw-ui-navigation mw-ui-navigation-horizontal">'
                + '<li>'
                + '<a href="javascript:;">Insert<span class="mw-icon-dropdown"></span></a>'
                + '<ul>'
                + '<li><a href="javascript:;" onclick="mw.liveedit.inline.tableManager.insertRow(\'above\', mw.liveedit.inline.activeCell);">Row Above</a></li>'
                + '<li><a href="javascript:;" onclick="mw.liveedit.inline.tableManager.insertRow(\'under\', mw.liveedit.inline.activeCell);">Row Under</a></li>'
                + '<li><a href="javascript:;" onclick="mw.liveedit.inline.tableManager.insertColumn(\'left\', mw.liveedit.inline.activeCell)">Column on left</a></li>'
                + '<li><a href="javascript:;" onclick="mw.liveedit.inline.tableManager.insertColumn(\'right\', mw.liveedit.inline.activeCell)">Column on right</a></li>'
                + '</ul>'
                + '</li>'
                + '<li>'
                + '<a href="javascript:;">Style<span class="mw-icon-dropdown"></span></a>'
                + '<ul>'
                + '<li><a href="javascript:;" onclick="mw.liveedit.inline.tableManager.setStyle(\'mw-wysiwyg-table\', mw.liveedit.inline.activeCell);">Bordered</a></li>'
                + '<li><a href="javascript:;" onclick="mw.liveedit.inline.tableManager.setStyle(\'mw-wysiwyg-table-zebra\', mw.liveedit.inline.activeCell);">Bordered Zebra</a></li>'
                + '<li><a href="javascript:;" onclick="mw.liveedit.inline.tableManager.setStyle(\'mw-wysiwyg-table-simple\', mw.liveedit.inline.activeCell);">Simple</a></li>'
                + '<li><a href="javascript:;" onclick="mw.liveedit.inline.tableManager.setStyle(\'mw-wysiwyg-table-simple-zebra\', mw.liveedit.inline.activeCell);">Simple Zebra</a></li>'
                + '</ul>'
                + '</li>'
                + '<li>'
                + '<a href="javascript:;">Delete<span class="mw-icon-dropdown"></span></a>'
                + '<ul>'
                + '<li><a href="javascript:;" onclick="mw.liveedit.inline.tableManager.deleteRow(mw.liveedit.inline.activeCell);">Row</a></li>'
                + '<li><a href="javascript:;" onclick="mw.liveedit.inline.tableManager.deleteColumn(mw.liveedit.inline.activeCell);">Column</a></li>'
                + '</ul>'
                + '</li>'
                + '</ul>';
        }
        var off = mw.$(el).offset();
        mw.$(mw.liveedit.inline.tableControl).css({
            top: off.top - 45,
            left: off.left,
            display: 'block'
        });
    },
    activeCell: null,
    setActiveCell: function (el, event) {
        if (!mw.tools.hasClass(el.className, 'tc-activecell')) {
            mw.$(".tc-activecell").removeClass('tc-activecell');
            mw.$(el).addClass('tc-activecell');
            mw.liveedit.inline.activeCell = el;
        }
    },
    tableManager: {
        insertColumn: function (dir, cell) {
            cell = mw.$(cell)[0];
            if (cell === null) {
                return false;
            }
            dir = dir || 'right';
            var rows = mw.$(cell.parentNode.parentNode).children('tr');
            var i = 0, l = rows.length, index = mw.tools.index(cell);
            for (; i < l; i++) {
                var row = rows[i];
                var cell = mw.$(row).children('td')[index];
                if (dir == 'left' || dir == 'both') {
                    mw.$(cell).before("<td>&nbsp;</td>");
                }
                if (dir == 'right' || dir == 'both') {
                    mw.$(cell).after("<td>&nbsp;</td>");
                }
            }
        },
        insertRow: function (dir, cell) {
            var cell = mw.$(cell)[0];
            if (cell === null) {
                return false;
            }
            var dir = dir || 'under';
            var parent = cell.parentNode, cells = mw.$(parent).children('td'), i = 0, l = cells.length, html = '';
            for (; i < l; i++) {
                html += '<td>&nbsp;</td>';
            }
            var html = '<tr>' + html + '</tr>';
            if (dir == 'under' || dir == 'both') {
                mw.$(parent).after(html)
            }
            if (dir == 'above' || dir == 'both') {
                mw.$(parent).before(html)
            }
        },
        deleteRow: function (cell) {
            mw.$(cell.parentNode).remove();
        },
        deleteColumn: function (cell) {
            var index = mw.tools.index(cell), body = cell.parentNode.parentNode, rows = mw.$(body).children('tr'), l = rows.length, i = 0;
            for (; i < l; i++) {
                var row = rows[i];
                mw.$(row.getElementsByTagName('td')[index]).remove();
            }
        },
        setStyle: function (cls, cell) {
            var table = mw.tools.firstParentWithTag(cell, 'table');
            mw.tools.classNamespaceDelete(table, 'mw-wysiwyg-table');
            mw.$(table).addClass(cls);
        }
    }
}
