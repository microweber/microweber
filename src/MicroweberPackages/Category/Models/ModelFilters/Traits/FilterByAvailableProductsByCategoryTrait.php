<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 10/15/2020
 * Time: 3:42 PM
 */

namespace MicroweberPackages\Category\Models\ModelFilters\Traits;

trait FilterByAvailableProductsByCategoryTrait
{
   public function hasProductsInStock($data){
      if($data) {
          $this->_queryProductsInStock();
      } else {
          $this->_queryProductsOutOfStock();
      }
   }


   private function _queryProductsInStock()
   {
       return $this->query->whereHas('items', function ($itemQuery) {
           $itemQuery->whereHas('contentItems', function ($contentItemQuery) {
               $contentItemQuery->whereHas('contentData', function ($contentDataQuery) {
                   $contentDataQuery->where('field_name', '=', 'qty');
                   $contentDataQuery->where(function ($contentDataFieldValueQuery) {
                       $contentDataFieldValueQuery->where('field_value', '>',0);
                       $contentDataFieldValueQuery->orWhere('field_value', '=', 'nolimit');
                   });
               });
           });
       });
   }

   private function _queryProductsOutOfStock()
   {
       return $this->query->whereHas('items', function ($itemQuery) {
           $itemQuery->whereHas('contentItems', function ($contentItemQuery) {
               $contentItemQuery->whereHas('contentData', function ($contentDataQuery) {
                   $contentDataQuery->where('field_name', '=', 'qty');
                   $contentDataQuery->where(function ($contentDataFieldValueQuery) {
                       $contentDataFieldValueQuery->where('field_value', '=',0);
                   });
               });
           });
       });
   }


}