//require('./bootstrap.js');

// mw live-edit core
import '../api-core/services/bootstrap.js';
import "../api-core/core/core/@core.js";

mw.app.dispatch('init');
mw.app.dispatch('ready');


