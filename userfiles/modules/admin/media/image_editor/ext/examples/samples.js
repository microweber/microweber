/*
 * Ext JS Library 2.2
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

SamplePanel = Ext.extend(Ext.DataView, {
    autoHeight: true,
    frame:true,
    cls:'demos',
    itemSelector: 'dd',
    overClass: 'over',
    
    tpl : new Ext.XTemplate(
        '<div id="sample-ct">',
            '<tpl for=".">',
            '<div><a name="{id}"></a><h2><div>{title}</div></h2>',
            '<dl>',
                '<tpl for="samples">',
                    '<dd ext:url="{url}"><img src="shared/screens/{icon}"/>',
                        '<div><h4>{text}</h4><p>{desc}</p></div>',
                    '</dd>',
                '</tpl>',
            '<div style="clear:left"></div></dl></div>',
            '</tpl>',
        '</div>'
    ),

    onClick : function(e){
        var group = e.getTarget('h2', 3, true);
        if(group){
            group.up('div').toggleClass('collapsed');
        }else {
            var t = e.getTarget('dd', 5, true);
            if(t && !e.getTarget('a', 2)){
                var url = t.getAttributeNS('ext', 'url');
                window.open(url);
            }
        }
        return SamplePanel.superclass.onClick.apply(this, arguments);
    }
});


Ext.EventManager.on(window, 'load', function(){

    var catalog = [{
        title: 'Combination Samples',
        samples: [{
            text: 'Feed Viewer 2.0',
            url: 'feed-viewer/view.html',
            icon: 'feeds.gif',
            desc: 'RSS 2.0 feed reader sample application that features a swappable reader panel layout.'
        },{
            text: 'Simple Tasks 2.0',
            url: 'http://extjs.com/blog/2008/02/24/tasks2/',
            icon: 'air.gif',
            desc: 'Complete personal task management application sample that runs on <a href="http://labs.adobe.com/technologies/air/" target="_blank">Adobe AIR</a>.'
        },{
            text: 'Simple Tasks',
            url: 'tasks/tasks.html',
            icon: 'tasks.gif',
            desc: 'Personal task management application sample that uses <a href="http://gears.google.com" target="_blank">Google Gears</a> for data storage.'
        },{
            text: 'Image Organizer',
            url: 'organizer/organizer.html',
            icon: 'organizer.gif',
            desc: 'DataView and TreePanel sample that demonstrates dragging data items from a DataView into a TreePanel.'
        },{
            text: 'Web Desktop',
            url: 'desktop/desktop.html',
            icon: 'desktop.gif',
            desc: 'Demonstrates how one could build a desktop in the browser using Ext components including a module plugin system.'
        }]
    },{
        title: 'Grids',
        samples: [{
            text: 'Basic Array Grid',
            url: 'grid/array-grid.html',
            icon: 'grid-array.gif',
            desc: 'A basic read-only grid loaded from local array data that demonstrates the use of custom column renderer functions.'
        },{
            text: 'Editable Grid',
            url: 'grid/edit-grid.html',
            icon: 'grid-edit.gif',
            desc: 'An editable grid loaded from XML that shows multiple types of grid ediors as well as defining custom data records.'
        },{
            text: 'XML Grid',
            url: 'grid/xml-grid.html',
            icon: 'grid-xml.gif',
            desc: 'A simple read-only grid loaded from XML data.'
        },{
            text: 'Paging',
            url: 'grid/paging.html',
            icon: 'grid-paging.gif',
            desc: 'A grid with paging, cross-domain data loading and custom- rendered expandable row bodies.'
        },{
            text: 'Grouping',
            url: 'grid/grouping.html',
            icon: 'grid-grouping.gif',
            desc: 'A basic grouping grid showing collapsible data groups that can be customized via the "Group By" header menu option.'
        },{
            text: 'Live Group Summary',
            url: 'grid/totals.html',
            icon: 'grid-summary.gif',
            desc: 'Advanced grouping grid that allows cell editing and includes custom dynamic summary calculations.'
        },{
            text: 'Grid Plugins',
            url: 'grid/grid3.html',
            icon: 'grid-plugins.gif',
            desc: 'Multiple grids customized via plugins: expander rows, checkbox selection and row numbering.'
        },{
            text: 'Grid Filtering',
            url: 'grid-filtering/grid-filter.html',
            icon: 'grid-filter.gif',
            desc: 'Grid plugins providing custom data filtering menus that support various data types.'
        },{
            text: 'Grid From Markup',
            url: 'grid/from-markup.html',
            icon: 'grid-from-markup.gif',
            desc: 'Custom GridPanel extension that can convert a plain HTML table into a dynamic grid at runtime.'
        },{
            text: 'Grid Data Binding (basic)',
            url: 'grid/binding.html',
            icon: 'grid-data-binding.gif',
            desc: 'Data binding a grid to a detail preview panel via the grid\'s RowSelectionModel.'
        },{
            text: 'Grid Data Binding (advanced)',
            url: 'grid/binding-with-classes.html',
            icon: 'grid-data-binding.gif',
            desc: 'Refactoring the basic data binding example to use a class-based application design model.'
        }]
    },{
        title: 'Tabs',
        samples: [{
            text: 'Basic Tabs',
            url: 'tabs/tabs.html',
            icon: 'tabs.gif',
            desc: 'Basic tab functionality including autoHeight, tabs from markup, Ajax loading and tab events.'
        },{
            text: 'Advanced Tabs',
            url: 'tabs/tabs-adv.html',
            icon: 'tabs-adv.gif',
            desc: 'Advanced tab features including tab scrolling, adding tabs programmatically and a context menu plugin.'
        }]
    },{
        title: 'Windows',
        samples: [{
            text: 'Hello World',
            url: 'window/hello.html',
            icon: 'window.gif',
            desc: 'Simple "Hello World" window that contains a basic TabPanel.'
        },{
            text: 'MessageBox',
            url: 'message-box/msg-box.html',
            icon: 'msg-box.gif',
            desc: 'Different styles include confirm, alert, prompt, progress and wait and also support custom icons.'
        },{
            text: 'Layout Window',
            url: 'window/layout.html',
            icon: 'window-layout.gif',
            desc: 'A window containing a basic BorderLayout with nested TabPanel.'
        }]
    },{
        title: 'Trees',
        samples: [{
            text: 'Drag and Drop Reordering',
            url: 'tree/reorder.html',
            icon: 'tree-reorder.gif',
            desc: 'A TreePanel loaded asynchronously via a JSON TreeLoader that shows drag and drop with container scroll.'
        },{
            text: 'Multiple trees',
            url: 'tree/two-trees.html',
            icon: 'tree-two.gif',
            desc: 'Drag and drop between two different sorted TreePanels.'
        },{
            text: 'Column Tree',
            url: 'tree/column-tree.html',
            icon: 'tree-columns.gif',
            desc: 'A custom TreePanel implementation that demonstrates extending an existing component.'
        },{
            text: 'XML Tree Loader',
            url: 'tree/xml-tree-loader.html',
            icon: 'tree-xml-loader.gif',
            desc: 'A custom TreeLoader implementation that demonstrates loading a tree from an XML document.'
        }]
    },{
        title: 'Layout Managers',
        samples: [{
            text: 'Layout Browser',
            url: 'layout-browser/layout-browser.html',
            icon: 'layout-browser.gif',
            desc: 'Includes examples for each standard Ext layout, several custom layouts and combination examples.'
        },{
            text: 'Border Layout',
            url: 'layout/complex.html',
            icon: 'border-layout.gif',
            desc: 'A complex BorderLayout implementation that shows nesting multiple components and sub-layouts.'
        },{
            text: 'Anchor Layout',
            url: 'form/anchoring.html',
            icon: 'anchor.gif',
            desc: 'A simple example of anchoring form fields to a window for flexible form resizing.'
        },{
            text: 'Portal Demo',
            url: 'portal/portal.html',
            icon: 'portal.gif',
            desc: 'A page layout using several custom extensions to provide a web portal interface.'
        }]
    },{
        title: 'ComboBox',
        samples: [{
            text: 'Basic ComboBox',
            url: 'form/combos.html',
            icon: 'combo.gif',
            desc: 'Basic combos, combos rendered from markup and customized list layout to provide item tooltips.'
        },{
            text: 'ComboBox Templates',
            url: 'form/forum-search.html',
            icon: 'combo-custom.gif',
            desc: 'Customized combo with template-based list rendering, remote loading and paging.'
        }]
    },{
        title: 'Forms',
        samples: [{
            text: 'Dynamic Forms',
            url: 'form/dynamic.html',
            icon: 'form-dynamic.gif',
            desc: 'Various example forms showing collapsible fieldsets, column layout, nested TabPanels and more.'
        },{
            text: 'Ajax with XML Forms',
            url: 'form/xml-form.html',
            icon: 'form-xml.gif',
            desc: 'Ajax-loaded form fields from remote XML data and remote field validation on submit.'
        },{
            text: 'Custom Search Field',
            url: 'form/custom.html',
            icon: 'form-custom.gif',
            desc: 'A TriggerField search extension combined with an XTemplate for custom results rendering.'
        },{
            text: 'Binding a Grid to a Form',
            url: 'form/form-grid.html',
            icon: 'form-grid-binding.gif',
            desc: 'A grid embedded within a FormPanel that automatically loads records into the form on row selection.'
        },{
            text: 'Advanced Validation',
            url: 'form/adv-vtypes.html',
            icon: 'form-adv-vtypes.gif',
            desc: 'Relational form field validation using custom vtypes.'
        },{
            text: 'Checkbox/Radio Groups',
            url: 'form/check-radio.html',
            icon: 'form-check-radio.gif',
            desc: 'Many examples showing different checkbox and radio group configurations.'
        },{
            text: 'File Upload Field',
            url: 'form/file-upload.html',
            icon: 'form-file-upload.gif',
            desc: 'A demo of how to give standard file upload fields a bit of Ext style using a custom class.'
        },{
            text: 'MultiSelect and ItemSelector',
            url: 'multiselect/multiselect-demo.html',
            icon: 'form-multiselect.gif',
            desc: 'Example controls for selecting a list of items in forms.'
        }]
    },{
        title: 'Toolbars and Menus',
        samples: [{
            text: 'Basic Toolbar',
            url: 'menu/menus.html',
            icon: 'toolbar.gif',
            desc: 'Toolbar and menus that contain various components like date pickers, color pickers, sub-menus and more.'
        },{
            text: 'Ext Actions',
            url: 'menu/actions.html',
            icon: 'toolbar-actions.gif',
            desc: 'Bind the same behavior to multiple buttons, toolbar and menu items using the Ext.Action class.'
        }]
    },{
        title: 'Templates and DataView',
        samples: [{
            text: 'Templates',
            url: 'core/templates.html',
            icon: 'templates.gif',
            desc: 'A simple example of rendering views from templates bound to data objects.'
        },{
            text: 'DataView',
            url: 'view/data-view.html',
            icon: 'data-view.gif',
            desc: 'A basic DataView with custom plugins for editable labels and drag selection of items.'
        },{
            text: 'DataView (advanced)',
            url: 'view/chooser.html',
            icon: 'chooser.gif',
            desc: 'A more customized DataView supporting sorting and filtering with multiple templates.'
        }]
    },{
		title   : 'Drag and Drop',
		samples :  [{
			text : 'Grid to Grid Drag and Drop',
			url  : 'dd/dnd_grid_to_grid.html',
			icon : 'dd-gridtogrid.gif',
			desc : 'A simple drag and drop from grid to grid implementation.'
    	},{
			text : 'Grid to FormPanel Drag and Drop',
			url  : 'dd/dnd_grid_to_formpanel.html',
			icon : 'dd-gridtoformpanel.gif',
			desc : 'A basic drag and drop from grid to formpanel.'
    	},{
			text : 'Custom Drag and Drop',
			url  : 'dd/dragdropzones.html',
			icon : 'dd-zones.gif',
			desc : 'Enabling drag and drop between a DataView and a grid using DragZone and DropZone extensions.'
        }]
	},{
        title: 'Miscellaneous',
        samples: [{
            text: 'History',
            url: 'history/history.html',
            icon: 'history.gif',
            desc: 'A History manager that allows the user to navigate an Ext UI via browser back/forward.'
        },{
            text: 'Google Maps',
            url: 'window/gmap.html',
            icon: 'gmap-panel.gif',
            desc: 'A Google Maps wrapper class that enables easy display of dynamic maps in Ext panels and windows.'
        },{
            text: 'StatusBar',
            url: 'statusbar/statusbar-demo.html',
            icon: 'statusbar.gif',
            desc: 'A simple StatusBar that can be dropped into the bottom of any panel to display status text and icons.'
        },{
            text: 'StatusBar (advanced)',
            url: 'statusbar/statusbar-advanced.html',
            icon: 'statusbar-plugin.gif',
            desc: 'Customizing the StatusBar via a plugin to provide automatic form validation monitoring and error linking.'
       },{
            text: 'Slider',
            url: 'slider/slider.html',
            icon: 'slider.gif',
            desc: 'A slider component that supports vertical mode, snapping, tooltips, customized styles and more.'
        },{
            text: 'QuickTips',
            url: 'simple-widgets/qtips.html',
            icon: 'qtips.gif',
            desc: 'Various tooltip and quick tip configuration options including Ajax loading and mouse tracking.'
        },{
            text: 'Progress Bar',
            url: 'simple-widgets/progress-bar.html',
            icon: 'progress.gif',
            desc: 'A basic progress bar component shown in various configurations and with custom styles.'
        },{
            text: 'Panels',
            url: 'panel/panels.html',
            icon: 'panel.gif',
            desc: 'A basic collapsible panel example.'
        },{
            text: 'Resizable',
            url: 'resizable/basic.html',
            icon: 'resizable.gif',
            desc: 'Examples of making any element resizable with various configuration options.'
        },{
            text: 'Spotlight',
            url: 'core/spotlight.html',
            icon: 'spotlight.gif',
            desc: 'A utility for masking everything except a single element on the page to visually highlight it.'
        },{
            text: 'Localization (static)',
            url: 'locale/dutch-form.html',
            icon: 'locale-dutch.gif',
            desc: 'Demonstrates fully localizing a form by including a custom locale script.'
        },{
            text: 'Localization (dynamic)',
            url: 'locale/multi-lang.html',
            icon: 'locale-switch.gif',
            desc: 'Dynamically render various Ext components in different locales by selecting from a locale list.'
        }]
    }];

    for(var i = 0, c; c = catalog[i]; i++){
        c.id = 'sample-' + i;
    }

    var store = new Ext.data.JsonStore({
        idProperty: 'id',
        fields: ['id', 'title', 'samples'],
        data: catalog
    });

    new Ext.Panel({
        autoHeight: true,
        collapsible: true,
        frame: true,
        title: 'View Samples',
        items: new SamplePanel({
            store: store
        })
    }).render('all-demos');

    var tpl = new Ext.XTemplate(
        '<tpl for="."><li><a href="#{id}">{title:stripTags}</a></li></tpl>'
    );
    tpl.overwrite('sample-menu', catalog);

    Ext.select('#sample-spacer').remove();

    setTimeout(function(){
        Ext.get('loading').remove();
        Ext.get('loading-mask').fadeOut({remove:true});
    }, 250);

    if(window.console && window.console.firebug){
        Ext.Msg.alert('Warning', 'Firebug is known to cause performance issues with Ext JS.');
    }
});