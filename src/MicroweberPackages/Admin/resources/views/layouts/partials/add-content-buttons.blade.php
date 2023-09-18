@props(['quickContentAdd' => false,'showEditContentButtonForContentId' => false])


<?php $custom_view = url_param('view'); ?>
<?php $custom_action = url_param('action'); ?>
<?php event_trigger('content.create.menu'); ?>
<?php $create_content_menu = mw()->module_manager->ui('content.create.menu'); ?>

<?php




$appendIframeModeSuffix = '';
if (isset($isIframe) and $isIframe) {
    $appendIframeModeSuffix = '?iframe=true';

    if (isset($quickContentAdd) and $quickContentAdd) {
        $appendIframeModeSuffix .= '&quickContentAdd=true';
    }


} else {
    $appendIframeModeSuffix = '?addEditContent=true';
}
$allBlogs = [];
$allShops = [];
$firstBlogId = false;
$firstShopId = false;
$allBlogs = app()->content_repository->getAllBlogPages();
$allShops = app()->content_repository->getAllShopPages();
if($allBlogs){
    $firstBlogId = $allBlogs[0]['id'];
}
if($allShops){
    $firstShopId = $allShops[0]['id'];
}



$editContentBtnData = false;
$editContentUrl = false;
if($showEditContentButtonForContentId){
    $editContentBtnData = get_content_by_id($showEditContentButtonForContentId);

if($editContentBtnData){
    $editContentUrl = route('admin.content.edit', $showEditContentButtonForContentId);

    if (\Route::has('admin.' . $editContentBtnData['content_type'] . '.edit', $showEditContentButtonForContentId)) {
        $editContentUrl = route('admin.' . $editContentBtnData['content_type'] . '.edit', $showEditContentButtonForContentId);
    }


    if ($appendIframeModeSuffix) {
        $editContentUrl = $editContentUrl . $appendIframeModeSuffix;
    }
}



}


?>





@if($editContentBtnData && $editContentUrl)
    <a  href="{{ $editContentUrl }}" class="col-12 text-start d-flex align-items-center flex-wrap admin-add-new-modal-buttons me-auto">
        <div class="col-lg-2 mx-2 modal-add-new-buttons-img tblr-body-color">

            @include('content::admin.content.livewire.components.icon', ['content'=>$editContentBtnData])

        </div>

        <div class="col-lg-9 ps-3">
            <h3 class="  font-weight-bolder">

                @if($editContentBtnData && isset($editContentBtnData['content_type']) && $editContentBtnData['content_type'] == 'post')
                    @lang('Edit current post')
                @elseif($editContentBtnData && isset($editContentBtnData['content_type']) && $editContentBtnData['content_type'] == 'product')
                    @lang('Edit current product')
                @elseif($editContentBtnData && isset($editContentBtnData['content_type']) && $editContentBtnData['content_type'] == 'page')
                    @lang('Edit current page')
                @else
                    @lang('Edit current content')
                @endif
            </h3>


            <p class="  font-weight-bold mb-0 modal-add-new-buttons-p   d-lg-block">
                @lang('Edit') {{ $editContentBtnData['title'] }}
            </p>
        </div>





    </a>
@endif





<?php if (!empty($create_content_menu)): ?>
    <?php foreach ($create_content_menu as $type => $item): ?>
    <?php $title = (isset($item['title'])) ? ($item['title']) : false; ?>
    <?php $class = (isset($item['class'])) ? ($item['class']) : false; ?>
    <?php $html = (isset($item['html'])) ? ($item['html']) : false; ?>
    <?php $type = (isset($item['content_type'])) ? ($item['content_type']) : false; ?>
    <?php $subtype = (isset($item['subtype'])) ? ($item['subtype']) : false; ?>
    <?php $base_url = (isset($item['base_url'])) ? ($item['base_url']) : false; ?>
    <?php

    $additionalButtons = [];

    $base_url = route('admin.content.create');
    if (\Route::has('admin.' . $item['content_type'] . '.create')) {
        $base_url = route('admin.' . $item['content_type'] . '.create');
    }
    if ($appendIframeModeSuffix) {
        $base_url = $base_url . $appendIframeModeSuffix;
    }
    $base_url_orig = $base_url;
    if ($item['content_type'] == 'post') {
        if($firstBlogId){
            $base_url = $base_url .'&recommended_content_id='.$firstBlogId; ;
        }
        if($allBlogs && count($allBlogs) > 1){
            foreach ($allBlogs as $buttonItem){
                $base_url_item = $base_url_orig .'&recommended_content_id='.$buttonItem['id']; ;

                $additionalButtons[] = [
                    'title' => $buttonItem['title'],
                    'url' =>$base_url_item,

                    'id' => $buttonItem['id'],
                ];
            }
        }
    }
    if ($item['content_type'] == 'product') {
        if($firstShopId){
            $base_url = $base_url .'&recommended_content_id='.$firstShopId; ;
        }
        if($allShops && count($allShops) > 1){
            foreach ($allShops as $buttonItem){
                $base_url_item = $base_url_orig .'&recommended_content_id='.$buttonItem['id']; ;
                $additionalButtons[] = [
                    'title' => $buttonItem['title'],
                    'url' =>$base_url_item,
                    'id' => $buttonItem['id'],
                ];
            }
        }
    }





    ?>

