<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Default Filesystem Disk
	|--------------------------------------------------------------------------
	|
	| Here you may specify the default filesystem disk that should be used
	| by the framework. A "local" driver, as well as a variety of cloud
	| based drivers are available for your choosing. Just store away!
	|
	| Supported: "local", "s3", "rackspace"
	|
	*/

	'default' => 'local',

	/*
	|--------------------------------------------------------------------------
	| Default Cloud Filesystem Disk
	|--------------------------------------------------------------------------
	|
	| Many applications store files both locally and in the cloud. For this
	| reason, you may specify a default "cloud" driver here. This driver
	| will be bound as the Cloud disk implementation in the container.
	|
	*/

	'cloud' => 's3',

	/*
	|--------------------------------------------------------------------------
	| Filesystem Disks
	|--------------------------------------------------------------------------
	|
	| Here you may configure as many filesystem "disks" as you wish, and you
	| may even configure multiple disks of the same driver. Defaults have
	| been setup for each driver as an example of the required options.
	|
	*/

	'disks' => [

		'local' => [
			'driver' => 'local',
			'root'   => storage_path().'/app',
		],

        'public' => [
            'driver' => 'local',
            'root'   => is_link(public_path().'/storage') ? public_path().'/storage' : storage_path('app/public'),
            'url' => site_url().'storage',
            'visibility' => 'public',
        ],

        'tmp-for-tests' => [
            'driver' => 'local',
            'root' => storage_path('storage/cache/livewire-tmp'),
        ],

		's3' => [
			'driver' => 's3',
			'key'    => env('CDN_SYNC_KEY', env('AWS_ACCESS_KEY_ID')),
			'secret' => env('CDN_SYNC_SECRET', env('AWS_SECRET_ACCESS_KEY')),
			'region' => env('CDN_SYNC_REGION', env('AWS_DEFAULT_REGION', 'us-east-1')),
			'bucket' => env('CDN_SYNC_BUCKET', env('AWS_BUCKET')),
			'endpoint' => env('CDN_SYNC_ENDPOINT', env('AWS_ENDPOINT')),
			'use_path_style_endpoint' => (bool) env('CDN_SYNC_USE_PATH_STYLE', env('AWS_USE_PATH_STYLE_ENDPOINT', false)),
			'throw' => false,
			'visibility' => 'public',
		],

		'rackspace' => [
			'driver'    => 'rackspace',
			'username'  => 'your-username',
			'key'       => 'your-key',
			'container' => 'your-container',
			'endpoint'  => 'https://identity.api.rackspacecloud.com/v2.0/',
			'region'    => 'IAD',
		],

	],



];
