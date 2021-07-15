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
        $product = Product::where('title', $tableData['title'])->first();
        if ($product == null) {
            $product = new Product();
            $product->title = $tableData['title'];
        }

        if (isset($tableData['content_body'])) {
            $product->content_body = $tableData['content_body'];
        }
        if (isset($tableData['description'])) {
            $product->description = $tableData['description'];
        }
        if (isset($tableData['pictures'])) {
            self::downloadMedia($tableData['pictures']);
        }

        $product->price = $tableData['price'];
        $save = $product->save();

        return $save;
    }

    public static function downloadMedia($imageUrl) {

        $photoId = md5($imageUrl);
        $filename = media_uploads_path() . $photoId . '.tmp';
        $filenameUrl = media_uploads_url() . $photoId . '.tmp';

        $downloaded = mw()->http->url($imageUrl)->download($filename);
        if ($downloaded && is_file($filename)) {
            $imageExt = strtolower(mime_content_type($filename));
            if (strpos($imageExt, 'image/') !== false) {
                $imageExt = str_replace('image/', '', $imageExt);
                $newFilename = media_uploads_path() . $photoId .'.'. $imageExt;
                rename($filename, $newFilename);
                if (is_file($newFilename)) {

                    
                }
            }
        }
    }

	public static function save($table, $tableData)
	{
		$tableData['skip_cache'] = true;
		$tableData['allow_html'] = true;
		$tableData['allow_scripts'] = true;

		return db_save($table, $tableData);
	}
}