<div  href="<?php print $base_url; ?>" onclick="window.location.href='<?php print $base_url; ?>'"
   class="col-12 text-start d-flex align-items-center flex-wrap admin-add-new-modal-buttons me-auto cursor-pointer">


        <?php if ($item['content_type'] == 'page') { ?>
    <div class="col-lg-2 mx-2 modal-add-new-buttons-img">
        <img src="<?php print modules_url()?>/microweber/api/libs/mw-ui/assets/img/mw-admin-add-page.svg" alt="">
    </div>

    <div class="col-lg-9 ps-3">
        <h3 class="font-weight-bolder"> <?php print $title; ?>      </h3>


        <p class="  font-weight-bold mb-0 modal-add-new-buttons-p d-none d-lg-block">
            Create a new page to your website or online store, choose from pre-pared page designs
        </p>
    </div>
    <?php } ?>

        <?php if ($item['content_type'] == 'post') { ?>
    <div class="col-lg-2 mx-2 modal-add-new-buttons-img">
        <img src="<?php print modules_url()?>/microweber/api/libs/mw-ui/assets/img/mw-admin-add-post.svg" alt="">
    </div>
    <div class="col-lg-9 ps-3">

        <div class="d-flex">

            <h3 class="  font-weight-bolder">
                <a href="<?php print $base_url; ?>"> <?php print $title; ?></a>

            </h3>

                <?php if ($additionalButtons){ ?>
                <span  class="mw-add-content-quick-parents">
                      <?php foreach ($additionalButtons as $additionalButton){ ?>
                    <a href="<?php print $additionalButton['url']; ?>" class="btn btn-ghost-secondary btn-sm me-2">
                            <?php print $additionalButton['title']; ?></a>
                     <?php } ?>
                </span>
            <?php } ?>

        </div>


        <p class="  font-weight-bold mb-0 modal-add-new-buttons-p d-none d-lg-block">
            Add new post to your blog page, linked to category of main page on your website
        </p>
    </div>
    <?php } ?>

        <?php if ($item['content_type'] == 'category') { ?>
    <div class="col-lg-2 mx-2 modal-add-new-buttons-img">
        <img src="<?php print modules_url()?>/microweber/api/libs/mw-ui/assets/img/mw-admin-add-category.svg" alt="">
    </div>
    <div class="col-lg-9 ps-3">
        <h3 class="  font-weight-bolder">  <?php print $title; ?></h3>


        <p class="  font-weight-bold mb-0 modal-add-new-buttons-p d-none d-lg-block">
            Add new category and organize your blog posts or items from the shop in the right way
        </p>
    </div>
    <?php } ?>

        <?php if ($item['content_type'] == 'product') { ?>
    <div class="col-lg-2 mx-2 modal-add-new-buttons-img">
        <img src="<?php print modules_url()?>/microweber/api/libs/mw-ui/assets/img/mw-admin-add-product.svg" alt="">

    </div>
    <div class="col-lg-9 ps-3">


        <div class="d-flex">

            <h3 class="  font-weight-bolder">
                <a href="<?php print $base_url; ?>"> <?php print $title; ?></a>

            </h3>

                <?php if ($additionalButtons){ ?>
            <span  class="mw-add-content-quick-parents">
                      <?php foreach ($additionalButtons as $additionalButton){ ?>
                    <a href="<?php print $additionalButton['url']; ?>" class="btn btn-ghost-secondary btn-sm me-2">
                            <?php print $additionalButton['title']; ?></a>
                     <?php } ?>
                </span>
            <?php } ?>

        </div>


        <p class="  font-weight-bold mb-0 modal-add-new-buttons-p d-none d-lg-block">
            Add new category and organize your blog posts or items from the shop in the right way
        </p>
    </div>
    <?php } ?>
</div>

<?php endforeach; ?>




<?php endif; ?>
