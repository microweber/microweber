import "./core.js";
import "../core/@core.js";
import "../core/ajax.js";
import "../core/url.js";
import "../core/events.js";
import "../core/reload-module.js";
import "../core/_.js";
import "../widgets/hamburger.js";

import { AdminTools } from "./admin-tools.service.js";



mw.tools = new AdminTools(mw.app);

import {Helpers} from "../core/helpers.js";



for ( let i in Helpers ) {
    mw.tools[i] = Helpers[i];
}



console.log('frontend.js loaded');
