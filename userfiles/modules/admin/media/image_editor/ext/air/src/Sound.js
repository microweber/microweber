/*
 * Ext JS Library 0.20
 * Copyright(c) 2006-2008, Ext JS, LLC.
 * licensing@extjs.com
 * 
 * http://extjs.com/license
 */

/**
 * @class Ext.air.Sound
 * 
 * @singleton
 */
Ext.air.Sound = {
	/**
	 * Play a sound.
	 * @param {String} file The file to be played. The path is resolved against applicationDirectory
	 * @param {Number} startAt (optional) A time in the sound file to skip to before playing 
	 */
	play : function(file, startAt){
		var soundFile = air.File.applicationDirectory.resolvePath(file);
		var sound = new air.Sound();
		sound.load(new air.URLRequest(soundFile.url));
		sound.play(startAt);
	}
};
