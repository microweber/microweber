<?php namespace Microweber\Install;

class WebserverInstaller {

		private $supported = array('Apache', 'Nginx', 'IIS');

		public function run()
		{
			$prefix = 'setup';
			$supported = get_class_methods(get_class());
			
			$soft = $_SERVER['SERVER_SOFTWARE'];

			foreach ($supported as $serverName) 
			{
				if(starts_with($soft, str_replace($prefix, '', $serverName)))
				{
					$this->$serverName();
				}
			}
		}

		public function setupApache()
		{
			dd('testmest');
		}

		public function setupNginx()
		{
		}

		public function setupIIS()
		{
		}
}