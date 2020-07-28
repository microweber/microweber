<?php

namespace MicroweberPackages\Invoice;

use Illuminate\Database\Eloquent\Model;
use MicroweberPackages\Invoice\Company;

class Customer extends Model
{
    // Fillabble Example
    // protected $fillable = ['name', 'email', 'active'];

    // Guarded Example
    protected $guarded = [];

    // protected $attributes = [
    //	'active' => 1
    // ];

    public function getActiveAttribute($attribute)
    {
        return $this->activeOptions()[$attribute];
    }

    public function scopeActive($query)
    {
        return $query->where('active', 1);
    }

    public function scopeInactive($query)
    {
        return $query->where('active', 0);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function user()
    {
        return $this->belongsTo(\MicroweberPackages\User\User::class);
    }

    public function activeOptions()
    {
        return [
            1 => 'Active',
            0 => 'Inactive',
            2 => 'In-Progress'
        ];
    }
}