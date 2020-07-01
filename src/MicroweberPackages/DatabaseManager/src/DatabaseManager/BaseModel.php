<?php
/*
 * This file is part of the Microweber framework.
 *
 * (c) Microweber LTD
 *
 * For full license information see
 * http://Microweber.com/license/
 *
 */
namespace MicroweberPackages\DatabaseManager;

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

    protected static function ___boot() // TODO
    {
        static::observe(new BaseModelObserver());
        parent::boot();
    }
}
