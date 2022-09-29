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

       $this->query->whereIn('categories.id', function ($subQuery) use ($in_stock) {
           $subQuery->select('categories_items.parent_id')->from('categories_items');
          // $subQuery->select('categories.id')->from('categories');

      //     $subQuery->leftjoin('categories_items', 'categories_items.parent_id', '=', 'categories.id');
           $subQuery->leftjoin('content', 'content.id', '=', 'categories_items.rel_id');

           $subQuery->leftjoin('content_data', 'categories_items.rel_id', '=', 'content_data.rel_id');
           $subQuery->where( 'categories_items.rel_type', '=', 'content');
           $subQuery->where('content.is_deleted', '=', '0');
           $subQuery->where('content.is_active', '=', '1');

           $subQuery->where(function ($contentDataFieldValueQuery) use ($in_stock) {
               $contentDataFieldValueQuery->where('content_data.field_name', '=', 'qty');
               if ($in_stock) {
                   $contentDataFieldValueQuery->where('content_data.field_value', '>', 0);
                   $contentDataFieldValueQuery->orWhere('content_data.field_value', '=', 'nolimit');
               } else {
                   $contentDataFieldValueQuery->where('content_data.field_value', '=', 0);
               }
           });
           $subQuery->groupBy(  'categories_items.parent_id');


           return $subQuery;
       });

//
//
//       $this->query->leftJoin('categories_items', 'categories_items.parent_id', '=', 'categories.id');
//        $this->query->leftJoin('content_data', 'categories_items.rel_id', '=', 'content_data.rel_id');
//        $this->query->leftJoin('content', 'content.id', '=', 'categories_items.rel_id');
//        $this->query->where( 'categories_items.rel_type', '=', 'content');
//        $this->query->where('content.is_deleted', '=', '0');
//        $this->query->where('content.is_active', '=', '1');
//
//       $this->query->where(function ($contentDataFieldValueQuery) use ($in_stock) {
//           $contentDataFieldValueQuery->where('content_data.field_name', '=', 'qty');
//           if ($in_stock) {
//               $contentDataFieldValueQuery->where('content_data.field_value', '>', 0);
//               $contentDataFieldValueQuery->orWhere('content_data.field_value', '=', 'nolimit');
//           } else {
//               $contentDataFieldValueQuery->where('content_data.field_value', '=', 0);
//           }
//       });
//       $this->query->groupBy(  'categories.id');


        //   ->leftJoin('custom_fields_values', 'custom_fields.id', '=', 'custom_fields_values.custom_field_id')
         //  ->leftJoin('content', 'offers.product_id', '=', 'content.id');

//       $this->query->joinRelationship('items.contentItems.contentData', function ($join) {
//
//       }) ;

//       $this->query->whereHas('items', function ($contentItemQuery) use ($in_stock) {
//
//           $contentItemQuery->whereHas('contentItems.contentData', function ($contentDataQuery) use ($in_stock) {
//               $contentDataQuery->where('field_name', '=', 'qty');
//               $contentDataQuery->where('content.is_deleted', '=', '0');
//               $contentDataQuery->where('content.is_active', '=', '1');
//               $contentDataQuery->where(function ($contentDataFieldValueQuery) use ($in_stock) {
//                   if ($in_stock) {
//                       $contentDataFieldValueQuery->where('field_value', '>', 0);
//                       $contentDataFieldValueQuery->orWhere('field_value', '=', 'nolimit');
//                   } else {
//                       $contentDataFieldValueQuery->where('field_value', '=', 0);
//                   }
//               });
//           });
//       });

//       $this->query->join('categories_items', function ($itemQuery) use ($in_stock) {
//           $itemQuery->select('categories_items.parent_id');
//
//       });



//
//
//
//
//       $this->query->whereHas('items', function ($itemQuery) use ($in_stock) {
//           $itemQuery->select('categories_items.parent_id');
//           $itemQuery->whereHas('contentItems.contentData', function ($contentItemQuery) use ($in_stock) {
//               $contentItemQuery->select('content_data.rel_id');
//               $contentItemQuery->where('is_deleted', '=', '0');
//               $contentItemQuery->where('is_active', '=', '1');
//               //$contentItemQuery->whereHas('contentData', function ($contentDataQuery)use ($in_stock) {
//               $contentItemQuery->where('field_name', '=', 'qty');
//               $contentItemQuery->where(function ($contentDataFieldValueQuery) use ($in_stock) {
//                       if($in_stock){
//                           $contentDataFieldValueQuery->where('field_value', '>',0);
//                           $contentDataFieldValueQuery->orWhere('field_value', '=', 'nolimit');
//                       } else {
//                           $contentDataFieldValueQuery->where('field_value', '=',0);
//                       }
//                   });
//               //});
//           });
//       });
//
//













   }
}
