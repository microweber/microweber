<?php

return array(

	'driver' => 'database',

	'lifetime' => 120,

	'expire_on_close' => false,

	'files' => storage_path().'/sessions',

	'connection' => 'mysql',

	'table' => 'sessions',

	'lottery' => array(2, 100),

	'cookie' => 'microweber_session',

	'path' => '/',

	'domain' => null,

	'secure' => false,

);
