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

    public function addresses()
    {
        return $this->hasMany(Address::class);
    }

    public function estimates()
    {
        return $this->hasMany(Estimate::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }

    public function expenses()
    {
        return $this->hasMany(Expense::class);
    }

    public function billingAddress()
    {
        return $this->hasOne(Address::class)->where('type', Address::BILLING_TYPE);
    }

    public function shippingAddress()
    {
        return $this->hasOne(Address::class)->where('type', Address::SHIPPING_TYPE);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function user()
    {
        return $this->belongsTo(\MicroweberPackages\User\User::class);
    }

    public function scopeWhereDisplayName($query, $displayName)
    {
        return $query->where('name', 'LIKE', '%'.$displayName.'%');
    }

    public function scopeWherePhone($query, $phone)
    {
        return $query->where('phone', 'LIKE', '%'.$phone.'%');
    }

    public function activeOptions()
    {
        return [
            1 => 'Active',
            0 => 'Inactive',
            2 => 'In-Progress'
        ];
    }

    public function delete()
    {
        if ($this->estimates()->exists()) {
            $this->estimates()->delete();
        }

        if ($this->invoices()->exists()) {
            $this->invoices()->delete();
        }

        if ($this->payments()->exists()) {
            $this->payments()->delete();
        }

        if ($this->addresses()->exists()) {
            $this->addresses()->delete();
        }

        $this->delete();

        return true;
    }
}
