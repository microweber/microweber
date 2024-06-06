
import "../api-core/core/core/@core.js";
import {MWUniversalContainer} from "../api-core/services/containers/container.js";
import {AdminFilament} from "./admin-filament/admin-filament.js";


mw.admin = new MWUniversalContainer();
mw.admin.filament = new AdminFilament();
mw.admin.dispatch('init');
mw.admin.dispatch('ready');


console.log('admin-filament-app.js');
