<?php
namespace Microweber\Install\Schema;

class MailSubscribe
{
	public function get()
	{
		return [
			'mail_subscribers' => [
				'rel_type' => 'string',
				'rel_id' => 'string',
				'mail_address' => 'string',
				'mail_provider_id' => 'integer'
			],
			'mail_providers' => [
				'provider_name' => 'string',
				'provider_settings' => 'text',
				'is_active' => 'integer'
			]
		];
	}
}