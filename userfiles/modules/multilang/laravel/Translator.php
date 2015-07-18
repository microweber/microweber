<?php namespace Multilanguage;

require_once 'sqlparser/PHPSQLParser.php';

use PHPSQLParser;
use DB;

class Translator {

	private $table = 'translations';

	public $translatableTables;

	public function __construct()
	{
		$this->translatableTables = [
			'content' => [
				'title',
				'content'
			]
		];
	}

	public function store($sql, $bindings)
	{
    $parser = new PHPSQLParser($sql, true);

		$table = $parser->parsed['UPDATE'][0]['no_quotes'];
	  $table = str_replace(config('database.connections.' . config('database.default') . '.prefix'), '', $table);

		if (!in_array($table, array_keys($this->translatableTables))) return;

		$id = null;
		$newData = array();

		foreach ($parser->parsed['SET'] as $s => $setField)
		{
			$fieldName = substr($setField['sub_tree'][0]['base_expr'], 1, -1);
			if (in_array($fieldName, $this->translatableTables[$table])) {
				$newData[$fieldName] = $bindings[$s];
			} else if($fieldName == 'id') {
				$id = $bindings[$s];
			}
		}

		if(!$id) return;

		$translation = DB::table($this->table)->where('translatable_id', $id)->first();

		if($translation) {
			$existingData = json_decode($translation->translation);
			if($existingData) {
				$newData = (object) array_merge((array) $existingData, (array) $newData);;
			}
		}

		$newData = json_encode($newData);
		if(!$newData) $newData = null;

		$translationData = [
			'lang' => app()->getLocale(),
			'translatable_type' => $table,
			'translatable_id' => $id,
			'translation' => $data
		];

		if($translation) {
			DB::table($this->table)->update($translationData);
		}
		else {
			DB::table($this->table)->insert($translationData);
		}
	}

	public function translate($table, &$results)
	{
		if(app()->getLocale() == config('app.fallback_locale')) {
			return;
		}

		if (!in_array($table, array_keys($this->translatableTables))) return;

		$ids = array_map(function($result) { if(isset($result->id)) return $result->id; }, $results);

		if (!count($ids)) return;

		$translations = DB::table($this->table)
			->whereTranslatableType($table)
			->whereIn('translatable_id', $ids)
			->groupBy('translatable_id')
			->get();

		foreach ($results as &$result)
		{
			if (!isset($result->id)) continue;
			if (!isset($translations[$result->id])) continue;

			$trans = $translations[$result->id]->translation;
			$trans = json_decode($trans);
			if (!$trans) continue;

			foreach ($this->translatableTables[$table] as $field)
			{
				if(!isset($trans->$field)) continue;
				$result->$field = $trans->$field;
			}
		}
	}

}

app()->singleton('mw_translator', function() {
  return new Translator;
});
