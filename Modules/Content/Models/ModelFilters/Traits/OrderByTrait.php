<?php
/**
 * Created by PhpStorm.
 * User: Bojidar
 * Date: 10/15/2020
 * Time: 3:42 PM
 */

namespace Modules\Content\Models\ModelFilters\Traits;

use Illuminate\Support\Facades\DB;
use MicroweberPackages\Database\Facades\DatabaseManager;

trait OrderByTrait
{
    /**
     * Valid order columns to prevent SQL injection
     */
    protected array $validOrderColumns = [
        'id', 'title', 'url', 'created_at', 'updated_at', 'position',
        'content_type', 'is_active', 'is_deleted', 'is_home', 'is_shop',
        'description', 'content', 'content_body', 'price', 'orders',
    ];

    /**
     * Valid order directions
     */
    protected array $validOrderDirections = ['asc', 'desc', 'ASC', 'DESC'];

    public function orderBy($orderBy)
    {
        $orderColumn = $orderBy;
        $orderDirection = 'desc';

        $orderBy = str_replace(' ', ',', $orderBy);

        if (strpos($orderBy, ',') !== false) {
            $orderBy = explode(',', $orderBy);
            $orderColumn = $orderBy[0];
            $orderDirection = strtolower(trim($orderBy[1]));
        }

        if (isset($this->input['orderDirection'])) {
            $orderDirection = strtolower(trim($this->input['orderDirection']));
        }

        // Validate order direction
        if (!in_array($orderDirection, $this->validOrderDirections, true)) {
            $orderDirection = 'desc';
        }

        // Validate order column to prevent SQL injection
        if (!in_array($orderColumn, $this->validOrderColumns, true)) {
            // If column is not in whitelist, check if it's a valid column name (alphanumeric and underscores only)
            if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $orderColumn)) {
                $orderColumn = 'id'; // Default to safe column
            }
        }

        switch ($orderColumn) {
            case 'price':
                 $this->query->whereHas('customFieldsPrices', function ($query) use ($orderColumn, $orderDirection) {
                    return $query->orderBy('custom_fields_values.value', $orderDirection);
                })->orderByPowerJoins('customFieldsPrices.value', $orderDirection);

                break;
            case 'orders':

                $table = DatabaseManager::real_table_name('cart');
                $this->query->orderByLeftPowerJoinsCount('cart.order')
                    ->select(
                        'content.*',
                        DB::raw("count(" . $table . ".order_id) AS total_orders"))
                    ->orderBy('total_orders', $orderDirection);

                break;
            default:
                 $this->query->orderBy($this->query->getModel()->getTable().'.'.$orderColumn, $orderDirection);
                break;
        }

         return $this->query;
    }


}
