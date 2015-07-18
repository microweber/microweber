<?php namespace Multilanguage;

use Illuminate\Database\Query\Builder;
use Illuminate\Database\Query\Processors\Processor as BaseProcessor;
use DB;

class Processor extends BaseProcessor {

 	// DB READ
	public function processSelect(Builder $query, $results)
	{
		app('mw_translator')->translate($query->from, $results);
	  return $results;
	}

}

app('db')->connection()->setPostProcessor(new Processor);

// DB WRITE
\DB::listen(function($sql, $bindings, $time)
{
  if(app()->getLocale() == config('app.fallback_locale')) {
    return;
  }

  if(starts_with($sql, 'update')) {
    app('mw_translator')->store($sql, $bindings);
  }
});
