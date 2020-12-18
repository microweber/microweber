<?php
namespace Microweber\Install\Schema;

class MailTemplates
{

	public function get()
	{
		return [
			'mail_templates' => [
				'type' => 'string',
				'name' => 'string',
				'subject' => 'string',
				'message' => 'text',
				'from_name' => 'string',
				'from_email' => 'string',
				'custom' => 'text',
				'copy_to' => 'string',
				'plain_text' => 'integer',
				'is_active' => 'integer'
			]
		];
	}
}
