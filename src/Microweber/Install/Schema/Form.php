<?php
namespace Microweber\Install\Schema;

class Form
{

	public function get()
	{
		return [
			'forms_data' => [
				'created_at' => 'dateTime',
				'created_by' => 'integer',
				'rel_type' => 'string',
				'rel_id' => 'string',
				'list_id' => 'integer',
				'form_values' => 'text',
				'module_name' => 'string',
				'url' => 'string',
				'user_ip' => 'string',
				'$index' => [
					'rel_type',
					'rel_id',
					'list_id'
				]
			],
			'forms_lists' => [
				'created_at' => 'dateTime',
				'created_by' => 'integer',
				'title' => 'string',
				'description' => 'text',
				'custom_data' => 'text',
				'module_name' => 'string',
				'last_export' => 'dateTime',
				'last_sent' => 'dateTime',
				'$index' => [
					'title'
				]
			]
		];
	}
}
