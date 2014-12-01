<?php 

use Illuminate\Database\Eloquent\Model as Eloquent;


abstract class CachedModel extends Eloquent {

	public $errors;
	public static $cache = false;
	public static $cache_driver = null;
	public static $cache_ttl = 15;
	public static $object_cached = array();
	public static $rules = array();
	public static $messages = array();

	public static function key_cache($id)
	{
		return Str::lower(get_called_class()).'_'.$id;
	}

	protected function fire_event($event)
	{
		parent::fire_event($event);

		// if cache enabled
		if (static::$cache === true)
		{
			// events to detect
			if (in_array($event, array('updated', 'saved', 'deleted')))
			{
				$ckey = static::key_cache($this->id);				

				// remove exists cache
				Cache::forget($ckey);
			}
		}
	}

	public function valid($data = array(), $withs = array(), $rules = array(), $messages = array())
	{
		$valid = true;

		if ( ! empty($rules) || ! empty(static::$rules))
		{
			// If empty rules, so get from static.
			if (empty($rules))
			{
				$rules = static::$rules;

				// Merge validation rules from related.
				if (count($withs)) foreach ($withs as $with)
				{
					if (class_exists($with))
					{
						$rules = array_merge($rules, $with::$rules);
					}
				}
			}

			// If empty messages, so get from static.
			if (empty($messages))
			{
				$messages = static::$messages;

				// Merge validation messages from related.
				if (count($withs)) foreach ($withs as $with)
				{
					if (class_exists($with))
					{
						$messages = array_merge($messages, $with::$messages);
					}
				}
			}

			// If the model exists, this is an update.
			if ($this->exists)
			{

				$_data = array();
				foreach ($data as $key => $value)
				{
				    if ( ! array_key_exists($key, $this->original) or $value != $this->original[$key])
				    {
				        $_data[$key] = $value;
				    }
				}

				// Then just validate the fields that are being updated.
				$rules = array_intersect_key($rules, $_data);
			}


			// Construct the validator
			$validator = Validator::make($data, $rules, $messages);

			// Validate.
			$valid = $validator->valid();

			// If the model is valid, unset old errors.
			// Otherwise set the new ones.
			if ($valid)
			{
				$this->errors = array();
			}
			else
			{
				$this->errors = $validator->errors;
			}
		}

		return $valid;
	}

	public function __set($key, $value)
	{
		// only update an attribute if there's a change
		if (!array_key_exists($key, $this->attributes) || $value !== $this->$key)
		{
			parent::__set($key, $value);
		}
	}

	public static function __callStatic($method, $parameters)
	{
		if (strcmp($method, 'find') === 0 and ! isset($parameters[1]))
		{
			$id = $parameters[0];
			
			$ckey = static::key_cache($id);

			if ( ! $result = array_get(static::$object_cached, $ckey))
			{
				if (static::$cache === true)
				{					
					if ( ! $result = Cache::get($ckey))
					{
						$result = parent::__callStatic('find', $parameters);
						 
						if ( ! is_null($result))
						{						
							Cache::put($ckey, $result, static::$cache_ttl);
						}
					}
				}
				else
				{
					$result = parent::__callStatic('find', $parameters); 
				}

				array_set(static::$object_cached, $ckey, $result);
			}
			
			return $result;
		}
	
		return parent::__callStatic($method, $parameters); 
	}

}
