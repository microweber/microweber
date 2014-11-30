<?php

class BaseModelObserver {
	
	protected function clearCache()
	{
		$ql = DB::getQueryLog();
		$ql = end($ql);
		$key = crc32('cache' . $ql['query'] . implode($ql['bindings']));
		Cache::forget($key);

		var_dump('cache cleared', $key, $ql['query']);
	}

	public function saved($model)
	{
		$this->clearCache();
	}

	public function updated($model)
	{
		$this->clearCache();
	}

	public function deleted($model)
	{
		$this->clearCache();
	}

	public function restored($model)
	{
		$this->clearCache();
	}
}