<?php namespace Microweber\Install;

class WebserverInstaller {

		public function run()
		{
			$prefix = 'setup';
			$supported = get_class_methods(get_class());
			
			$soft = $prefix.$_SERVER['SERVER_SOFTWARE'];

			foreach ($supported as $serverName) 
			{
				$serverName = str_replace('_', '-', $serverName);
				if(starts_with($soft, $serverName))
				{
					return $this->$serverName();
				}
			}
		}

		private function storeConfig($file, $data)
		{
			$file = public_path() .'/'. $file;
			$data = trim($data);
			return \File::put($file, $data);
		}

		public function setupApache()
		{
			$forbidDirs = ['app', 'bootstrap', 'src', 'vendor'];

		if (isset($_SERVER['SCRIPT_NAME'])) {
			$rwBase = dirname($_SERVER['SCRIPT_NAME']);
		} else if (isset($_SERVER['PHP_SELF'])) {
			$rwBase = dirname($_SERVER['PHP_SELF']);
		}

			$data = '
<IfModule mod_rewrite.c>
    <IfModule mod_negotiation.c>
        Options -MultiViews
    </IfModule>
    RewriteEngine On
    	RewriteRule ^(.*)/$ '. $rwBase .'/$1 [R=301,L]
		RewriteCond %{REQUEST_FILENAME} !-f
		RewriteCond %{REQUEST_FILENAME} !-d
		RewriteRule ^(.*)$ index.php [NC,L]
</IfModule>
';

		foreach ($forbidDirs as $dir)
		{
			$this->storeConfig($dir.'/.htaccess', 'Deny from all');
		}
		
			return $this->storeConfig('.htaccess', $data);
		}

		public function setupNginx()
		{
		}

		public function setupMicrosoft_IIS()
		{
			$data = '';
			return $this->storeConfig('web.config', $data);
		}
}