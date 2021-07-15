<?php
namespace MicroweberPackages\Backup;

use MicroweberPackages\Product\Models\Product;

/**
 * Microweber - Backup Module Database Save
 *
 * @namespace MicroweberPackages\Backup
 * @package DatabaseWriter
 * @author Bozhidar Slaveykov
 */
class DatabaseSave
{
    public static function saveProduct($tableData)
    {
        $product = new Product();
        $product->title = $tableData['title'];
        $product->content_body = $tableData['content_body'];
        $product->description = $tableData['description'];
        $product->price = $tableData['price'];
        $save = $product->save();
        return $save;
    }

	public static function save($table, $tableData)
	{
		$tableData['skip_cache'] = true;
		$tableData['allow_html'] = true;
		$tableData['allow_scripts'] = true;

		return db_save($table, $tableData);
	}
}
