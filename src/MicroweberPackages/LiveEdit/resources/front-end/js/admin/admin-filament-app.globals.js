import {MWUniversalContainer} from "../core/container.js";



if(!mw.app) {
    mw.admin = new MWUniversalContainer();
    mw.app = mw.admin;
}


mw.widget = {};
