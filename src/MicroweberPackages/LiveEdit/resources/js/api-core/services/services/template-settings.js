import MicroweberBaseClass from "../containers/base-class.js";

import axios from 'axios';

export class TemplateSettings extends MicroweberBaseClass {

    constructor() {
        super();


    }
    helperClasses = {
        external_grids_col_classes: [
            'col-1',
            'col-2',
            'col-3',
            'col-4',
            'col-5',
            'col-6',
            'col-7',
            'col-8',
            'col-9',
            'col-10',
            'col-11',
            'col-12',
            'col-lg-1',
            'col-lg-2',
            'col-lg-3',
            'col-lg-4',
            'col-lg-5',
            'col-lg-6',
            'col-lg-7',
            'col-lg-8',
            'col-lg-9',
            'col-lg-10',
            'col-lg-11',
            'col-lg-12',
            'col-md-1',
            'col-md-2',
            'col-md-3',
            'col-md-4',
            'col-md-5',
            'col-md-6',
            'col-md-7',
            'col-md-8',
            'col-md-9',
            'col-md-10',
            'col-md-11',
            'col-md-12',
            'col-sm-1',
            'col-sm-2',
            'col-sm-3',
            'col-sm-4',
            'col-sm-5',
            'col-sm-6',
            'col-sm-7',
            'col-sm-8',
            'col-sm-9',
            'col-sm-10',
            'col-sm-11',
            'col-sm-12',
            'col-xs-1',
            'col-xs-2',
            'col-xs-3',
            'col-xs-4',
            'col-xs-5',
            'col-xs-6',
            'col-xs-7',
            'col-xs-8',
            'col-xs-9',
            'col-xs-10',
            'col-xs-11',
            'col-xs-12',
            'row'
        ],
        external_css_no_element_classes: [
            'container',
            'navbar',
            'navbar-header',
            'navbar-collapse',
            'navbar-static',
            'navbar-static-top',
            'navbar-default',
            'navbar-text',
            'navbar-right',
            'navbar-center',
            'navbar-left',
            'nav navbar-nav',
            'collapse',
            'header-collapse',
            'panel-heading',
            'panel-body',
            'panel-footer'
        ],
        section_selectors: ['.module-layouts'],
        external_css_no_element_controll_classes: [
            'container',
            'container-fluid',
            'edit',
            'noelement',
            'no-element',
            'mw-skip',
            'allow-drop',
            'nodrop',
            'mw-open-module-settings',
            'module-layouts',
            'mw-layout-background-block',
            'mw-layout-background-node',
            'mw-layout-background-overlay',
            'mw-layout-container',
        ]
    }
    reloadStylesheet(source_file, option_group) {

        axios.get(mw.settings.api_url + "template/delete_compiled_css?path="+source_file+"&option_group=" + option_group)
            .then(function (response){

            mw.top().notification.success("Reloading styles", 7000);

            var canvasWindow = mw.app.canvas.getWindow();
            var stylesheet = canvasWindow.document.getElementById('theme-style');

            if (stylesheet) {
                stylesheet.setAttribute('href', response.data.new_file + '&t=' + mw.random());
            }

        });
    }

};




