<?php

/*

type: layout

name: Default

description: Grid Columns

*/
?>

<?php

$columns = get_option('columns', $params['id']);
if ($columns) {
    $columns = $columns;
} elseif (isset($params['data-columns'])) {
    $columns = $params['data-columns'];
} else {
    $columns = 'col-md-6 col-lg-4';
}


$columns_xl = get_option('columns-lg', $params['id']);
$thumb_quality = '1920';
if ($columns_xl != null OR $columns_xl != false OR $columns_xl != '') {
    if ($columns_xl == 'col-lg-12') {
        $thumbs_columns = 1;
    } else if ($columns_xl == 'col-lg-6') {
        $thumbs_columns = 2;
    } else if ($columns_xl == 'col-lg-4') {
        $thumbs_columns = 3;
    } else if ($columns_xl == 'col-lg-3') {
        $thumbs_columns = 4;
    } else if ($columns_xl == 'col-lg-2') {
        $thumbs_columns = 6;
    }

    $thumb_quality = 1920 / $thumbs_columns;
}
?>

<style>
    #posts-<?php print $params['id']; ?> .big-news .post-holder .thumbnail{
        height: 250px;
        margin: -20px -20px 0 -20px;
        background-repeat: no-repeat;
        background-size: cover;
        background-position: center;
    }
    #posts-<?php print $params['id']; ?> .big-news .post-holder h3 a{
        text-decoration: none;
    }
    #posts-<?php print $params['id']; ?> .big-news .post-holder {
        padding: 25px;
        background: var(--background);
        box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;
        margin: 20px 0;
        border-radius: 5px;
        border: 1px solid #ccc;
        text-align: left;
    }

    #posts-<?php print $params['id']; ?> .post-list-image{
        width: 240px;
    }



</style>

<?php if (!empty($data)): ?>
<div class="row" id="posts-<?php print $params['id']; ?>">
    <div class="col-lg-12 mx-auto">
        <div class="row big-news">

                <?php foreach ($data as $key => $item): ?>
                    <?php
                    $itemData = content_data($item['id']);
                    $itemTags = content_tags($item['id']);
                    ?>

                    <div class="<?php print $columns; ?>" data-aos="fade-up" data-aos-delay="<?php echo $key; ?>00" itemscope itemtype="<?php print $schema_org_item_type_tag ?>">
                        <div class="post-holder">
                            <a href="<?php print $item['link'] ?>" itemprop="url">
                                <div class="thumbnail-holder">
                                    <?php if ($itemTags): ?>
                                        <div class="tags">
                                            <?php foreach ($itemTags as $tag): ?>
                                                <?php if ($key < 3): ?>
                                                    <span class="badge badge-primary"><?php echo $tag; ?></span>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (!isset($show_fields) or $show_fields == false or in_array('thumbnail', $show_fields)): ?>
                                        <div class="thumbnail" itemprop="image" style="background: url('<?php print thumbnail($item['image'], 535, 285, true); ?>')">
                                            <!--<img src="<?php print thumbnail($item['image'], 535, 285, true); ?>"/>-->
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </a>
                            <br>
                            <?php if (!isset($show_fields) or $show_fields == false or in_array('title', $show_fields)): ?>
                                <h6 itemprop="name" class="m-0"><a href="<?php print $item['link'] ?>"><?php print $item['title'] ?></a></h6>
                            <?php endif; ?>

                            <?php if (!isset($show_fields) or $show_fields == false or in_array('created_at', $show_fields)): ?>
                                <small class="text-muted"><?php echo date_system_format($item['created_at']) ; ?></small>
                            <?php endif; ?>



                            <?php if (!isset($show_fields) or $show_fields == false or in_array('description', $show_fields)): ?>
                                <p itemprop="description" class="mt-3"><?php print $item['description'] ?></p>
                            <?php endif; ?>

                            <?php if (!isset($show_fields) or $show_fields == false or in_array('read_more', $show_fields)): ?>
                                <a href="<?php print $item['link'] ?>" itemprop="url" class="button-8 m-t-20"><span><?php
                                        if($read_more_text){
                                            print $read_more_text;
                                        } else {
                                            print 'Read more';
                                        }
                                        ?></span></a>
                            <?php endif; ?>



                            @if (is_array($item['prices']) and !empty($item['prices']))


                                <div class="post-price-holder clearfix">
                                    @if (!$show_fields || in_array('price', $show_fields))
                                        @if (isset($item['prices']) && is_array($item['prices']))
                                            @php $val1 = array_shift($item['prices']); @endphp
                                            <span class="price">{{ currency_format($val1) }}</span>
                                        @endif
                                    @endif
                                    @if (!$show_fields || in_array('add_to_cart', $show_fields))

                                        @php
                                            $add_cart_text = $add_to_cart_text ?? __('Add to cart');
                                        @endphp
                                        @if (is_array($item['prices']) and !empty($item['prices']))
                                            <button class="btn btn-primary" type="button"
                                                    onclick="mw.cart.add_and_checkout('{{ $item['id'] }}');">
                                                <i class="icon-shopping-cart glyphicon glyphicon-shopping-cart"></i>&nbsp;{{ $add_cart_text }}
                                            </button>
                                        @endif


                                    @endif
                                </div>


                                @foreach ($item['prices'] as $k => $v)
                                    <div class="clear posts-list-proceholder mw-add-to-cart-{{ $item['id'].$count }}">
                                        <input type="hidden" name="price" value="{{ $v }}"/>
                                        <input type="hidden" name="content_id" value="{{ $item['id'] }}"/>
                                    </div>
                                    @break
                                @endforeach

                            @endif

                        </div>
                    </div>
                <?php endforeach; ?>

        </div>
    </div>
</div>

<?php if (isset($pages_count) and $pages_count > 1 and isset($paging_param)): ?>
    <module type="pagination" pages_count="<?php echo $pages_count; ?>" paging_param="<?php echo $paging_param; ?>"/>
<?php endif; ?>



<?php else: ?>
<div class="module-posts-template-no-data">
    <div>No content found.</div>
</div>
<?php endif; ?>

