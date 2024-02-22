<?php
namespace MicroweberPackages\Import;

/**
 * Microweber - Backup Module Database Duplicate Checker
 *
 * @namespace MicroweberPackages\Import
 * @package DatabaseWriter
 * @author Bozhidar Slaveykov
 */
class DatabaseDuplicateChecker
{

	public static $tableFields = array();

	public static function getRecognizeFields($table)
	{
		// Load defaults
		self::defaultFields();

		$method = $table . 'Fields';

		if (method_exists(self::class, $method)) {
			self::$method();
		}

		return self::$tableFields;
	}

	public static function defaultFields()
	{
		self::$tableFields[] = 'option_key';
		self::$tableFields[] = 'item_type';
		self::$tableFields[] = 'option_group';
		self::$tableFields[] = 'title';
		self::$tableFields[] = 'url';
		self::$tableFields[] = 'email';
		self::$tableFields[] = 'username';
		self::$tableFields[] = 'content_type';
		self::$tableFields[] = 'subtype';
		self::$tableFields[] = 'layout_file';
		self::$tableFields[] = 'media_type';
		self::$tableFields[] = 'filename';
		self::$tableFields[] = 'rel_type';
		self::$tableFields[] = 'field';
	}

	public static function tagging_tagsFields() {
		self::$tableFields[] = 'name';
		self::$tableFields[] = 'slug';
	}

	public static function tagging_tag_groupsFields() {
		self::$tableFields[] = 'name';
		self::$tableFields[] = 'slug';
	}

	public static function testimonialsFields()
	{
		self::$tableFields[] = 'client_picture';
		self::$tableFields[] = 'client_website';
		self::$tableFields[] = 'content';
		self::$tableFields[] = 'created_on';
	}

	public static function cartFields()
	{
		self::$tableFields[] = 'payment_verify_token';
		self::$tableFields[] = 'first_name';
		self::$tableFields[] = 'last_name';
		self::$tableFields[] = 'amount';
		self::$tableFields[] = 'created_at';
	}

	public static function commentsFields()
	{
		self::$tableFields[] = 'comment_name';
		self::$tableFields[] = 'comment_body';
		self::$tableFields[] = 'comment_email';
		self::$tableFields[] = 'from_url';
	}

	public static function calendarFields()
	{
		self::$tableFields[] = 'title';
		self::$tableFields[] = 'start_date';
		self::$tableFields[] = 'end_date';
		self::$tableFields[] = 'start_time';
		self::$tableFields[] = 'end_time';
		self::$tableFields[] = 'recurrence_type';
		self::$tableFields[] = 'recurrence_repeat_type';
	}
}
