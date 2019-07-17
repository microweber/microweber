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
				'message' => 'string',
				'attachments' => 'string',
				'from_name' => 'string',
				'from_email' => 'string',
				'custom' => 'string',
				'copy_to' => 'string',
				'plain_text' => 'integer',
				'is_active' => 'integer'
			]
		];
	}
}
