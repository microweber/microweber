
import "../api-core/core/core/@core.js";
import {MWUniversalContainer} from "../api-core/services/containers/container.js";


mw.admin = new MWUniversalContainer();

mw.admin.dispatch('init');
mw.admin.dispatch('ready');


console.log('admin-filament-app.js');
