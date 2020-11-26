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

       $this->query->withCount(['items' => function ($itemQuery) use ($data) {
           $itemQuery->whereHas('contentItems', function ($contentItemQuery) use ($data) {
               $contentItemQuery->where('is_deleted', '=', '0');
               $contentItemQuery->where('is_active', '=', '1');
               $contentItemQuery->whereHas('contentData', function ($contentDataQuery)use ($data) {
                   $contentDataQuery->where('field_name', '=', 'qty');
                   $contentDataQuery->where(function ($contentDataFieldValueQuery) use ($data) {
                       if($data){
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
   }


}