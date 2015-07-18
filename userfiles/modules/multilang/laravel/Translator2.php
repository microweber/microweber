<?php namespace Multilanguage;

use DB;

class Translator {

	private $table = 'translations';
	private $queue = array();

	public function translate($table, &$results)
	{
		if(!isset($this->queue[$table])) {
			$this->queue[$table] = array();
		}
		$this->queue[$table][] =& $results;
		//d($this->queue);
	}

	public function fetchTranslations()
	{
		//d($this->queue);
		if(count($this->queue))
		{
			foreach($this->queue as &$table) {
				foreach($table as &$results) {
					foreach($results as &$result) {
						$result->content = 'TESTIS ' . $result->content . ' TESTIS';
						d(strip_tags($result->content));
					}
				}
			}
		}
		/*$translation = DB::table($this->table)
			->whereTranslatableType($table)
			->whereTranslatableId($id)
			->first();*/
		if(count($this->queue))
			d(count(reset($this->queue)));
	}

	public function getTranslatableTables()
	{
		return [
			'content' => [
				'title',
				'content'
				]
		];
	}

}
