/**
 * expressinstall.as v2.0 - http://blog.deconcept.com/swfobject/
 * 
 * SWFObject is (c) 2006 Geoff Stearns and is released under the MIT License:
 * http://www.opensource.org/licenses/mit-license.php
 *
 * NOTE: Your Flash movie must be at least 214px by 137px in order to use ExpressInstall.
 *        Please see http://blog.deconcept.com/swfobject/ for other notes.
 *
 * This swf needs to be AS2 because it needs to run in Flash Player 6.0.65
 *
 */
class com.deconcept.expressinstall.ExpressInstall {
	
	private static var instance:ExpressInstall;
	private var updater:MovieClip;
	private var hold:MovieClip;
	
	function ExpressInstall() {}
	
	public static function getInstance():ExpressInstall {
		if (instance == undefined) {
			instance = new ExpressInstall();
		}
		return instance;
	}
	
	public function loadUpdater():Void {
		System.security.allowDomain("fpdownload.macromedia.com");
		
		var _self = this;
		
		// hope that nothing is at a depth of 10000007, you can change this depth if needed, but you want
		// it to be on top of your content if you have any stuff on the first frame
		updater = _root.createEmptyMovieClip("deconcept_expressInstallHolder", 10000007);

		// register the callback so we know if they cancel or there is an error
		updater.installStatus = _self.onInstallStatus;
		hold = updater.createEmptyMovieClip("hold", 1);
		
		// can't use movieClipLoader because it has to work in 6.0.65
		updater.onEnterFrame = function():Void {
			if (typeof hold.startUpdate == 'function') {
				_self.initUpdater();
				this.onEnterFrame = null;
			}
		}

		var cacheBuster:Number = Math.random();
		hold.loadMovie("http://fpdownload.macromedia.com/pub/flashplayer/update/current/swf/autoUpdater.swf?"+ cacheBuster);
	}

	private function initUpdater():Void {
		hold.redirectURL = _root.MMredirectURL;
		hold.MMplayerType = _root.MMplayerType;
		hold.MMdoctitle = _root.MMdoctitle;
		hold.startUpdate();
	}

	private function onInstallStatus(msg):Void {
		if (msg == "Download.Complete") {
			// Installation is complete. In most cases the browser window that this SWF 
			// is hosted in will be closed by the installer or manually by the end user

		} else if (msg == "Download.Cancelled") {
			// The end user chose "NO" when prompted to install the new player
			// by default no User Interface is presented in this case. It is left up to 
			// the developer to provide an alternate experience in this case

			// feel free to change this to whatever you want, js errors are sufficient for this example
			getURL("javascript:alert('This content requires a more recent version of the Adobe Flash Player.')");
		} else if (msg == "Download.Failed") {
			// The end user failed to download the installer due to a network failure
			// by default no User Interface is presented in this case. It is left up to 
			// the developer to provide an alternate experience in this case

			// feel free to change this to whatever you want, js errors are sufficient for this example
			getURL("javascript:alert('There was an error downloading the Flash Player update. Please try again later, or visit adobe.com/go/getflashplayer/ to download the latest version of the Flash plugin.')");
		}
	}
}
