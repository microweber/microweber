<?php

use Illuminate\Database\Eloquent\Model as Eloquent;

class BaseModel extends Eloquent
{
    protected $guarded = array();

    protected $rules = array();
    private $validator;

    public function validateAndFill($data)
    {
        $this->validator = \Validator::make($data, $this->rules);
        if ($this->validator->fails()) {
            return false;
        }
        $this->fill($data);

        return true;
    }

    protected static function boot()
    {
        static::observe(new BaseModelObserver());
        parent::boot();
    }
}
