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
   public function hasProductsInStock($in_stock = true){

       $this->query->withCount(['items' => function ($itemQuery) use ($in_stock) {
           $itemQuery->whereHas('contentItems', function ($contentItemQuery) use ($in_stock) {
               $contentItemQuery->where('is_deleted', '=', '0');
               $contentItemQuery->where('is_active', '=', '1');
               $contentItemQuery->whereHas('contentData', function ($contentDataQuery)use ($in_stock) {
                   $contentDataQuery->where('field_name', '=', 'qty');
                   $contentDataQuery->where(function ($contentDataFieldValueQuery) use ($in_stock) {
                       if($in_stock){
                           $contentDataFieldValueQuery->where('field_value', '>',0);
                           $contentDataFieldValueQuery->orWhere('field_value', '=', 'nolimit');
                       } else {
                           $contentDataFieldValueQuery->where('field_value', '=',0);
                       }

                   });
               });
           });
       }]);

   }

/*
   private function _queryProductsInStock()
   {
       return $this->query->whereHas('items', function ($itemQuery) {
           $itemQuery->whereHas('contentItems', function ($contentItemQuery) {
               $contentItemQuery->where('is_deleted', '=', '0');
               $contentItemQuery->where('is_active', '=', '1');
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
               $contentItemQuery->where('is_deleted', '=', '0');
               $contentItemQuery->where('is_active', '=', '1');
               $contentItemQuery->whereHas('contentData', function ($contentDataQuery) {
                   $contentDataQuery->where('field_name', '=', 'qty');
                   $contentDataQuery->where(function ($contentDataFieldValueQuery) {
                       $contentDataFieldValueQuery->where('field_value', '=',0);
                   });
               });
           });
       });
   }*/


}