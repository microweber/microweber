import MicroweberBaseClass from "../containers/base-class.js";
import CssBoxShadowValuesParser from "./css-box-shadow-values-parser.js";
import ColorPaletteManager from "./color-palette-manager.js";
import PredefinedElementStylesManager from "./predefined-element-styles-manager.js";

import axios from 'axios';


export class TemplateSettings extends MicroweberBaseClass {

    cssBoxShadowsParser = null;
    colorPaletteManager = null;
    predefinedElementStylesManager =  null;

    constructor() {
        super();

        this.cssBoxShadowsParser = new CssBoxShadowValuesParser();
        this.colorPaletteManager = new ColorPaletteManager();
        this.predefinedElementStylesManager = new PredefinedElementStylesManager();
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
        mw_grids_col_classes: [
            'mw-ui-col',
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

        axios.get(mw.settings.api_url + "template/delete_compiled_css?path=" + source_file + "&option_group=" + option_group)
            .then(function (response) {

                mw.top().notification.success("Reloading styles", 7000);

                var canvasWindow = mw.app.canvas.getWindow();
                var stylesheet = canvasWindow.document.getElementById('theme-style');

                if (stylesheet) {
                    stylesheet.setAttribute('href', response.data.new_file + '&t=' + mw.random());
                }

            });
    }

    getPredefinedBoxShadows() {
        var predefinedShadows = [
            {
                name: "None",
                value: ""
            },
            {
                name: "Shadow 1",
                value: "rgba(100, 100, 111, 0.2) 0px 7px 29px 0px"
            },
            {
                name: "Shadow 2",
                value: "rgba(0, 0, 0, 0.15) 0px 0px 1.95px 1.95px"
            },
            {
                name: "Shadow 3",
                value: "rgba(0, 0, 0, 0.35) 0px 0px 0px 5px"
            },
            {
                name: "Shadow 4",
                value: "rgba(0, 0, 0, 0.16) 0px 0px 0px 1px"
            },
            {
                name: "Shadow 5",
                value: "rgba(0, 0, 0, 0.24) 0px 0px 0px 3px"
            },
            {
                name: "Shadow 6",
                value: "rgba(99, 99, 99, 0.2) 0px 2px 8px 0px"
            },
            {
                name: "Shadow 7",
                value: "rgba(0, 0, 0, 0.16) 0px 1px 4px 0px, rgb(51, 51, 51) 0px 0px 0px 3px"
            },
            {
                name: "Shadow 8",
                value: "rgba(0, 0, 0, 0.02) 0px 1px 3px 0px, rgba(27, 31, 35, 0.15) 0px 0px 0px 1px"
            },
            {
                name: "Shadow 9",
                value: "rgba(0, 0, 0, 0.1) 0px 4px 12px 0px"
            },
            {
                name: "Shadow 10",
                value: "rgba(0, 0, 0, 0.25) 0px 54px 55px 0px, rgba(0, 0, 0, 0.12) 0px -12px 30px 0px, rgba(0, 0, 0, 0.12) 0px 4px 6px 0px, rgba(0, 0, 0, 0.17) 0px 12px 13px 0px, rgba(0, 0, 0, 0.09) 0px -3px 5px 0px"
            },
            {
                name: "Shadow 11",
                value: "rgba(0, 0, 0, 0.05) 0px 6px 24px 0px, rgba(0, 0, 0, 0.08) 0px 0px 0px 1px"
            },
            {
                name: "Shadow 12",
                value: "rgba(0, 0, 0, 0.16) 0px 10px 36px 0px, rgba(0, 0, 0, 0.06) 0px 0px 0px 1px"
            },
            {
                name: "Shadow 13",
                value: "rgba(17, 12, 46, 0.15) 0px 48px 100px 0px"
            },
            {
                name: "Shadow 14",
                value: "rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px, rgba(10, 37, 64, 0.35) 0px -2px 6px 0px inset"
            },
            {
                name: "Shadow 15",
                value: "rgba(255, 255, 255, 0.1) 0px 1px 1px 0px inset, rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px"
            },
            {
                name: "Shadow 16",
                value: "rgba(50, 50, 93, 0.25) 0px 50px 100px -20px, rgba(0, 0, 0, 0.3) 0px 30px 60px -30px"
            },
            {
                name: "Shadow 17",
                value: "rgba(50, 50, 93, 0.25) 0px 13px 27px -5px, rgba(0, 0, 0, 0.3) 0px 8px 16px -8px"
            },
            {
                name: "Shadow 18",
                value: "rgba(50, 50, 93, 0.25) 0px 2px 5px -1px, rgba(0, 0, 0, 0.3) 0px 1px 3px -1px"
            },
            {
                name: "Shadow 19",
                value: "rgb(38, 57, 77) 0px 20px 30px -10px"
            },
            {
                name: "Shadow 20",
                value: "rgba(6, 24, 44, 0.4) 0px 0px 0px 2px, rgba(6, 24, 44, 0.65) 0px 4px 6px -1px, rgba(255, 255, 255, 0.08) 0px 1px 0px inset"
            },
            {
                name: "Shadow 21",
                value: "rgba(50, 50, 93, 0.25) 0px 6px 12px -2px, rgba(0, 0, 0, 0.3) 0px 3px 7px -3px"
            },
            {
                name: "Shadow 22",
                value: "rgba(50, 50, 93, 0.25) 0px 30px 60px -12px, rgba(0, 0, 0, 0.3) 0px 18px 36px -18px"
            },
            {
                name: "Shadow 23",
                value: "rgba(50, 50, 93, 0.25) 0px 30px 60px -12px inset, rgba(0, 0, 0, 0.3) 0px 18px 36px -18px inset"
            },
            {
                name: "Shadow 24",
                value: "rgba(0, 0, 0, 0.12) 0px 1px 3px 0px, rgba(0, 0, 0, 0.24) 0px 1px 2px 0px"
            },
            {
                name: "Shadow 25",
                value: "rgba(0, 0, 0, 0.16) 0px 3px 6px 0px, rgba(0, 0, 0, 0.23) 0px 3px 6px 0px"
            },
            {
                name: "Shadow 26",
                value: "rgba(0, 0, 0, 0.19) 0px 10px 20px 0px, rgba(0, 0, 0, 0.23) 0px 6px 6px 0px"
            },
            {
                name: "Shadow 27",
                value: "rgba(0, 0, 0, 0.25) 0px 14px 28px 0px, rgba(0, 0, 0, 0.22) 0px 10px 10px 0px"
            },
            {
                name: "Shadow 28",
                value: "rgba(0, 0, 0, 0.3) 0px 19px 38px 0px, rgba(0, 0, 0, 0.22) 0px 15px 12px 0px"
            },
            {
                name: "Shadow 29",
                value: "rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 2px 6px 2px"
            },
            {
                name: "Shadow 30",
                value: "rgba(60, 64, 67, 0.3) 0px 1px 2px 0px, rgba(60, 64, 67, 0.15) 0px 1px 3px 1px"
            },
            {
                name: "Shadow 31",
                value: "rgba(0, 0, 0, 0.05) 0px 0px 0px 1px"
            },
            {
                name: "Shadow 32",
                value: "rgba(0, 0, 0, 0.05) 0px 1px 2px 0px"
            },
            {
                name: "Shadow 33",
                value: "rgba(0, 0, 0, 0.1) 0px 1px 3px 0px, rgba(0, 0, 0, 0.06) 0px 1px 2px 0px"
            },
            {
                name: "Shadow 34",
                value: "rgba(0, 0, 0, 0.1) 0px 4px 6px -1px, rgba(0, 0, 0, 0.06) 0px 2px 4px -1px"
            },
            {
                name: "Shadow 35",
                value: "rgba(0, 0, 0, 0.1) 0px 10px 15px -3px, rgba(0, 0, 0, 0.05) 0px 4px 6px -2px"
            },
            {
                name: "Shadow 36",
                value: "rgba(0, 0, 0, 0.1) 0px 20px 25px -5px, rgba(0, 0, 0, 0.04) 0px 10px 10px -5px"
            },
            {
                name: "Shadow 37",
                value: "rgba(0, 0, 0, 0.25) 0px 25px 50px -12px"
            },
            {
                name: "Shadow 38",
                value: "rgba(0, 0, 0, 0.06) 0px 2px 4px 0px inset"
            },
            {
                name: "Shadow 39",
                value: "rgba(0, 0, 0, 0.1) 0px 0px 5px 0px, rgba(0, 0, 0, 0.1) 0px 0px 1px 0px"
            },
            {
                name: "Shadow 40",
                value: "rgba(0, 0, 0, 0.07) 0px 1px 2px 0px, rgba(0, 0, 0, 0.07) 0px 2px 4px 0px, rgba(0, 0, 0, 0.07) 0px 4px 8px 0px, rgba(0, 0, 0, 0.07) 0px 8px 16px 0px, rgba(0, 0, 0, 0.07) 0px 16px 32px 0px, rgba(0, 0, 0, 0.07) 0px 32px 64px 0px"
            },
            {
                name: "Shadow 41",
                value: "rgba(0, 0, 0, 0.09) 0px 2px 1px 0px, rgba(0, 0, 0, 0.09) 0px 4px 2px 0px, rgba(0, 0, 0, 0.09) 0px 8px 4px 0px, rgba(0, 0, 0, 0.09) 0px 16px 8px 0px, rgba(0, 0, 0, 0.09) 0px 32px 16px 0px"
            },
            {
                name: "Shadow 42",
                value: "rgba(255, 255, 255, 0.5) 0px 0px 0px 1px inset, rgba(0, 0, 0, 0.12) 0px 1px 3px 1px, rgba(0, 0, 0, 0.1) 0px 1px 2px 0px"
            }
        ];


        return predefinedShadows;
    }


}




