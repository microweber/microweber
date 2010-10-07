if (window.console != undefined) {
	console.log('Microweber Javascript Framework Loaded');
}

/*
 * Microweber v0.1 - Javascript Framework
 * 
 * Copyright (c) 2010 Mass Media Group (www.ooyes.net) Dual licensed under the
 * MIT (MIT-LICENSE.txt) and GPL (GPL-LICENSE.txt) licenses.
 * 
 */

window.mw = window.mw ? window.mw : {};
MW = mw = window.mw;

mw.temp = function() {
	alert('temp');
	mw.users.get();
};


