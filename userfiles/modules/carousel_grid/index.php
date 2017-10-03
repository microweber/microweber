<script>mw.require("<?php print $config['url_to_module'] ?>jquery.justified.min.js", 'jquery_justified'); </script>

<style scoped="scoped">
    .image-container {
        position: relative;
    }

    .photo-container {
        float: left;
        position: relative;
        overflow: hidden;
    }

    .image-thumb {
        position: relative;
        background-color: #eee;
        cursor: pointer;
        cursor: zoom-in;
    }
</style>

<?php

$data = get_pictures('rel_type=modules&rel_id=' . $params['id']);
$items_number = get_option('items_number', $params['id']);
$maxRowHeight = get_option('max_row_height', $params['id']);
$rowHeight = get_option('row_height', $params['id']);
if (!$maxRowHeight) {
    $maxRowHeight = 250;
}
if (!$rowHeight) {
    $rowHeight = 120;
}
if (!$items_number) {
    $items_number = 10;
}
$items_number = intval($items_number);
?>
<?php $rand = uniqid(); ?>
<script>
    mw.lib.require('slick');
    $(document).ready(function () {


        var allslides = $("#carousel-grid-<?php print $params['id']; ?> .carousel-grid-slide");
        var slidesDone = 0;
        (function (allslides, slidesDone) {
            allslides.each(function () {
                var photos = $("img", this), data = [], datao = [], scope = $(this);
                photos.each(function () {
                    datao.push(this.src);
                });


                (function (scope, datao) {

                    mw.image.preloadForAll(datao, function (width, height) {
                        data.push({url: this.src, width: width, height: height});
                    }, function () {
                        var config = {
                            images: data,
                            rowHeight: <?php print $rowHeight ?>,
                            maxRowHeight: <?php print $maxRowHeight ?>,
                            thumbnailPath: function (photo, width, height) {
                                return photo.url;
                            },
                            getSize: function (photo) {

                                return {
                                    width: photo.width,
                                    height: photo.height
                                };
                            },
                            margin: 1

                        }
                        scope.data('__config', config);
                        scope[0].__time = null;
                        scope.empty().justifiedImages(scope.data('__config'));

                        $(window).on('resize orientationchange', function(){
                          clearTimeout(scope[0].__time);
                          scope[0].__time = setTimeout(function(){
                            scope.empty().justifiedImages(scope.data('__config'));
                            setTimeout(function(){
                              $("#carousel-grid-<?php print $params['id']; ?> img.image-thumb").not('.gallery-ready').each(function(i){
                             var el = $(this);
                             el.addClass('gallery-ready')

                               el.on('click', function(){
                                 if(!!window['gallery<?php print $rand; ?>']){
                                   mw.gallery(gallery<?php print $rand; ?>, i)
                                 }

                               })

                           })
                            }, 100)
                          }, 333);
                        })
                       $("#carousel-grid-<?php print $params['id']; ?> img.image-thumb").not('.gallery-ready').each(function(i){
                         var el = $(this);
                         el.addClass('gallery-ready')

                           el.on('click', function(){
                             if(!!window['gallery<?php print $rand; ?>']){
                               mw.gallery(gallery<?php print $rand; ?>, i)
                             }

                           })


                       })

                        slidesDone++;
                        if (slidesDone == allslides.length) {
                            $("#carousel-grid-<?php print $params['id']; ?>").slick({
                                adaptiveHeight: true
                            })
                        }
                    })
                })(scope, datao)

            })

        })(allslides, slidesDone)


    })
</script>

<?php if ($data): ?>
    <script>
        gallery<?php print $rand; ?> = [
                <?php foreach($data  as $item): ?>{image: "<?php print ($item['filename']); ?>", description: "<?php print $item['title']; ?>"},
            <?php endforeach;  ?>
        ];
    </script>
<?php endif; ?>

<div class="carousel-grid" id="carousel-grid-<?php print $params['id']; ?>">
    <?php
    $count = 0;
    $html = '';
    if ($data == false) {
        print lnotif("Click to add images");
    } else {
        foreach ($data as $key => $pic) {
            $count++;
            $html .= $count == 1 ? '<div class="carousel-grid-slide">' : '';
            $html .= '<img src="' . thumbnail($pic['filename'], 600) . '" >' . ($pic['title'] != null ? ('<span class="carousel-grid-slide-description">' . $pic['title'] . '</span>') : '');
            $html .= ($count == $items_number || !isset($data[$key+1])) ? '</div>' : '';
            $count = $count != $items_number ? $count : 0;
        }
        print $html;
    }
    ?>
</div>