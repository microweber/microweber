<?php
namespace MicroweberPackages\Tax;

use Illuminate\Database\Eloquent\Model;


class TaxType extends Model
{
    protected $fillable = [
        'name',
        'type',
        'rate',
        'company_id',
        'compound_tax',
        'collective_tax',
        'description'
    ];

    protected $casts = [
        'percent' => 'float'
    ];

    public function taxes()
    {
        return $this->hasMany(Tax::class);
    }

    public function scopeWhereCompany($query, $company_id)
    {
        $query->where('company_id', $company_id);
    }
}
