<?php
namespace MicroweberPackages\Import;

use MicroweberPackages\Category\Models\Category;
use MicroweberPackages\Content\Content;
use MicroweberPackages\Page\Models\Page;
use MicroweberPackages\Post\Models\Post;
use MicroweberPackages\Product\Models\Product;
use MicroweberPackages\Product\Models\ProductVariant;

/**
 * Microweber - Backup Module Database Save
 *
 * @namespace MicroweberPackages\Backup
 * @package DatabaseWriter
 * @author Bozhidar Slaveykov
 */
class DatabaseSave
{
    public static function savePost($postData)
    {
        $blogPage = Page::where('content_type', 'page')
            ->where('is_shop', 0)
            ->where('subtype', 'dynamic')
            ->first();

        if ($blogPage == null) {
            $blogPage = new Page();
            $blogPage->title = 'Blog';
            $blogPage->content_type = 'page';
            $blogPage->subtype = 'dynamic';
            $blogPage->save();
        }

        $post = Post::where('title', $postData['title'])->first();
        if ($post == null) {
            $post = new Post();
            $post->title = $postData['title'];
            $post->parent = $blogPage->id;
        }

        if (isset($postData['first_level_categories']) && !empty($postData['first_level_categories'])) {
            if (empty($post->category_ids)) {
                $post->category_ids = [];
            }
            foreach($postData['first_level_categories'] as $firstLevelCategory) {
                $categoryIds = self::getOrInsertCategories([$firstLevelCategory], $blogPage->id);
                if (!empty($categoryIds)) {
                    $post->category_ids = array_merge($post->category_ids, $categoryIds);
                }
            }
        }

        if (isset($postData['categories']) && !empty($postData['categories'])) {
            $categoryIds = self::getOrInsertCategories($postData['categories'], $blogPage->id);
            if (!empty($categoryIds)) {
                $post->category_ids = $categoryIds;
            }
        }

        if (isset($postData['content_body'])) {
            $post->content = $postData['content_body'];
        }
        if (isset($postData['description'])) {
            $post->description = $postData['description'];
        }

        if (isset($postData['content_data']) && !empty($postData['content_data'])) {
            $post->setContentData($postData['content_data']);
        }

        $post->save();

        if (isset($postData['pictures'])) {
            self::downloadAndSaveMedia($postData['pictures'], $post->id);
        }

    }

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

        if (isset($postData['first_level_categories']) && !empty($postData['first_level_categories'])) {
            if (empty($product->category_ids)) {
                $product->category_ids = [];
            }
            foreach($postData['first_level_categories'] as $firstLevelCategory) {
                $categoryIds = self::getOrInsertCategories([$firstLevelCategory], $shopPage->id);
                if (!empty($categoryIds)) {
                    $product->category_ids = array_merge($product->category_ids, $categoryIds);
                }
            }
        }

        if (isset($productData['categories']) && !empty($productData['categories'])) {
            $categoryIds = self::getOrInsertCategories($productData['categories'], $shopPage->id);
            if (!empty($categoryIds)) {
                $product->category_ids = $categoryIds;
            }
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

    public static function getOrInsertCategories($categories, $parentPageId)
    {
        $categoryIds = [];

        $categoryParentId = false;
        foreach ($categories as $category) {

            $findCategoryQuery = Category::query();
            $findCategoryQuery->where('title', $category);
            if ($categoryParentId) {
                // Here is a child of categories
                $findCategoryQuery->where('parent_id', $categoryParentId);
            } else {
                // Here is a first level of category [parent]
                $findCategoryQuery->where('rel_id', $parentPageId);
            }
            $findCategory = $findCategoryQuery->first();

            if ($findCategory == null) {
                $findCategory = new Category();
                $findCategory->title = $category;
                if ($categoryParentId) {
                    // Category childs
                    $findCategory->parent_id = $categoryParentId;
                } else {
                    // First level of category [parent]
                    $findCategory->rel_id = $parentPageId;
                }
                $findCategory->save();
            }
            // Save latest category memory id
            $categoryParentId = $findCategory->id;
            $categoryIds[] = $findCategory->id;
        }

        return $categoryIds;
    }

	public static function save($table, $data)
	{

		$data['skip_cache'] = true;
		$data['allow_html'] = true;
		$data['allow_scripts'] = true;

		return db_save($table, $data);
	}
}
