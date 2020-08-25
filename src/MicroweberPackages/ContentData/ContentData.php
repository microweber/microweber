<?php
namespace MicroweberPackages\ContentData;

use Illuminate\Database\Eloquent\Model;

class ContentData extends Model
{

    protected $table = 'content_data';

    protected $fillable = [
        'rel_type',
        'rel_id',
        'field_value',
        'field_name',
        'content_id',
        'edited_by',
        'created_by'
    ];





//
//    public function setAttribute($key, $value)
//    {
//
//
//
//        //parent::setAttribute($key, $value);
//    }




    /**
     * @param $value
     */
    public function setSkuAttribute($value)
    {
        $this->attributes['field_name'] = 'sku';
        $this->attributes['field_value'] =$value;
        return $this;
    }  /**
     * @param $value
     */


//    public function setQtyAttribute($value)
//    {
//        $tmp['field_name'] = 'qty';
//        $tmp['field_value'] =$value;
//        $this->attributes [] = $tmp;
//        return $this;
//    }


    public function scopeSku($query)
    {
        return $query->where('field_name', 'sku');
    }

}
