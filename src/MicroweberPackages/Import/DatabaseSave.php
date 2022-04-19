<?php
namespace MicroweberPackages\Import;

use MicroweberPackages\Page\Models\Page;
use MicroweberPackages\Product\Models\Product;
use MicroweberPackages\Product\Models\ProductVariant;
use function db_save;
use function media_uploads_path;
use function media_uploads_url;
use function mw;

/**
 * Microweber - Backup Module Database Save
 *
 * @namespace MicroweberPackages\Backup
 * @package DatabaseWriter
 * @author Bozhidar Slaveykov
 */
class DatabaseSave
{
    public static function saveProduct($productData)
    {
        $shopPage = Page::where('content_type', 'page')->where('is_shop', 1)->first();
        if ($shopPage == null) {
            $shopPage = new Page();
            $shopPage->title = 'Shop';
            $shopPage->content_type = 'page';
            $shopPage->is_shop = 1;
            $shopPage->save();
        }

        $product = Product::where('title', $productData['title'])->first();
        if ($product == null) {
            $product = new Product();
            $product->title = $productData['title'];
            $product->parent = $shopPage->id;
        }

        if (isset($productData['content_body'])) {
            $product->content_body = $productData['content_body'];
        }
        if (isset($productData['description'])) {
            $product->description = $productData['description'];
        }

        $product->price = $productData['price'];

        if (isset($productData['content_data']) && !empty($productData['content_data'])) {
            $product->setContentData($productData['content_data']);
        }

        $product->save();

        if (isset($productData['pictures'])) {
            self::downloadAndSaveMedia($productData['pictures'], $product->id);
        }

        if (isset($productData['variants']) && !empty($productData['variants'])) {
            foreach ($productData['variants'] as $variantData) {

                $productVariant = ProductVariant::where('title', $variantData['title'])->where('parent', $product->id)->first();
                if ($productVariant == null) {
                    $productVariant = new ProductVariant();
                    $productVariant->title = $variantData['title'];
                    $productVariant->parent = $product->id;
                }

                if (isset($variantData['content_body'])) {
                    $productVariant->content_body = $variantData['content_body'];
                }
                if (isset($variantData['description'])) {
                    $productVariant->description = $variantData['description'];
                }

                $productVariant->price = $variantData['price'];

                if (isset($variantData['content_data']) && !empty($variantData['content_data'])) {
                    $productVariant->setContentData($variantData['content_data']);
                }

                if (isset($variantData['pictures'])) {
                    self::downloadAndSaveMedia($variantData['pictures'], $productVariant->id);
                }

                $productVariant->save();
            }
        }

        return $product->id;
    }

    public static function downloadAndSaveMedia($imageUrl, $contentId) {

        if (is_array($imageUrl)) {
            $saved = [];
            foreach ($imageUrl as $url) {
                $saved[] = self::downloadAndSaveMedia($url,$contentId);
            }
            return $saved;
        }

        $photoId = md5($imageUrl);
        $filename = media_uploads_path() . $photoId . '.tmp';
        $filenameUrl = media_uploads_url() . $photoId . '.tmp';

        $files_utils = new \MicroweberPackages\Utils\System\Files();
        $is_allowed_file = $files_utils->is_allowed_file($imageUrl);
        if (!$is_allowed_file) {
            return false;
        }

        $downloaded = mw()->http->url($imageUrl)->download($filename);
        if ($downloaded && is_file($filename)) {
            $ext = get_file_extension($imageUrl);

            $imageExt = strtolower($ext);
            $newFilename = media_uploads_path() . $photoId . '.' . $imageExt;
            @rename($filename, $newFilename);
            if (is_file($newFilename)) {
                mw()->media_manager->save([
                    'rel_id' => $contentId,
                    'rel_type' => 'content',
                    'media_type' => 'picture',
                    'name' => $photoId,
                    'filename' => $newFilename
                ]);
            }

        }
    }

	public static function save($table, $data)
	{

		$data['skip_cache'] = true;
		$data['allow_html'] = true;
		$data['allow_scripts'] = true;

		return db_save($table, $data);
	}
}
